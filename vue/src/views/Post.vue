<template>
    <div class="col-md-8 mb-4">
        <main class="card mb-4">
            <div class="card-body">
                <Post v-if="post" v-model="post"/>
                <SecondaryLoader v-else/>
            </div>
        </main>
        <div class="card">
            <div class="card-body">
                another card
            </div>
        </div>
    </div>
</template>

<script>
import Post from '../components/Post.vue';
import SecondaryLoader from '../components/SecondaryLoader.vue';
import { getPostBySlug } from '../common-functions.js';

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
            this.post = await getPostBySlug(this.$route.params.slug);
        }
    },
    created() {
        this.getAndSetPost();
    },
}
</script>
