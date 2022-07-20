<template>
    <div class="col-md-8 mb-4">
        <main class="card mb-4">
            <div class="card-body">
                <Post v-if="post" v-model="post"/>
                <SecondaryLoader v-else/>
            </div>
        </main>
        <div v-if="post" class="card">
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
                            Leave a Comment
                        </a>
                    </div>
                    <form v-if="commentFormToggled" @submit.prevent="saveComment" class="d-block my-3" id="commentForm">
                        <hr class="my-2">
                        <h5>Leave a Comment</h5>

                        <div v-if="rep_comment" class="px-5 mt-2 row">
                            <div class="col-11">
                                <span class="fst-italic">Replying to</span>
                                <Comment replying :comment="rep_comment"/>
                            </div>
                            <div class="col-1 d-flex">
                                <a @click="removeReply" class="btn btn-outline-danger my-auto">
                                    <span class="sr-only">Remove Reply</span>
                                    <i class="fa-solid fa-x"/>
                                </a>
                            </div>
                        </div>

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
                        <div v-for="comment_ in comments.data" :key="comment_.id">
                            <Comment
                                :comment="comment_"
                                :ref="`comment-${comment_.id}`"
                                @delete="deleteComment"
                                @update="updateComment"
                                @reply="toggleReplyForm"
                            />
                            <div v-if="comment_.replies?.length" class="ps-5">
                                <div v-for="(reply, rep_index) in comment_.replies" :key="reply.id">
                                    <Comment
                                        v-if="comment_.showAllReplies || rep_index == 0"
                                        :comment="reply"
                                        :ref="`reply-${reply.id}`"
                                        @delete="deleteComment"
                                        @update="updateComment"
                                    />
                                    <div v-if="!comment_.showAllReplies && rep_index == 0 && comment_.replies[1]" class="text-center">
                                        <a
                                            @click="comment_.showAllReplies = true"
                                            href="javascript:;"
                                            class="link-dark d-inline-block pb-1 px-1 border border-secondary border-2 border-top-0"
                                        >
                                            Load more Replies...
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="comments.moreLink" class="text-center mt-1">
                            <a
                                v-if="!comments.loading"
                                @click="getComments()"
                                href="javascript:;"
                                class="link-dark d-inline-block pb-1 px-1 border border-dark border-2 border-top-0"
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
import { getPostBySlug, updateObjInArray } from '../common-functions.js';
import { mapMutations, mapState } from 'vuex';
import axiosClient from '../axios.js';

export default {
    data: () => ({
        post: null,
        commentFormToggled: false,
        comment: { id_post: null },
        rep_comment: null,
        comments: {
            data: [],
            loading: false,
            loaded: false,
            error: null,
            moreLink: null,
        },
    }),
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
        ...mapMutations(['changeAuthPopup', 'toggleLoader']),
        toggleReplyForm(rep_comment) {
            this.commentFormToggled = true;
            this.rep_comment = rep_comment;
            this.comment.id_reply_to = rep_comment.id;
        },
        removeReply() {
            this.rep_comment = null;
            this.comment.id_reply_to = null;
        },
        getPathById_reply_to(comment) {
            return comment.id_reply_to
                ? `/reply/${comment.id}`
                : `/comment/${comment.id}`;
        },
        async deleteComment(d_comment) {
            this.toggleLoader();

            const path = this.getPathById_reply_to(d_comment);

            await axiosClient.delete(path)
                .then(( { data, status } ) => {
                    if(status == 204)
                    {
                        this.comments.data = this.comments.data.filter(comment => {
                            if(d_comment.id_reply_to)
                            {
                                comment.replies = comment.replies.filter(c_rep => c_rep.id != d_comment.id);
                            }
                            return d_comment.id_reply_to
                                ? true
                                : comment.id != d_comment.id;
                        });
                    }
                    else
                    {
                        this.comments.data = updateObjInArray(this.comments.data, 'id', data, true);
                    }
                })
                .catch(() => d_comment.error = 'Something went wrong!');

            this.toggleLoader();
        },
        async updateComment(up_comment) {
            this.toggleLoader();

            const path = this.getPathById_reply_to(up_comment);

            await axiosClient.put(path, up_comment)
                .then(() => {
                    this.comments.data = up_comment.id_reply_to
                        ? this.updateCommentReply(this.comments.data, up_comment)
                        : updateObjInArray(this.comments.data, 'id', up_comment);

                    const refName = up_comment.id_reply_to
                        ? `reply-${up_comment.id}`
                        : `comment-${up_comment.id}`;
                    const commentComponent = this.$refs[refName][0];
                    commentComponent.comment.isUpdated = true;
                    commentComponent.toggleEditForm();
                })
                .catch(( { response } ) => {
                    up_comment.error = response.statusText;
                });

            this.toggleLoader();
        },
        updateCommentReply(array, updated_comment) {
            return array.map(cm => {
                cm.replies = updateObjInArray(cm.replies, 'id', updated_comment);
                return cm;
            });
        },
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

                    if(this.rep_comment)
                    {
                        this.rep_comment.showAllReplies = true;
                        this.comments.data = updateObjInArray(this.comments.data, 'id', this.rep_comment);
                        this.rep_comment = null;
                    }
                })
                .catch(() => {
                    this.comment.error = 'Something went wrong!'
                });
        },
        addComments(comments) {
            if(Array.isArray(comments))
            {
                this.comments.data.push(...comments);
            }
            else
            {
                if(comments.id_reply_to)
                {
                    this.comments.data = this.comments.data.map(comment => {
                        if(comment.id == comments.id_reply_to)
                        {
                            comment.replies.push(comments);
                        }
                        return comment;
                    });
                }
                else
                {
                    comments.replies = [];
                    this.comments.data.unshift(comments);
                }
            }
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
        this.comment.id_post = this.post?.id;
    },
};
</script>
