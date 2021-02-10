<template>
	<div>
		 <Modal
			 	v-model="participateModal"
			 	title="Review Responses Track Modal"
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
			 		           		:title="review.username + '   ' + review.created_at"
			 		            	:description="review.review_response" 
			 		            />
			 		        </ListItem>
			 				 </List>
			 				 <div v-else>
			 				 	<h5>Waiting for the first Response</h5>
			 				 </div>
			 				</div>
			 	</Card>
			 	
			 	<div slot="footer">
			 		<Button type="error" @click="participateModal=false">Exit</Button>
			 	</div>
		 	</Modal>
		<Card>
			<p slot="title">This are questions you have asked reviews for</p>
					<div class="list_scrollable">
						<List border size="small" v-for="(question, q) in questions" :key="q" v-if="questions.length">
					    <ListItem class="mb-1">
			            	<ListItemMeta  
			           		:title="question.answers_count+ ' Responses'"
			            	:description="question.review_body" 
			            />
			            <template slot="action">
			                <Button type="primary"  @click="showParticipateModal(question, q)">
			                View Responses
			                <!--  -->
			            </Button>            
			             </template>
			        </ListItem>
					 </List>
					 <div v-else>
					 	<h5>Nothing to Show Here Currently</h5>
					 </div>
					</div>
					 
		</Card>
	</div>
</template>
<script>
	export default{
		data(){
			return {
				questions:[],
				 participateData:{
				 	id:'',
				 	question:'',
					owner:''
				 },
				 participateModal:false,
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
				this.fetch_reviews(obj)
				this.participateModal = true
				this.index = index
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
			const res = await this.callApi('get','/teacher/fetch_review_questions');
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
		max-height: 570px;
	    overflow-y: scroll;
	}
	.modal_list_scrollable{
		max-height: 570px;
	    overflow-y: scroll;
	}
	::-webkit-scrollbar {
	    display: none;
	}
</style>