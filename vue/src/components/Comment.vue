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
            <p class="mb-0">{{ comment.body }}</p>
        </div>
    </div>
</template>

<script>
import { formatDate } from '../common-functions.js';

export default {
    props: {
        comment: {
            type: Object,
            require: true,
        }
    },
    mounted() {
        this.comment.created_at = formatDate(this.comment.created_at, true);
    },
}
</script>