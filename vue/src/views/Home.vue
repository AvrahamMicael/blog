<template>
    <div class="col-md-8 mb-4">
        <main class="card">
            <div class="card-body">
                <div v-if="posts.data.length">
                    <Post
                        v-for="(post, index) in posts.data"
                        :key="post.body[0].value"
                        v-model="posts.data[index]"
                    >
                        <hr v-if="posts.data[index + 1]">
                    </Post>
                    <div v-if="posts.links.length > 3" class="d-flex justify-content-center">
                        <div class="btn-group" role="group">
                            <a
                                v-for="link in posts.links" :key="link.label"
                                :disabled="!link.url"
                                v-html="link.label"
                                @click="getForPage(link)"
                                class="btn btn-outline-dark"
                                :class="[
                                    !link.url || link.active
                                        ? 'disabled'
                                        : null
                                ]"
                            />
                        </div>
                    </div>
                </div>
                <SecondaryLoader v-else-if="!error"/>
                <DisplayError v-else :error="error"/>
            </div>
        </main>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import { admin } from '../constants/Roles.js';
import Post from '../components/Post.vue';
import SecondaryLoader from '../components/SecondaryLoader.vue';
import DisplayError from '../components/DisplayError.vue';

export default {
    data: () => ({
        admin,
        error: null
    }),
    components: {
        Post,
        SecondaryLoader,
        DisplayError
    },
    methods: {
        getForPage(link) {
            if(!link.url || link.active) 
                return;
            
            this.$store.dispatch('getHomePosts', link.url);
        },
    },
    computed: {
        ...mapState(['posts', 'user'])
    },
    async created() {
        this.error = await this.$store.dispatch('getHomePosts');
    },
}
</script>
