<template>
    <article class="text-decoration-none d-block link-dark">
        <div class="row">
            <h2 class="col-8">{{ post.title }}</h2>
            <small class="col-4 text-end mb-auto">
                {{ post.created_at }}
                <span v-if="user.data.role == admin">
                    &nbsp;
                    <router-link
                        :to="{ name: 'EditPost', params: { slug: post.slug } }"
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
                :to="{ name: 'Post', params: { slug: post.slug } }"
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
    </article>
</template>

<script>
import { mapState } from 'vuex';
import { admin } from '../constants/Roles.js';
import PostContent from './PostContent.vue';
import { formatDate } from '../common-functions.js';

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
            confirm('Are you sure? This action cannot be undone.')
            {
                this.$store
                    .dispatch('deletePost', post)
                    .then(() => {
                        if(this.$route.name == 'Post') this.$router.push({
                            name: 'Home'
                        });
                    })
                    .catch(() => alert("The post couldn't be deleted."));
            }
        },
    },
    computed: {
        ...mapState(['user']),
    },
    mounted() {
        this.post.created_at = formatDate(this.post.created_at);
        if(this.$route.name == 'Home')
        {
            this.post.body[0].value = this.post.body[0].value.split(' ', 30).join(' ') + '...';
        }
    }
};
</script>