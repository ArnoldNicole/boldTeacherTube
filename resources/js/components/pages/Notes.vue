<template>
	<div class="container-fluid">
		<hr class="border border-danger">
		<div class="row justify-content-center">
			<div class="col-md-12 mt-4">
						<p class="_title0">Notes <Button @click="addModal=true">Add Note <Icon type="md-add" /> </Button></p>
					
					
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
						      	<Button type="info" size="small" @click="showEditModal(note, i)" class="btn-small">
						      			<Icon type="ios-clipboard-outline" />
						      	Edit</Button>

						      	<Button type="warning" size="small" @click="showReadModal(note, i)" class="btn-small">
						      	<Icon type="ios-eye-outline" />Read</Button>

						      	<Button type="info" size="small" @click="showShareModal(note, i)" class="btn-small">
						      	<Icon type="ios-share-alt" />
						      	 Share</Button>

						      	<Button type="error" size="small" @click="deleteNote(note, i)" :loading="note.isDeleting" class="btn-small"><Icon type="ios-trash" /> Delete</Button>
						      </td>
						    </tr>				    
						  </tbody>
						</table>
			</div>
		</div>

		<Modal
		        v-model="addModal"
		        title="Add Note Dialog box"
		        :mask-closable='false'
		        :closable="false"
		        >
		        <Form>
		        	<FormItem label="Note Name">
		        		<input v-model="data.noteName" placeholder="Enter a note name" class="form-control" >
		        	</FormItem>
		        	<div class="row form-group">
		        					<label class="col-form-label font-weight-bold col-md-3 text-md-right">Write your note</label>
		        					<div class="col-md-9">
		        					<vue-editor v-model="data.noteBody"/> 
		        					</div>
		        			</div>

		        </Form>

		        <div slot='footer'>
		        	<Button type="error" @click="addModal=false">Close</Button>
					<Button type="primary" @click="addNote" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add note'}}</Button>
		        </div>
		</Modal>
		<Modal
			v-model="editModal"
			title="Edit note"
			:mask-closable="false"
			:closable="false"
			:width="80"
			:scrollable="true"
			>
			<Input v-model="editData.noteName" placeholder="Edit note name" />

			<div class="row form-group">
							<label class="col-form-label font-weight-bold col-md-3 text-md-right">Write your note</label>
							<div class="col-md-9">
							<vue-editor v-model="editData.noteBody"/> 
							</div>
					</div>

			<div slot="footer">
				<Button type="error" @click="editModal=false">Close</Button>
				<Button type="primary" @click="editNote" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit note'}}</Button>
			</div>
			</Modal>

			<Modal
				v-model="readModal"
				title="Read and Preview"
				:mask-closable="false"
				:closable="false"
				:fullscreen="true"
				>
				<p>{{shareData.noteName}}</p>
				<div v-html="shareData.noteBody"></div>
				<div slot="footer">
					<Button type="error" @click="readModal=false">Close</Button>
				</div>
				</Modal>

				<Modal
					v-model="shareModal"
					title="Share Notes Diague Box"
					:mask-closable="false"
					:closable="false"
					:width='80'					
					>
					<p>Share {{shareData.noteName}}</p>
					<!-- <div v-html="editData.noteBody"></div> -->
					<p class="font-weight-bold text-danger">
						Shared notes are only visible to students in your specific class.
						Sharing note with id {{shareData.id}}
					<Form>
						<FormItem label="Select Class to share note on">
							<Select v-model="shareData.class" multiple style="width:100">
							       <Option
							        v-for="myClass in classLists"
							        :value="myClass.id"
							        :key="myClass.id"
							        >
							        {{ myClass.className }}
							         <span class="float-right">
							        	 {{myClass.classCode}}
							     	</span> 
							       </Option>
							   </Select>
						</FormItem>						
					</Form>

					</p>
					<div slot="footer">
						<Button type="info" :loading="sharing" :disabled="sharing" @click="shareNote">Share <Icon type="ios-share-alt" /> </Button>
						<Button type="error" @click="shareModal=false">Close</Button>
					</div>
					</Modal>


	</div>
</template>

<script>
	import { VueEditor } from "vue2-editor";
	export default {
		data (){
			return {
				data: {
					noteName:'',
					noteBody:''
				},				
				classLists:[],
				addModal:false,
				isAdding:false,
				notes:[],
				editModal : false,
				readModal:false,
				shareModal:false,
				editData : {
				noteName: '',
				noteBody:''
			},
			sharing:false,
				shareData : {
					noteName: '',
					noteBody:'',
					id:'',
					class:[],
			},
			    index : -1,  
			}
		},		
		methods : {
			async shareNote(){
			this.sharing=true
			if(this.shareData.class==[]) 
				{
					this.sharing=false				
					return this.e('Note should be shared with atleast one class')
				}
				 const share = await this.callApi('post','/app/notes/share', this.shareData)
				 if (share.status == 200) {
				 	this.s('Note shared to selected classes');
				 	this.sharing = false
				 	this.shareModal = false
				 }else{
				 	this.i(share.message)
				 }

			},
			async addNote(){
				if (this.data.noteName.trim()=='') {
					return this.e('Note Name is required')
				}
				const res = await this.callApi('post','/app/create_note', this.data)
				if (res.status===201) {	
					this.notes.unshift(res.data);
					this.s('Note Added successfully')
					this.addModal=false
					this.data.noteName=''
					this.data.noteBody=''
				}else{
					if(res.status==422){
					if(res.data.errors.noteName){
						this.e(res.data.errors.noteName[0])
					}
					if(res.data.errors.noteBody){
						this.e(res.data.errors.notBody[0])
					}
					
				}else{
					this.swr()
				}
				}
			},

			async editNote(){
			if(this.editData.noteName.trim()=='') return this.e('Note name is required')
			if(this.editData.noteBody.trim()=='') return this.e('Note Body is required')
			const res = await this.callApi('post', '/app/edit_note', this.editData)
			if(res.status===200){
				this.notes[this.index].noteName = this.editData.noteName
				this.notes[this.index].noteBody = this.editData.noteBody
				this.s('Note has been edited successfully!')
				this.editModal = false
				
			}else{
				if(res.status==422){
					if(res.data.errors.noteName){
						this.e(res.data.errors.noteName[0])
					}
					if(res.data.errors.body){
						this.e(res.data.errors.noteBody[0])
					}
					
				}else{
					this.swr()
				}
				
			}

		},
		showEditModal(note, index){
			let obj = {
				id : note.id, 
				noteName : note.noteName,
				noteBody: note.noteBody
			}
			this.editData = obj
			this.editModal = true
			this.index = index
		},

		showReadModal(note, index){
			let obj = {
				id : note.id, 
				noteName : note.noteName,
				noteBody: note.noteBody
			}
			this.shareData = obj
			this.readModal = true
			this.index = index
		},

		showShareModal(note, index){
			let obj = {
				id : note.id, 
				noteName : note.noteName,
				//noteBody: note.noteBody
			}			
			this.shareData = obj
			//this.shareData.id = obj.id
			//this.fetchClasses()
			this.sharing=false
			this.shareModal = true
			this.index = index
		},

		async deleteNote(note, i)
		{
			if(!confirm('Are you sure you want to delete this note?')) return
				 this.$set(note, 'isDeleting', true)
				const res = await this.callApi('delete', '/app/deleteNote', note)
				if (res.status==200) {
					this.notes.splice(i,1)
					this.s('Note Deleted successfully')
				}
				else{
					this.e('Something Went Wrong')
				}
		}
		,
		// async fetchClasses(){
		// 	const res_data = await this.callApi('get','/app/fetch_rooms')
		// 	if (res_data.status===200) {				
		// 		this.classLists = res.data
		// 	}else{
		// 		this.e('Error fetching your classes')
		// 	}
		// }			
			
		},
		async created(){
				const res = await this.callApi('get','/app/fetch_notes')
				if (res.status===200) {
					this.s('Load complete')
					this.notes = res.data
				}else{
					this.swr()
				}

				const res_data = await this.callApi('get','/app/fetch_rooms')
				if (res_data.status===200) {				
					this.classLists = res_data.data
				}else{
					this.e('Error fetching your classes')
				}

			}
	}
</script>
<style>
	::-webkit-scrollbar {
            display: none;
        }
</style>