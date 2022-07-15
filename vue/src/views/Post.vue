<template>
    <div class="col-md-8 mb-4">
        <main class="card mb-4">
            <div class="card-body">
                <Post v-if="post" v-model="post"/>
                <SecondaryLoader v-else/>
            </div>
        </main>
        <div v-if="post" class="card" id="comments_card">
            <div v-if="comments.loaded">
                <section class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="mb-0">
                            <i class="fa fa-comments-o"/>
                            Comments
                        </h4>
                        <a
                            v-if="!commentFormToggled"
                            @click="commentFormToggled = true"
                            class="btn btn-outline-dark"
                        >
                            Leave a Reply
                        </a>
                    </div>
                    <form v-if="commentFormToggled" @submit.prevent="saveComment" class="d-block my-3">
                        <hr class="my-2">
                        <h5>Leave a Reply</h5>

                        <div v-if="!user.token" class="row">
                            <div class="col-md-4">
                                <Input v-model="comment.user_name" placeholder="Your Name" :required="true"/>
                            </div>
                            <div class="col-md-4">
                                <Input v-model="comment.email" placeholder="Your Email Address" type="email" :required="true"/>
                            </div>
                            <div class="col-1 d-flex text-secondary"><span class="my-auto mx-auto">or</span></div>
                            <div class="col-md-3">
                                <a @click="changeAuthPopup('login')" class="btn btn-outline-secondary">
                                    login
                                </a>
                            </div>
                        </div>
                        
                        <Alert
                            v-if="comment.error"
                            :content="comment.error"
                            @toggle="delete comment.error"
                            class="mt-3"
                        />

                        <TextArea
                            v-model="comment.body"
                            :required="true"
                        />
                        <div class="d-flex justify-content-end mt-3">
                            <button class="btn btn-outline-warning">
                                Submit Comment
                            </button>
                        </div>
                    </form>
                    <hr class="my-2">
                    <div v-if="comments.data.length">
                        <Comment
                            v-for="comment_ in comments.data" :key="comment_.body"
                            :comment="comment_"
                        />
                        <div v-if="comments.moreLink" class="text-center">
                            <a
                                v-if="!comments.loading"
                                @click="getComments()"
                                href="javascript:;"
                                class="link-dark"
                            >
                                Load more comments...
                            </a>
                            <SecondaryLoader v-else/>
                        </div>
                    </div>
                    <div v-else class="text-center text-secondary p-2 fs-5 mt-3">
                        This post doesn't have comments.
                    </div>
                </section>
            </div>
            <SecondaryLoader v-else-if="!comments.error"/>
            <DisplayError
                v-else
                error="Something went wrong!"
            />
        </div>
    </div>
</template>

<script>
import DisplayError from '../components/DisplayError.vue';
import Post from '../components/Post.vue';
import Input from '../components/Input.vue';
import SecondaryLoader from '../components/SecondaryLoader.vue';
import Alert from '../components/Alert.vue';
import Comment from '../components/Comment.vue';
import TextArea from '../components/TextArea.vue';
import { getPostBySlug } from '../common-functions.js';
import { mapMutations, mapState } from 'vuex';
import axiosClient from '../axios.js';

export default {
    data() {
        return {
            post: null,
            commentFormToggled: false,
            comment: { id_post: null },
            comments: {
                data: [],
                loading: false,
                loaded: false,
                error: null,
                moreLink: null,
            },
        };
    },
    watch: {
        post() {
            this.$nextTick(this.getComments);
        },
    },
    components: {
        Input,
        Alert,
        Comment,
        DisplayError,
        Post,
        SecondaryLoader,
        TextArea,
    },
    computed: {
        ...mapState(['user']),
    },
    methods: {
        ...mapMutations(['changeAuthPopup']),
        showCommentForm() {
                this.commentFormToggled = true;
        },
        saveComment() {
            if(!this.user.token
            && !this.comment.user_name
            && !this.comment.email)
            {
                this.comment.error = 'You need to fill with your name and email!';
                return;
            }
            this.$store.dispatch('saveComment', this.comment)
                .then(( { data } ) => {
                    this.addComments(data);
                    this.comment = { id_post: this.post.id };
                })
                .catch(() => this.comment.error = 'Something went wrong!');
        },
        addComments(comments) {
            if(Array.isArray(comments)) this.comments.data.push(...comments);
            else this.comments.data.unshift(comments);
        },
        async getComments() {
            this.comments.loading = true;

            await axiosClient.get(this.comments.moreLink ?? `/comment/${this.post.id}`)
                .then(( { data } ) => {
                    this.addComments(data.data);
                    this.comments.moreLink = data.links.at(-1).url;
                    this.comments.loaded = true;
                })
                .catch(() => this.comments.error = true);

            this.comments.loading = false;
        },
    },
    async created() {
        this.post = await getPostBySlug(this.$route.params.slug);
        this.comment.id_post = this.post.id;
    },
};
</script>
