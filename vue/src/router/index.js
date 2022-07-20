import { createRouter, createWebHistory } from 'vue-router';
import { admin } from '../constants/Roles.js';

//layouts
import DefaultLayout from '../components/DefaultLayout.vue'
import OneColumnLayout from '../components/OneColumnLayout.vue'
import UserLayout from '../components/UserLayout.vue'

//views
import Home from '../views/Home.vue';
import ConfigurePost from '../views/ConfigurePost.vue';
import Post from '../views/Post.vue';
import NotFound from '../views/NotFound.vue';
import Unsubscribe from '../views/Unsubscribe.vue';
import UserCommentsReplies from '../views/UserCommentsReplies.vue';

import store from '../store';

const routes = [
   {
      path: '/',
      redirect: { name: 'Home' },
      component: DefaultLayout,
      children: [
         { name: 'Home', path: '/', component: Home },
         { name: 'Post', path: '/post/:slug', component: Post }
      ]
   },
   {
      path: '/',
      component: OneColumnLayout,
      meta: { isAdmin: true },
      children: [
         { name: 'NewPost', path: '/post/create', component: ConfigurePost},
         { name: 'EditPost', path: '/post/:slug/edit', component: ConfigurePost},
         { name: 'NotFound', path: '/error/404', component: NotFound, meta: { isAdmin: false } },
         { name: 'Unsubscribe', path: '/subscriber/delete/:id/:token', component: Unsubscribe, meta: { isAdmin: false } },
      ]
   },
   {
      path: '/user',
      component: UserLayout,
      meta: { isLogged: true },
      children: [
         { name: 'UserComments', path: 'comments', component: UserCommentsReplies },
         { name: 'UserReplies', path: 'replies', component: UserCommentsReplies },
         // { name: 'Settings', path: 'settings', component: Settings },
      ]
   },
   {
      path: '/:catchAll(.*)*',
      redirect: { name: 'NotFound' }
   },
];

const router = createRouter({
   history: createWebHistory(),
   routes
});

router.beforeEach((to, from, next) => {
   if(to.meta.isAdmin
   && store.state.user.data.role != admin
   || (
      to.meta.isLogged
      && !store.state.user.token
   ))
   {
      next({name: 'Home'});
   }
   next();
});

export default router;