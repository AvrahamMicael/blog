<script>
import {mapState} from 'vuex'
import Header from './components/Header.vue'

export default {
  computed: {
    ...mapState(['user'])
  },
  components: {
    Header
  }
}
</script>

<template>
  <div>
    <Header/>
    <router-view/>
  </div>
</template>
