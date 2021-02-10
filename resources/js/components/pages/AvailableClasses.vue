<template>
	<div>
		<div v-for="(room, i) in rooms" v-if="rooms.length">
			<Card class="mb-1 mt-2">
				<p slot="title">Class Name {{room.className}}<span class="float-right">Class Code {{room.classCode}}</span></p>
				<Button type="primary" @click="joinClass(room)"><Icon type="md-add"></Icon> Join Class</Button>
			</Card>
		</div>		
	</div>
</template>
<script>
export default{
	data(){
		return {
			rooms:[],
			// status: false,
		}
	},
	methods:{
		joinClass(room){
		axios.post('/joinClass/' + room.id)
		.then(response=> {
		// this.status = ! this.status;		
		})
		.catch(error => {
		    if(error.response.status==401){
		    window.location = '/login';
		    }
		})
		this.s('joined class successfully')
		this.refreshClasses();		
		},
		async refreshClasses(){
			const res = await this.callApi('get','/student/get/rooms')
			if (res.status == 200) {
				return this.rooms = res.data
			}else{
				if (res.status == 401) {
					return window.location ='/available_classes/'
				}else{
					return this.swr()
				}
			}
		}
	},
	// computed: {
	// buttonText(){
	// return (this.status) ? 'Leave' : 'Join';
	// }
	// },
	async created(){
		return this.refreshClasses()		
	}
}
</script>