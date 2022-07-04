<template>
    <section class="text-decoration-none d-block link-dark">
        <div class="row">
            <h2 class="col-8">{{ post.title }}</h2>
            <small class="col-4 text-end my-auto">
                {{ post.created_at }}
                <button
                    v-if="user.data.role == admin"
                    @click="deletePost(post)"
                    class="btn btn-sm btn-outline-danger"
                >
                    <i class="fa-solid fa-x"/>
                </button>
            </small>
        </div>
        <p v-if="$route.name == 'Home'">
            {{ post.body[0].value }}
            <router-link
                :to="{ name: 'Post', params: { id: post.id } }"
            >
                Read more...
            </router-link>
        </p>
        <PostContent
            v-else
            v-for="content in post.body"
            :key="content.value"
            :content="content"
        />
        <slot></slot>
    </section>
</template>

<script>
import { mapState } from 'vuex';
import { admin } from '../constants/Roles.js';
import PostContent from './PostContent.vue';

export default {
    data() {
        return {
            admin,
            post: JSON.parse(JSON.stringify(this.modelValue))
        };
    },
    components: {
        PostContent
    },
    props: ['modelValue'],
    methods: {
        deletePost(post) {
            //delete
        }
    },
    computed: {
        ...mapState(['user'])
    },
    mounted() {
        const date = new Date(this.post.created_at);
        this.post.created_at = `${date.getMonth() + 1}/${date.getDate()}/${date.getFullYear()}`;
        // this.post.created_at = this.post.created_at.split(' ', 1).join();
        if(this.$route.name == 'Home')
        {
            this.post.body[0].value = this.post.body[0].value.split(' ', 30).join(' ') + '...';
        }
    }
};
</script>