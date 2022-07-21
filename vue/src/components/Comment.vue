<template>
    <div class="card my-3">
        <div class="card-header">
            <div class="row">
                <div class="col-8 fw-bold">
                    {{ comment.user_name }}
                    <span v-if="comment.user?.role == admin || comment.role == admin" class="badge rounded-pill bg-primary">
                        author
                    </span>
                </div>
                <div class="col-4 text-end">
                    {{ created_at }}
                </div>
            </div>
        </div>
        <div class="card-body">
            <form @submit.prevent="$emit('update', comment)" v-if="editForm" class="row">
                <TextArea
                    v-model="comment.body"
                    returnAs="string"
                    :required="true"
                    class="col-11"
                />
                <div class="d-flex col-1">
                    <button class="my-auto btn btn-outline-success">
                        <span class="sr-only">Submit Comment</span>
                        <i class="fa-solid fa-check"/>
                    </button>
                </div>
            </form>
            <p v-else class="mb-0">{{ body }}</p>
        </div>
        <div v-if="showFooter && !replying" class="card-footer text-end">
            <Alert
                v-if="comment.error"
                :content="comment.error"
                @toggle="delete comment.error"
            />

            <a
                v-if="!comment.id_reply_to"
                @click="$emit('reply', comment)"
                href="#commentForm"
                class="btn btn-outline-dark btn-sm"
            >
                <span class="sr-only">Reply Comment</span>
                <i class="fas fa-reply"/>
            </a>
            <span v-if="showUpdateDelete">
                &nbsp;
                <a v-if="!isDeleted" @click="toggleEditForm" class="btn btn-outline-primary btn-sm">
                    <span class="sr-only">Edit Comment</span>
                    <i class="fas fa-edit"/>
                </a>
                &nbsp;
                <a @click="$emit('delete', comment)" class="btn btn-outline-danger btn-sm">
                    <span class="sr-only">Delete Comment</span>
                    <i class="fa-solid fa-x"/>
                </a>
            </span>
        </div>
    </div>
</template>

<script>
import Alert from './Alert.vue'
import { mapState } from 'vuex';
import { formatDate } from '../common-functions.js';
import { admin } from '../constants/Roles.js';
import TextArea from './TextArea.vue';

export default {
    data: () => ({
        editForm: false,
        originalCommentBody: null,
    }),
    components: { Alert, TextArea },
    emits: [
        'update',
        'delete',
        'reply',
    ],
    methods: {
        toggleEditForm() {
            if(this.editForm == true && !this.comment.isUpdated) this.comment.body = this.originalCommentBody;
            delete this.comment.isUpdated;
            this.editForm = !this.editForm;
        },
    },
    computed: {
        ...mapState(['user']),
        created_at() {
            return formatDate(this.comment.created_at, true);
        },
        isDeleted() {
            return this.comment.body === null;;
        },
        body() {
            return this.comment.body
                ?? '[DELETED]';
        },
        admin() {
            return admin;
        },
        showFooter() {
            return !['UserComments', 'UserReplies'].includes(this.$route.name)
                && (
                    !this.comment.id_reply_to
                    || this.showUpdateDelete
                );
        },
        showUpdateDelete() {
            return this.user.token
                && (
                    this.user.data?.id == this.comment.id_user
                    && this.comment.id_user != null
                    || this.user.data?.role == admin
                );
        },
    },
    props: {
        comment: {
            type: Object,
            required: true,
        },
        replying: Boolean,
    },
    mounted() {
        this.originalCommentBody = this.comment.body;
    },
}
</script>