import Router from 'vue-router'
import Vue from 'vue'
Vue.use(Router)
//import ViewUi from 'view-design'
//Vue.use(ViewUi)

// router.beforeEach((to, from, next)=>{
// 	ViewUi.LoadingBar.start();
// 	next();
// })
// router.afterEach(route => {
// 	ViewUi.LoadingBar.finish();
// })
import Dashboard from './components/pages/Home'
import Note from  './components/pages/Notes'
import Lessons  from  './components/pages/lessonPlan'
import Classes from './components/pages/Class'
import AvailableClasses from './components/pages/AvailableClasses'
import ChatComponent from './components/pages/ChatComponent'
import sharedNotes from './components/pages/sharedNotes'
import MyClasses from './components/pages/MyClasses'
import ReviewWriter from './components/pages/AskForReviews'
import Reviews  from './components/pages/myReviewQuestions.vue'
import Response from './components/pages/Responses'


const routes = [
	{
		path: '/home',
		component: Dashboard,
	},
	{
		path: '/notes',
		component: Note,
	},
	{
 		path: '/lessonPlan/',
 		component: Lessons,
	},
	{
		path: '/classes',
		component: Classes,
	},

	{
		path: '/available_classes/',
		component: AvailableClasses,
	},
	{
		path: '/chat/',
		component: ChatComponent,
	},
	{
		path: '/sharedNotes/',
		component: sharedNotes,
	},
	{
		path: '/my_classes/',
		component: MyClasses,
	},
	{
		path: '/ask_reviews/',
		component: ReviewWriter,
	}, 
	{
		path: '/cloud_source/',
		component: Reviews,
	},
	{
		path:'/responses/',
		component: Response,
	}
	
	
	
]
export default new Router({
	mode: 'history',
	routes
})