<template>
    <Post v-if="post" v-model="post"/>
    <SecondaryLoader v-else/>
</template>

<script>
import { mapState } from 'vuex';
import Post from '../components/Post.vue';
import SecondaryLoader from '../components/SecondaryLoader.vue';

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
            const id_post = parseInt(this.$route.params.id);
            this.post = this.posts.data.find(post => post.id == id_post)
                ?? this.posts.showedPosts.find(post => id_post == post.id)
                ?? await this.$store.dispatch('showPost', id_post)
                ?? this.$router.push({
                    name: 'NotFound'
                });
        }
    },
    computed: {
        ...mapState(['posts'])
    },
    created() {
        this.getAndSetPost();
    },
}
</script>
