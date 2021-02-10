<template>
	<div class="container-fluid">
		<hr class="border border-danger">
		<div class="row justify-content-center">
			<div class="col-md-12 mt-4">
						<hr class="border border-info">			
					
						<table class="table table-hover table-responsive-md">
						  <thead>
						    <tr class="bg-info">
						      <th scope="col">#</th>
						      <th scope="col">Class Name</th>
						      <th scope="col">Class Code</th>
						      <th scope="col">Action</th>
						    </tr>
						  </thead>
						  <tbody>
						    <tr v-for="(room, i) in rooms" :key="i" v-if="rooms.length">
						      <th scope="row">{{room.id}}</th>
						      <td>{{room.className}}</td>
						      <td>{{room.classCode}}</td>
						      <td>
						      	<Button type="warning" size="small" @click="showReadModal(room, i)" class="btn-small">
						      	<Icon type="ios-eye-outline" />View</Button>
						      </td>
						    </tr>				    
						  </tbody>
						</table>
			</div>
		</div>

			<Modal
				v-model="readModal"
				title="Preview Class Information"
				:mask-closable="false"
				:closable="false"
				:width="60"				
				>
				<div class="row justify-content-center">
					<div class="col-md-10">
						<Card>
							<p>Class Name: {{editData.className}}</p>

							<p>Class Code: {{editData.classCode}}</p>

							<p>Maximum Students Allowed: {{editData.studentsCount}}</p>

							<p>Status: {{editData.status}}</p>
							<p class="text-info">To read notes shared on this classes, got to <kbd>notes</kbd> then <kbd>Shared Notes</kbd> and select <kbd>{{editData.className}}</kbd></p>
						</Card>
					</div>
				</div>
				
				<div slot="footer">
					<Button type="error" @click="readModal=false">Close</Button>
				</div>
				</Modal>
	</div>
</template>

<script>
	// import { VueEditor } from "vue2-editor";
	export default {
		data (){
			return {
				rooms:[],				
				readModal:false,				
				editData : {
					className:'',
					classCode:'',
					studentsCount:'',
					status:'',
					id:''
				},				
			    index : -1,  
			}
		},		
		methods : {
			showReadModal(room, index){
			let obj = {
					id:room.id,
					className:room.className,
					classCode:room.classCode,
					studentsCount:room.studentsCount,
					status:room.status,							
						}
			this.editData = obj
			this.readModal = true
			this.index = index
		}
	},
		
		async created(){
				const respd = await this.callApi('get','/app/fetch_my_rooms')
				if (respd.status===200) {
					this.s('Load complete')
					this.rooms = respd.data
				}else{
					this.swr()
				}

				if (respd.status == 410) {
					this.e('You are using an expired auth token. Redirecting to login')
					window.location = '/classes/'
				}

			}
	}
</script>
<style>
	::-webkit-scrollbar {
            display: none;
        }
</style>