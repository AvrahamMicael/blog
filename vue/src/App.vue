<script>
import { mapState } from 'vuex';
import Header from './components/Header.vue';
import AuthPopup from './components/AuthPopup.vue';
import Loader from './components/Loader.vue';
import store from './store';

export default {
  computed: {
    ...mapState(['user', 'popup'])
  },
  components: {
    Header,
    AuthPopup,
    Loader
  },
  methods: {
      setTitle() {
        document.title = import.meta.env.VITE_SITE_TITLE;
      },
  },
  created() {
    this.setTitle()
    store.dispatch('getSubscribersNumber');
    if(this.user.token)
      store.dispatch('getNotificationsQty');
  },
};
</script>

<template>
  <div>
    <Header/>
    <router-view/>
    <AuthPopup v-if="popup"/>
    <teleport to='body'>
      <Loader/>
    </teleport>
  </div>
</template>

<style lang="css">
  .text-decoration-none
  {
    text-decoration: none;
  }
</style>
