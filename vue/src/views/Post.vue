<template>
    <Post v-model="post"/>
</template>

<script>
import Post from '../components/Post.vue';

export default {
    components: {
        Post
    },
    computed: {
        post() {
            return this.$store.state.posts.find(post => {
                return post.id == parseInt(this.$route.params.id);
            });
        }
    }
}
</script>
