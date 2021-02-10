<template>
	<div>
		<div class="card">
			<div class="card-body">
				<Form>
					<FormItem label="Review Subject">
						<Input v-model="data.review_body" type="textarea" :autosize="{minRows: 5,maxRows: 15}" placeholder="What do you want to talk about? Ask a question.">
							
						</Input>
					</FormItem>
					<FormItem>
					            <Button type="primary" :loading='isActive' :disabled="isActive" @click="submitReview">Submit</Button>
					            
					        </FormItem>
				</Form>
			</div>
		</div>
	</div>
</template>
<script>
	export default{

		data(){
			return {
				data:{
					review_body:'',
				},
				isActive:false,
			}
		},
		methods:{
			async submitReview(){
				this.isActive= true
				if (this.data.review_body.trim()=='') {
					this.isActive = false
					return this.e('Opinion or Review Request can`t be blank')
				}
				const resp = await this.callApi('post','/teacher/post_opinion_request', this.data)
				if (resp.status == 201) {
					this.s('Opinion/Review Request added. Click on my review to track it')
					this.isActive = false
					return this.data.review_body=''					
				}else{
					if (resp.status == 422) {
						if(res.data.errors.review_body){
							this.isActive = false
							return this.e(res.data.errors.className[0])
						}
					}else{
						this.isActive = false
						return this.e('Error Occured, Please try again')
					}
				}
			}
		}
	}
</script>