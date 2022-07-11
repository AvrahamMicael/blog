<template>
    <section class="text-decoration-none d-block link-dark">
        <div class="row">
            <h2 class="col-8">{{ post.title }}</h2>
            <small class="col-4 text-end my-auto">
                {{ post.created_at }}
                <span v-if="user.data.role == admin">
                    &nbsp;
                    <router-link
                        :to="{ name: 'EditPost', params: { id: post.id } }"
                        class="btn btn-sm btn-outline-primary"
                    >
                        <i class="fas fa-edit"/>
                    </router-link>
                    &nbsp;
                    <button
                        @click="deletePost(post)"
                        class="btn btn-sm btn-outline-danger"
                    >
                        <i class="fa-solid fa-x"/>
                    </button>
                </span>
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
            this.$store
                .dispatch('deletePost', post)
                .then(() => {
                    if(this.$route.name == 'Post') this.$router.push({
                        name: 'Home'
                    });
                });
        },
        formatDate() {
            const date = new Date(this.post.created_at);
            this.post.created_at = `${date.getMonth() + 1}/${date.getDate()}/${date.getFullYear()}`;
        }
    },
    computed: {
        ...mapState(['user']),
    },
    mounted() {
        this.formatDate();
        if(this.$route.name == 'Home')
        {
            this.post.body[0].value = this.post.body[0].value.split(' ', 30).join(' ') + '...';
        }
    }
};
</script>