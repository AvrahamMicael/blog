<template>
    <div v-if="!comments.loading || comments.data.length">
        <section v-for="(comment, index) in comments.data" :key="comment.id">
            <h5 class="mb-0">
                Post:
                <span class="fst-italic text-muted fw-light">
                    {{ comment.title }}
                </span>
            </h5>

            <router-link :to="{ name: 'Post', params: { slug: comment.slug } }" class="d-block text-decoration-none text-dark">
                <Comment :comment="comment"/>
            </router-link>

            <hr v-if="comments.data[index + 1]">
        </section>
        <Alert
            v-if="comments.error"
            :content="comments.error"
            @toggle="$router.go()"
            class="mt-3"
        />
        <section v-else-if="!comments.data.length">
            <h5 class="mb-0 text-muted text-center">You don't have any comment/reply</h5>
        </section>
        <div v-if="comments.moreLink" class="text-center">
            <a
                v-if="!comments.loading"
                @click="getComments"
                href="javascript:;"
                class="link-dark"
            >
                Load more comments...
            </a>
            <SecondaryLoader v-else/>
        </div>
    </div>
    <SecondaryLoader v-else/>
</template>

<script>
import axiosClient from '../axios';
import Comment from '../components/Comment.vue';
import Alert from '../components/Alert.vue';
import SecondaryLoader from '../components/SecondaryLoader.vue';
import { mapState } from 'vuex';
import store from '../store';

const initialState = () => ({
    comments: {
        data: [],
        loading: true,
        moreLink: null,
    },
});

export default {
    data: () => ({
        comments: {
            data: [],
            loading: true,
            moreLink: null,
        },
    }),
    watch: {
        async $route(to, from) {
            Object.assign(this.$data, initialState());
            if(['UserComments', 'UserReplies'].includes(to.name))
            {
                await this.getComments();
            }
            if(from.name == 'UserReplies' && this.user.notifications)
            {
                store.dispatch('clearNotifications');
            }
        },
    },
    components: {
        SecondaryLoader,
        Comment,
        Alert,
    },
    methods: {
        async getComments() {
            this.comments.loading = true;

            const link = this.comments.moreLink
                ?? this.$route.name == 'UserComments'
                    ? '/comment'
                    : '/reply';

            await axiosClient.get(link)
                .then(( { data } ) => {
                    if(data.data.length
                    && data.data[0].id_reply_to
                        ? this.$route.name == 'UserReplies'
                        : this.$route.name == 'UserComments'
                    )
                    {
                        this.comments.data.push(...data.data);
                        this.comments.moreLink = data.links.at(-1).url;
                    }
                })
                .catch(( { response } ) => {
                    this.comments.error = response.statusText
                });

            this.comments.loading = false;
        },
    },
    computed: {
        ...mapState(['user']),
    },
    async created() {
        await this.getComments();
    },
};
</script>
