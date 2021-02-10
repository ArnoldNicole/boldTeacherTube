<template>
	<div>
		<Row :gutter="16" class="mt-2">
			<Col span="6">
			<div class="scrollable">
				<List v-for="(myClass, c) in classLists" :key="c" :border="true" class="mb-1">
					<span @click="findNotes(myClass)">
				        <ListItem @click="findNotes(myClass)">
				            <ListItemMeta  :title="myClass.classCode" :description="myClass.className" />
				        </ListItem>	
				    </span>					   
				  </List>	
			</div>
			</Col>
			<Col span="18">
				<h4 class="text-center">Notes are shared to classes. to read shared notes, select class first</h4>
				<Modal
					v-model="readModal"					
					:mask-closable="false"
					:closable="false"
					:fullscreen="true">
					<div slot="header">Read Notes Dialogue Box</div>
					<p>{{readData.noteName}}</p>
					<div v-html="readData.noteBody"></div>
					<div slot="footer">
						<Button type="error" @click="readModal=false" :disabled="isActive" :loading="isActive">Close</Button>
					</div>
				</Modal>
				<Card v-if="notes.length">
					<table class="table table-hover table-responsive-md">
					  <thead>
					    <tr class="bg-info">
					      <th scope="col">#</th>
					      <th scope="col">Note Name</th>
					      <th scope="col">Created at</th>
					      <th scope="col">Action</th>
					    </tr>
					  </thead>
					  <tbody>
					    <tr v-for="(note, i) in notes" :key="i" v-if="notes.length">
					      <th scope="row">{{note.id}}</th>
					      <td>{{note.noteName}}</td>
					      <td>{{note.created_at}}</td>
					      <td>
					      	<Button type="warning" size="small" @click="showReadModal(note, i)" class="btn-small">
					      	<Icon type="ios-eye-outline" />Read</Button>
					      </td>
					    </tr>				    
					  </tbody>
					</table>
				</Card>
			</Col>
		</Row>
	</div>
</template>
<script>
	export default{
		data(){
			return{
				data:{
					classCode: '',
					className: '',					
					id: '',
				},
				classLists:[],	
				isActive:false,			
				notes:[],
				readData:[],
				readModal:false,
				index:-1

			}
		},
		methods:{
			showReadModal(note, index){
				let obj = {
					id : note.id, 
					noteName : note.noteName,
					noteBody: note.noteBody
				}
				this.readData = obj
				this.readModal = true
				this.index = index
			},
			async findNotes(myClass){
				this.data.id = myClass.id				
				// if (this.data.id.trim()=='') {
				// 	this.isActive = false
				// 	return this.e('Select a valid class')					
				// }
				const query = await this.callApi('post','/student/fetchnotes',this.data)
				if (query.status == 200) {
					this.notes = query.data
				}
				else{
					this.e('Error loading notes')
				}
			}
		},
		async created(){
			const res_data = await this.callApi('get','/app/fetch_my_rooms')
			if (res_data.status===200) {				
				this.classLists  = res_data.data
			}else{
				this.e('Error fetching your classes')
			}
		}
	}
</script>

<style>
	.scrollable{
		max-height: 400px;
		overflow-y: scroll;
	}
	::-webkit-scrollbar {
	          display: none;
	      }
</style>