<template>
	<div>
		<Row justify="center">
			<Col span=18>
				<Card>
					<p slot="title">Hello  Please complete your profile to help us know you</p>
					<Form>
						<FormItem prop="Account" label="Account Type">
						   <Select v-model="userProfileData.account_type" style="width:200px">
						        <Option value="Lecturer" label="Lecturere">
						            <span>Lecturer</span>
						            <span style="float:right;color:#ccc">Lecturer</span>
						        </Option>

						        <Option value="Student" label="Student">
						            <span>Student</span>
						            <span style="float:right;color:#ccc">Student</span>
						        </Option>
						      
						    </Select>
						
						    
						</FormItem>
						<FormItem prop="gender" label="Gender">						    
						        <RadioGroup v-model="userProfileData.gender" vertical>
						            <Radio label="Male">
						            	<span>Male</span>
						            </Radio>
						            <Radio label="Female">
						                <span>Female</span>
						            </Radio>
						            <Radio label="Other">
						                <span>Other</span>
						            </Radio>
						        </RadioGroup>
						    

						</FormItem>

						<FormItem prop="date_of_birth" label="Date of birth">
						    <Input type="date" v-model="userProfileData.date_of_birth">
						        <Icon type="md-calendar" slot="prepend"></Icon>
						    </Input>
						</FormItem>	
						<FormItem>
						    <Button type="primary" @click="completeRegistration">Submit Data</Button>
						</FormItem>

					</Form>
				</Card>
			</Col>
		</Row>
	</div>
</template>
<script>
	export default {
		data(){
			return {
				userProfileData:{
					date_of_birth:'',
					gender:'',
					account_type:''
					
				}				
			}
		},
		props:['user'],
		methods:{
			async completeRegistration(){				
				if (this.userProfileData.date_of_birth.trim()=='') {
					return this.e('Please a date of birth')
				}

				if (this.userProfileData.gender.trim()=='') {
					return this.e('Please select gender')
				}

				if (this.userProfileData.account_type.trim()=='') {
					return this.e('Select your preferred account type')
				}				
				
				const res = await this.callApi('post', '/app/user/profile/update', this.userProfileData)
				if (res.status==200) {	
				this.userProfileData = '';				
				this.s('Profile Updated Successfully')
				window.location='/home'					
				}else{
					if (res.status==422) {
						if (res.data.errors.date_of_birth) {
							this.e(res.data.errors.date_of_birth[0])
						}
						if (res.data.errors.gender) {
							this.e(res.data.errors.gender[0])
						}
						if (res.data.errors.account_type) {
							this.e(res.data.errors.account_type[0])
						}
						
					}
					else{
						this.swr('Opps, Please refresh the page')
					}
				}
			}
		}

	}
</script>