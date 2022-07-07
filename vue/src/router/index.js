import { createRouter, createWebHistory } from 'vue-router';
import { user, admin } from '../constants/Roles.js';

import Home from '../views/Home.vue';
import NewPost from '../views/NewPost.vue';
import Post from '../views/Post.vue';
import NotFound from '../views/NotFound.vue';

import store from '../store';
import DefaultLayout from '../components/DefaultLayout.vue'
import OneColumnLayout from '../components/OneColumnLayout.vue'

const routes = [
   {
      path: '/',
      redirect: { name: 'Home' },
      component: DefaultLayout,
      children: [
         { name: 'Home', path: '/', component: Home },
         { path: '/post/', redirect: { name: 'NotFound' } },
         { name: 'Post', path: '/post/:id', component: Post }
      ]
   },
   {
      path: '/',
      component: OneColumnLayout,
      meta: {isAdmin: true},
      children: [
         { name: 'NewPost', path: '/post/create', component: NewPost},
         { name: 'NotFound', path: '/error/404', component: NotFound }
      ]
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