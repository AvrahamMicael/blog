<template>
    <div class="card my-3">
        <div class="card-header">
            <div class="row">
                <div class="col-5 fw-bold">
                    {{ comment.user_name }}
                </div>
                <div class="offset-3 col-4 text-end">
                    {{ comment.created_at }}
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
                        <i class="fa-solid fa-check"/>
                    </button>
                </div>
            </form>
            <p v-else class="mb-0">{{ comment.body }}</p>
        </div>
        <div
            v-if="
                user.token
                && (user.data?.id == comment.id_user
                    && comment.id_user != null
                    || user.data?.role == admin
                )
            "
            class="card-footer text-end"
        >
            <Alert
                v-if="comment.error"
                :content="comment.error"
                @toggle="delete comment.error"
            />
            <a @click="toggleEditForm" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-edit"/>
            </a>
            &nbsp;
            <a @click="$emit('delete', comment)" class="btn btn-outline-danger btn-sm">
                <i class="fa-solid fa-x"/>
            </a>
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
        admin() {
            return admin;
        },
    },
    props: {
        comment: {
            type: Object,
            required: true,
        },
    },
    mounted() {
        this.comment.created_at = formatDate(this.comment.created_at, true);
        this.originalCommentBody = this.comment.body;
    },
}
</script>