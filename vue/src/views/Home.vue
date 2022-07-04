<template>
    <div>
        <Post
            v-for="(post, index) in posts"
            :key="post.body[0].value"
            v-model="posts[index]"
        >
            <hr v-if="posts[++index]">
        </Post>
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
    }
}
</script>
