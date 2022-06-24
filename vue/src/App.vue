<script>
import {mapState} from 'vuex'
import Header from './components/Header.vue'
import AuthPopup from './components/AuthPopup.vue'

export default {
  computed: {
    ...mapState(['user', 'popup'])
  },
  components: {
    Header,
    AuthPopup
  },
}
</script>

<template>
  <div>
    <Header/>
    <router-view/>
    <AuthPopup v-if="popup"/>
  </div>
</template>

<style lang="css">
  .text-decoration-none
  {
    text-decoration: none;
  }
</style>
