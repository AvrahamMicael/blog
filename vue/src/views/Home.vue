<template>
    <div>
        <div>
            <h2>
                Title
            </h2>
            <p>
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Incidunt, molestias. Esse aut fuga expedita? Molestiae eveniet fugiat commodi sequi nisi aperiam ducimus dolores, ex reiciendis iusto dolor, maxime distinctio numquam.
            </p>
            <hr>
        </div>
        <section
            v-for="(post, i) in posts"
            :key="i"
            class="text-decoration-none d-block link-dark"
        >
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
            <p>
                {{ post.body }}
                <router-link
                    :to="{ name: 'Post', params: { id: post.id } }"
                >
                    Read more...
                </router-link>
            </p>
            <hr v-if="posts[++i]">
        </section>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import { admin } from '../constants/Roles.js';

export default {
    data() {
        return {
            admin
        };
    },
    methods: {
        deletePost(post) {
            if(confirm("Are you sure you want to delete this post? Operation can't be undone!"))
            {
                // delte post
            }
        }
    },
    computed: {
        ...mapState(['posts', 'user'])
    },
    mounted() {
        this.posts.forEach(post => {
            post.body = post.body.split(' ', 30).join(' ') + '...';
            post.created_at = post.created_at.split(' ', 1).join();
        });
    },
}
</script>
