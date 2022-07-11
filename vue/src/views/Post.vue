<template>
    <Post v-if="post" v-model="post"/>
    <SecondaryLoader v-else/>
</template>

<script>
import Post from '../components/Post.vue';
import SecondaryLoader from '../components/SecondaryLoader.vue';
import { checkIfIdIsNumberAndTryToGetThePost } from '../common-functions.js';

export default {
    data() {
        return {
            post: null
        };
    },
    components: {
        Post,
        SecondaryLoader
    },
    methods: {
        async getAndSetPost() {
            this.post = await checkIfIdIsNumberAndTryToGetThePost(this.$route.params.id);
        }
    },
    created() {
        this.getAndSetPost();
    },
}
</script>
