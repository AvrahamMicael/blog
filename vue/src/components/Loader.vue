<template>
    <div v-if="loader" class="loading-box">
        <div class="lds-roller">
            <div/>
            <div/>
            <div/>
            <div/>
            <div/>
            <div/>
            <div/>
            <div/>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
  computed: {
    ...mapState(['loader'])
  }
};
</script>
