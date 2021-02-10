<template>
	<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col-md-11">
							<p class="_title0">Lesson Plans <Button type="success" @click="addModal=true">Add Plan <Icon type="md-add" /> </Button></p>
						
						
							<table class="table table-hover table-responsive-md">
							  <thead>
							    <tr class="bg-success">
							      <th scope="col">#</th>
							      <th scope="col">Plan Name</th>
							      <th scope="col">Created at</th>
							       <th scope="col">Plan</th>
							      <th scope="col">Action</th>

							    </tr>
							  </thead>
							  <tbody>
							    <tr v-for="(plan, i) in plans" :key="i" v-if="plans.length">
							      <th scope="row">{{plan.id}}</th>
							      <td>{{plan.planName}}</td>
							      <td>{{plan.created_at}}</td>
							      <td>
							      	<Button type="info" @click="showReadModal(plan, i)">Read</Button>
							      </td>
							      <td>
							      	<Button type="info" size="small" @click="showEditModal(plan, i)">Edit</Button>
							      	<Button type="error" size="small" @click="deletePlan(plan, i)" :loading="plan.isDeleting">Delete</Button>
							      </td>
							    </tr>				    
							  </tbody>
							</table>
				</div>
			</div>

			<Modal
			        v-model="addModal"
			        title="Add Plan Dialog box"
			        :mask-closable='false'
			        :closable="false"
			        >
			       <Form>
			       	<FormItem label="Paln Name">
			       		<input v-model="data.planName" placeholder="Enter a plan name" class="form-control" >
			       	</FormItem>
			       	<FormItem>
			       		<vue-editor v-model="data.planInfo"/>
			       	</FormItem>
			       </Form>
			        <div slot='footer'>
			        	<Button type="error" @click="addModal=false">Close</Button>
						<Button type="primary" @click="addPlan" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add plan'}}</Button>
			        </div>
			</Modal>
			<Modal
						v-model="editModal"
						title="Edit plan"
						:mask-closable="false"
						:closable="false"

						>
						<Form>
							<FormItem label="Plan Name">
								<Input v-model="editData.planName" placeholder="Edit plan name"/>
							</FormItem>

							<FormItem label="Write your Plan">
							<vue-editor v-model="editData.planInfo"/> 	
							</FormItem>
						</Form>

						<div slot="footer">
							<Button type="default" @click="editModal=false">Close</Button>
							<Button type="primary" @click="editPlan" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit plan'}}</Button>
						</div>

					</Modal>

					<Modal
								v-model="readModal"
								title="Read Plan"
								:mask-closable="false"
								:closable="false"
								:fullscreen = "true"
								>
								<h4 class="text-center">{{readData.planName}}</h4>
							   <div class="container" v-html="readData.planInfo">
							   	
							   </div>

								<div slot="footer">
									<Button type="error" @click="readModal=false">Exit</Button>
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
						planName:'',
						planInfo:''
					},
					readModal:false,
					addModal:false,
					isAdding:false,
					plans:[],
					editModal : false,
					editData : {
						planName: '',
						planInfo:''
					},
					readData : {
						planName: '',
						planInfo:''
				},
				    index : -1,  
				}
			},		
			methods : {
				async addPlan(){
					if (this.data.planName.trim()=='') {
						return this.e('Plan Name is required')
					}
					if (this.data.planInfo.trim()=='') {
						return this.e('Design your Plan')
					}
					const res = await this.callApi('post','/app/create_plan', this.data)
					if (res.status===201) {	
						this.plans.unshift(res.data);
						this.s('Plan Added successfully')
						this.addModal=false
						this.data.planName=''
						this.data.planInfo=''

					}else{
						if(res.status==422){
						if(res.data.errors.planName){
							this.e(res.data.errors.planName[0])
						}
						if(res.data.errors.planInfo){
							this.e(res.data.errors.planInfo[0])
						}
						
					}else{
						this.swr()
					}
					}
				},

				async editPlan(){
				if(this.editData.planName.trim()=='') return this.e('Plan name is required')
					if(this.editData.planInfo.trim()=='') return this.e('Plan name is required')
				const res = await this.callApi('post', '/app/edit_plan', this.editData)
				if(res.status===200){
					this.plans[this.index].planName = this.editData.planName
					this.plans[this.index].planInfo = this.editData.planInfo
					this.s('Plan has been edited successfully!')
					this.editModal = false
					
				}else{
					if(res.status==422){
						if(res.data.errors.planName){
							this.e(res.data.errors.planName[0])
						}

						if(res.data.errors.planInfo){
							this.e(res.data.errors.planInfo[0])
						}
						
					}else{
						this.swr()
					}
					
				}

			},
			showEditModal(plan, index){
				let obj = {
					id : plan.id, 
					planName : plan.planName,
					planInfo: plan.planInfo
				}
				this.editData = obj
				this.editModal = true
				this.index = index
			},
			showReadModal(plan, index){
				let obj = {
					id : plan.id, 
					planName : plan.planName,
					planInfo: plan.planInfo
				}
				this.readData = obj
				this.readModal = true
				this.index = index
			},
			async deletePlan(plan, i)
			{
				if(!confirm('Are you sure you want to delete this plan?')) return
					 this.$set(plan, 'isDeleting', true)
					const res = await this.callApi('delete', '/app/deletePlan', plan)
					if (res.status==200) {
						this.plans.splice(i,1)
						this.s('Plan Deleted successfully')
					}
					else{
						this.e('Something Went Wrong')
					}
			}			
				
			},
			async created(){
					const res = await this.callApi('get','/app/fetch_plans')
					if (res.status===200) {
						this.s('Load complete')
						this.plans = res.data
					}else{
						this.swr()
					}

				}
		}
</script>