<template>
	<div>
		<Row :gutter="16" class="mt-2">
			<Col span="8" :md="8">
				<Card>
					<p slot="title">Contact</p>
					<div class="contact_list">
						<List v-for="(contact, c) in contacts" :key="c" :border="true" class="mb-1">
							<span @click="chat(contact)">
						        <ListItem @click="chat(contact)">
						            <ListItemMeta avatar="https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar" :title="contact.name" />
						        </ListItem>	
						    </span>					   
						  </List>						  
					</div>	
					
				</Card>
			</Col>
			<Col span="16" :md="16">
				<Card>
					<p slot="title">Conversation Here <span class="float-right">{{data.username}}</span></p>
					<div class="auto">
						<ul class="chat-list" v-for="(message, m) in conversation" :key="m">
							<li class="in" v-if="message.from_user==data.user_id">
							     <div class="chat-body">
							        <div class="chat-message">	
							         	<p>{{message.content}}<br><small>{{message.created_at}}</small>
							         	</p>
							        </div>
							     </div>
							 </li>
							 <li class="out" v-else>
							    <div class="chat-body">
							        <div class="chat-message">
							        	<p>{{message.content}}<br><small>{{message.created_at}}</small>
							        	</p>
							        </div>
							    </div>
							</li>

						</ul>
						<Form @submit.native.prevent inline >
							<FormItem prop="message" class="w-75">
							    <Input type="text" v-model='data.message'  placeholder="Enter your message">
							        <Icon type="md-mail" slot="prepend"></Icon>	
							    </Input>
							</FormItem>
							<FormItem>
								<Button @click="sendMessage" :disabled="isActive" type="default"> <Icon type="ios-send" color="aqua" size="30"></Icon></Button>
							</FormItem>
						</Form>
					</div>					
				</Card>
			</Col>
		</Row>
	</div>
</template>

<script>
	export default{
		data(){
			return {
				user:[],
				isActive:false,
				data:{
					username:'',
					message:'',					
					user_id:''
				},
				conversation:[],
				contacts:[],
			}
		},
		methods:{
			async sendMessage(){				
				this.isActive=true
				if (this.data.message.trim()=='') {
					this.isActive=false
					return this.e('Please type your message')
					
				}
				if (this.data.user_id.trim()=='') {
					this.isActive=false
					return this.e('Invalid Contact Selected')					
				}
				
				const res =  await this.callApi('post', '/user/sendMessage', this.data)
				if (res.status==200) {					
					this.s('Message sent')
					this.conversation.push(res.data);
					this.data.message=''
					return this.isActive=false					

				}else{
					if(res.status==422){
						if (res.data.errors.message) {
							return this.e(res.data.errors.message[0])
						}
					}else{
							return this.e(res.data.message)
						}
					}
				
			},
			async chat(contact){
				//load conversation
				this.data.username = contact.name				
				this.data.user_id=contact.id
				const conversation_data  = await this.callApi('get', '/user/fetchMessages/'+contact.id)
				if (conversation_data.status == 200) {
					return this.conversation = conversation_data.data					
				}
				else{
					if (conversation_data.status == 404) {
						return this.e(conversation_data.data.message)
					}else{
						return this.e('Unknown Error')
					}
				}
				this.listen();
			},
			async listen(){
				 Echo.channel('chatroom.'+this.data.user_id+'.'+this.user.id)				    
				     .listen('NewMessage', (message) => {				     
				     	this.conversation.push(message)				     
				     });		    						

							      
			    }
		},
		async created(){
			const user = await this.callApi('get','/user')
			this.user = user.data
			const fetchContacts = await this.callApi('get','/user/fetchContacts')
			if (fetchContacts.status == 200) {
				return this.contacts = fetchContacts.data
			}
			else{
				return this.e('Trouble loading contacts')
			}
		}
	}
</script>
<style>
	body{
	    background:#eee;    
	}
	.chat-list {
	    padding: 0;
	    font-size: .8rem;
	}

	.chat-list li {
	    margin-bottom: 10px;
	    overflow: auto;
	    color: #ffffff;
	}

	.chat-list .chat-img {
	    float: left;
	    width: 48px;
	}

	.chat-list .chat-img img {
	    -webkit-border-radius: 50px;
	    -moz-border-radius: 50px;
	    border-radius: 50px;
	    width: 100%;
	}

	.chat-list .chat-message {
	    -webkit-border-radius: 50px;
	    -moz-border-radius: 50px;
	    border-radius: 50px;
	    background: #5a99ee;
	    display: inline-block;
	    padding: 10px 20px;
	    position: relative;
	}

	.chat-list .chat-message:before {
	    content: "";
	    position: absolute;
	    top: 15px;
	    width: 0;
	    height: 0;
	}

	.chat-list .chat-message h5 {
	    margin: 0 0 5px 0;
	    font-weight: 600;
	    line-height: 100%;
	    font-size: .9rem;
	}

	.chat-list .chat-message p {
	    line-height: 18px;
	    margin: 0;
	    padding: 0;
	}

	.chat-list .chat-body {
	    margin-left: 20px;
	    float: left;
	    width: 70%;
	}

	.chat-list .in .chat-message:before {
	    left: -12px;
	    border-bottom: 20px solid transparent;
	    border-right: 20px solid #5a99ee;
	}

	.chat-list .out .chat-img {
	    float: right;
	}

	.chat-list .out .chat-body {
	    float: right;
	    margin-right: 20px;
	    text-align: right;
	}

	.chat-list .out .chat-message {
	    background: #fc6d4c;
	}

	.chat-list .out .chat-message:before {
	    right: -12px;
	    border-bottom: 20px solid transparent;
	    border-left: 20px solid #fc6d4c;
	}

	.card .card-header:first-child {
	    -webkit-border-radius: 0.3rem 0.3rem 0 0;
	    -moz-border-radius: 0.3rem 0.3rem 0 0;
	    border-radius: 0.3rem 0.3rem 0 0;
	}
	.card .card-header {
	    background: #17202b;
	    border: 0;
	    font-size: 1rem;
	    padding: .65rem 1rem;
	    position: relative;
	    font-weight: 600;
	    color: #ffffff;
	}

	.content{
	    margin-top:40px;    
	}
	
	.auto{	      	
		height: 70%;
		overflow-y: scroll;
	    }
	      ::-webkit-scrollbar {
	          display: none;
	      }
	      .contact_list{
	      	max-height: 400px;
	      	overflow-y: scroll;
	      }
	                 
</style>
