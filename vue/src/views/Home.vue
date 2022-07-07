<template>
    <div v-if="posts.data.length">
        <Post
            v-for="(post, index) in posts.data"
            :key="post.body[0].value"
            v-model="posts.data[index]"
        >
            <hr v-if="posts.data[index + 1]">
        </Post>
    </div>
    <SecondaryLoader v-else-if="!error"/>
    <DisplayError v-else :error="error"/>
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
    computed: {
        ...mapState(['posts', 'user'])
    },
    async created() {
        this.error = await this.$store.dispatch('getHomePosts');
    },
}
</script>
