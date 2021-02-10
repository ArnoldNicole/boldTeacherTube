<template>
	<div class="container-fluid">
		<hr class="border border-danger">
		<div class="row justify-content-center">
			<div class="col-md-12 mt-4">
						<p class="_title0">Classes <Button @click="addModal=true">Add Room <Icon type="md-add" /> </Button></p>
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
						      	<Button type="info" size="small" @click="showEditModal(room, i)" class="btn-small">
						      			<Icon type="ios-clipboard-outline" />
						      	Edit</Button>

						      	<Button type="warning" size="small" @click="showReadModal(room, i)" class="btn-small">
						      	<Icon type="ios-eye-outline" />View</Button>

						      	<Button type="error" size="small" @click="deleteRoom(room, i)" :loading="room.isDeleting" class="btn-small"><Icon type="ios-trash" /> Delete</Button>
						      </td>
						    </tr>				    
						  </tbody>
						</table>
			</div>
		</div>

		<Modal
		        v-model="addModal"
		        title="Add Class Dialog box"
		        :mask-closable='false'
		        :closable="false"
		        :width="90"
		        >
		        <Form>
		        	<FormItem label="Class Name">
		        		<input v-model="data.className" type="text" placeholder="Give your class a name" class="form-control" >
		        	</FormItem>

		        	<FormItem label="Class Code">
		        		<input type="text" v-model="data.classCode" placeholder="Give your class a unique code" class="form-control" >
		        	</FormItem>

		        	<FormItem label="Max number of Students">
		        		<input v-model="data.studentsCount" type="number" placeholder="How many students in your class?" class="form-control" >
		        	</FormItem>

		        	<FormItem label="Status">
		        		<RadioGroup v-model="data.status">
		        		    <Radio label="Active">Active and available to join</Radio>
		        		    <Radio label="Pending">Pending: Students can`t enroll</Radio>
		        		    <Radio label="Private">Private: Active but disbled for self enrolling</Radio>
		        		</RadioGroup>
		        	</FormItem>		        	

		        </Form>

		        <div slot='footer'>
		        	<Button type="error" @click="addModal=false">Close</Button>
					<Button type="primary" @click="addRoom" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Save Class'}}</Button>
		        </div>
		</Modal>
		<Modal
			v-model="editModal"
			title="Edit Class"
			:mask-closable="false"
			:closable="false"
			:width="80"
			:scrollable="true"
			>
			<Form>
				<FormItem label="Class Name">
					<input v-model="editData.className" type="text" placeholder="Give your class a name" class="form-control" >
				</FormItem>

				<FormItem label="Class Code">
					<input type="text" v-model="editData.classCode" placeholder="Give your class a unique code" class="form-control" >
				</FormItem>

				<FormItem label="Max number of Students">
					<input v-model="editData.studentsCount" type="number" placeholder="How many students in your class?" class="form-control" >
				</FormItem>

				<FormItem label="Status">
					<RadioGroup v-model="editData.status">
					    <Radio label="Active">Active and available to join</Radio>
					    <Radio label="Pending">Pending: Students can`t enroll</Radio>
					    <Radio label="Private">Private: Active but disbled for self enrolling</Radio>
					</RadioGroup>
				</FormItem>		        	

			</Form>
			<div slot="footer">
				<Button type="error" @click="editModal=false">Close</Button>
				<Button type="primary" @click="editRoom" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit room'}}</Button>
			</div>
			</Modal>

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
				data: {
					className:'',
					classCode:'',
					studentsCount:'',
					status:'',
				},
				addModal:false,
				isAdding:false,
				rooms:[],
				editModal : false,
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
			async addRoom(){
				this.isAdding = true
				if (this.data.className.trim()=='') {
					this.isAdding = false
					return this.e('Class Name is required')
				}

				if (this.data.classCode.trim()=='') {
					this.isAdding = false
					return this.e('Class Code is required')
				}

				if (this.data.studentsCount.trim()=='') {
					this.isAdding = false
					return this.e('Maximum students is required')
				}

				if (this.data.status.trim()=='') {
					this.isAdding = false
					return this.e('Status is required')
				}
				const res = await this.callApi('post','/app/create_room', this.data)
				if (res.status===201) {	
					this.rooms.unshift(res.data);
					this.s('Class has been successfully')
					this.data = ''
					this.isAdding = false
					return this.addModal=false
				}else{
					if(res.status==422){
					if(res.data.errors.className){
						this.isAdding = false
						return this.e(res.data.errors.className[0])
					}
					if(res.data.errors.classCode){
						this.isAdding = false
						return this.e(res.data.errors.classCode[0])
					}
					if(res.data.errors.studentsCount){
						this.isAdding = false
						return this.e(res.data.errors.studentsCount[0])
					}
					if(res.data.errors.status){
						this.isAdding = false
						return this.e(res.data.errors.status[0])
					}
					
				}else{
					this.isAdding = true
					return this.swr()
				}
				}
				if (res.status == 401) {
					return this.e('You are using an expired session token. PLease login again')
					window.location="/classes/"
				}
			},

			async editRoom(){
			this.isAdding = true
			if(this.editData.className.trim()==''){
				this.isAdding = false
				return this.e('Class name is required')
			}

			if(this.editData.classCode.trim()==''){
				this.isAdding = false
				return this.e('Class Code is required')
			}

			if(this.editData.studentsCount.trim()==''){
				this.isAdding = false
				return this.e('Class Students Required is required')
			}
			const response_data = await this.callApi('patch', '/app/edit_room', this.editData)
			if(response_data.status==200){
				this.rooms[this.index].className = this.editData.className
				this.rooms[this.index].classCode = this.editData.classCode
				this.rooms[this.index].studentsCount = this.editData.studentsCount
				this.rooms[this.index].status = this.editData.status
				this.s('Class has been edited successfully!')
				this.editModal = false
				return this.isAdding = false
				
			}else{
				if(response_data.status==422){
					this.isAdding=false					
					if(response_data.data.errors.className){
						return this.e(response_data.data.errors.className[0])
					}

					if(response_data.data.errors.classCode){
						return this.e(response_data.data.errors.classCode[0])
					}

					if(response_data.data.errors.studentsCount){
						return this.e(response_data.data.errors.studentsCount[0])
					}

					if(response_data.data.errors.status){
						return this.e(response_data.data.errors.status[0])
					}
										
				 }
				else{
					//console.log(response_data)
					//this.e(response_data.status)
					this.isAdding=false
					return this.swr()					
				 }
				
			}

		},
		showEditModal(room, index){
			let obj = {
				id:room.id,
				className:room.className,
				classCode:room.classCode,
				studentsCount:room.studentsCount,
				status:room.status,
						}
			this.editData = obj
			this.editModal = true
			return this.index = index
		},

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
			return this.index = index
		},
		

		async deleteRoom(room, i)
		{
			if(!confirm('Are you sure you want to delete this room?')) return
				 this.$set(room, 'isDeleting', true)
				const res_del = await this.callApi('delete', '/app/deleteRoom', room)
				if (res_del.status==200) {
					this.rooms.splice(i,1)
					return this.s('Room Deleted successfully')
				}
				else{
					return this.e('Something Went Wrong')
				}
		}			
			
		},
		async created(){
				const respd = await this.callApi('get','/app/fetch_rooms')
				if (respd.status===200) {
					this.s('Load complete')
					return this.rooms = respd.data
				}else{
					return this.swr()
				}

				if (respd.status == 410) {
					return this.e('You are using an expired auth token. Redirecting to login')
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