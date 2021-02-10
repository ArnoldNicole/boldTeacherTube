<template>
	<div class="card">
		<div class="card-header bg-success text-white font-weight-bold text-center">
			<p>Welcome to Bold Teacher Tube</p>
		</div>
		<div class="card-body">

			<p>Bold Teacher tube is a web platform where learners get to interact with their teachers and talk beyond class room matters</p>
			<p>Everyone`s idea counts</p>
			<p>Teachers on the platform can share resources and find new innovative ways to share materials with students</p>
			<p>Users can brainstorm together to create a database of resources and best practises that benefit the institution</p>
			<p>Everyone can give feedback and offer asssistance in further developing currriculum</p>
			<p>Faculty can use peer evaluations to help with grading practices and receive feedback on their teaching style</p>
			<p>You can write your notes here, save them, review them, and update them anytime, anywhere</p>
			<p>With crowd sourcing we`ll improve the way education is conducted by teachers and received by learners</p>
			<p>Teachers can manage their lesson plans on the platform and share their notes to the learners</p>
			<p>All this is without fear of intimidation as their app has no admins.</p>
			<br>
			<p><span class="float-right text-success font-weight-bold">Enjoy the platform</span></p>
		</div>
	</div>
</template>	
<script>
	export default{
		data(){
			return {
				user:[],
			}
		},
		async created(){
			const resp = await this.callApi('get','/user');
			if (resp.status == 200) return this.user = resp.data
		}
	}
</script>
