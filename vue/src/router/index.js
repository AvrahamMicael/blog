import { createRouter, createWebHistory } from 'vue-router';
import { guest, user, admin } from '../constants/Roles.js';
import Home from '../views/Home.vue';
import NewPost from '../views/NewPost.vue';
import store from '../store';

const routes = [
   {
      path: '/',
      name: 'Home',
      component: Home
   },
   {
      path: '/post/create',
      name: 'NewPost',
      component: NewPost,
      meta: {isAdmin: true}
   },
];

const router = createRouter({
   history: createWebHistory(),
   routes
});

router.beforeEach((to, from, next) => {
   if(to.meta.isAdmin
   && store.state.user.role != admin)
   {
      next({name: 'Home'});  /// -change later
   }
   next();
});

export default router;