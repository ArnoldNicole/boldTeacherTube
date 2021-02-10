<template>
	<div>
		 <Modal
			 	v-model="participateModal"
			 	title="Participate in CrowdSourcing"
			 	:mask-closable="false"
			 	:closable="false"
			 	:fullscreen="true"
			 	>
			 	<Card>
			 		<p slot="title" class="text-center font-weight-bold text-info">{{participateData.question}} 
			 		<span class="float-right text-success">{{participateData.owner}}</span></p>
			 		<!-- Show all comments -->
			 				<div class="modal_list_scrollable">
			 					<List  size="small" v-for="(review, r) in reviews" :key="r" v-if="reviews.length">
			 				    <ListItem class="mb-1" >
			 		            	<ListItemMeta  
			 		           		:title="review.username +' '+ review.created_at"
			 		            	:description="review.review_response" 
			 		            />
			 		        </ListItem>
			 				 </List>
			 				</div>
			 				 <hr>
			 		<Form>
			 			<FormItem label="Your Suggestion Counts">
			 				<Input v-model="data.myresponse" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Enter your Suggestion.">			 					
			 				</Input>
			 			</FormItem>
			 			<FormItem>
			 			     <Button type="primary" :loading='isActive' :disabled="isActive" @click="sendSuggestion">Submit Response</Button>
			 			            
			 			 </FormItem>
			 		</Form>

			 	</Card>
			 	
			 	<div slot="footer">
			 		<Button type="error" @click="participateModal=false">Exit</Button>
			 	</div>
		 	</Modal>

		<Card>
			<p slot="title">Click on Participate button to view Thread or participate</p>
					<div class="list_scrollable">
						<List border size="small" v-for="(question, q) in questions" :key="q" v-if="questions.length">
					    <ListItem class="mb-1" >
			            	<ListItemMeta  
			           		:title="'Started by '+ question.owner"
			            	:description="question.review_body" 
			            />
			            <template slot="action">
			                <Button type="primary"  @click="showParticipateModal(question, q)">
			                Participate
			                <!--  -->
			            </Button>            
			             </template>
			        </ListItem>
					 </List>
					</div>
		</Card>
	</div>
</template>
<script>
	export default{
		data(){
			return {
				data:{
					myresponse:'',
					id:'',
				},
				isActive:false,
				questions:[],
				index:-1,
				 participateModal:false,
				 participateData:{
				 	id:'',
				 	question:'',
					owner:''
				 },
				 reviews:[],
			}
		},

		methods:{
			showParticipateModal(question, index){
				this.reviews=''
				let obj = {
					id : question.id, 
					question : question.review_body,
					owner:question.owner,
				}
				this.participateData = obj
				this.data.id = question.id
				this.data.myresponse = ''
				this.fetch_reviews(obj)
				this.participateModal = true
				this.index = index
			},
			async sendSuggestion(){
				this.isActive=true
				if (this.data.myresponse.trim()=='') {
					this.isActive = false
					return this.e('Please Enter a suggestion')
				}

				const send_suggestion = await this.callApi('post','/reviews/add', this.data)
				if (send_suggestion.status == 201) {
					this.reviews.unshift(send_suggestion.data.data)
					this.data = ''
					this.isActive = false
					return this.s('ReviewSaved')
				}else{
					if(send_suggestion.status == 422) {
						if(send_suggestion.data.errors.myresponse){
							this.isActive = false
							return this.e(send_suggestion.data.errors.myresponse[0])
						}
					}else{
						this.isActive = false
						return this.e('Unknown Error Occured')
					}
				}
				
			},
			async fetch_reviews(question){
				const review_res = await this.callApi('post','/questions/fetch_reviews', question)
				if (review_res.status == 200) {
					return this.reviews = review_res.data.data
				}else{
					return this.e('Error fetching responses')
				}
			}

		},
		async created(){
			const res = await this.callApi('get','/responder/fetch_review_questions');
			if (res.status == 200) {				
				return this.questions = res.data.data
			}else{
				return this.e('Error Occurred, please try again')
			}
		}
	}
</script>

<style>
	.list_scrollable{
		max-height: 400px;
	    overflow-y: scroll;
	}
	.modal_list_scrollable{
		max-height: 450px;
	    overflow-y: scroll;
	}
	::-webkit-scrollbar {
	    display: none;
	}
</style>