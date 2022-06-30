import { createRouter, createWebHistory } from 'vue-router';
import { user, admin } from '../constants/Roles.js';

import Home from '../views/Home.vue';
import NewPost from '../views/NewPost.vue';
import Post from '../views/Post.vue';

import store from '../store';
import DefaultLayout from '../components/DefaultLayout.vue'

const routes = [
   {
      path: '/',
      component: DefaultLayout,
      children: [
         { name: 'Home', path: '/', component: Home },
         { name: 'Post', path: '/post/:id', component: Post }
      ]
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
   && store.state.user.data.role != admin)
   {
      next({name: 'Home'});  /// -change later
   }
   next();
});

export default router;