<template>
    <div v-if="posts.data">
        <Post
            v-for="(post, index) in posts.data"
            :key="post.body[0].value"
            v-model="posts.data[index]"
        >
            <hr v-if="posts.data[index + 1]">
        </Post>
    </div>
    <div v-else>
        <!-- another loader -->
    </div>
</template>

<script>
import { mapState } from 'vuex';
import { admin } from '../constants/Roles.js';
import Post from '../components/Post.vue';

export default {
    data() {
        return {
            admin
        };
    },
    components: {
        Post
    },
    computed: {
        ...mapState(['posts', 'user'])
    },
    beforeCreate() {
        this.$store.dispatch('getHomePosts');
        const obj = { test: 1, test2: 2 };
    },
}
</script>
