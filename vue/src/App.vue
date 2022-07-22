<script>
import { mapState } from 'vuex';
import Header from './components/Header.vue';
import AuthPopup from './components/AuthPopup.vue';
import Loader from './components/Loader.vue';

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
    this.$store.dispatch('getSubscribersNumber');
    this.setTitle()
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
