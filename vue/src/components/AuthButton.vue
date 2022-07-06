<template>
    <div class="text-center mt-3">
        <div class="mb-1  d-block" href="javascript:;">
            <a @click="changeAuthMethod" href="javascript:;" class="link-primary text-decoration-none small">
                <small>
                    {{ changeAuthWords }}
                </small>
            </a>
        </div>
        <button id="auth_button" class="btn btn-outline-success text-capitalize">
            {{ popup }}
        </button>
    </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex';

export default {
    methods: {
        ...mapMutations(['changeAuthMethod']),
    },
    computed: {
        ...mapState(['popup']),
        changeAuthWords() {
            if(this.popup == 'register')
            {
                return 'Already have an account? Login now!';
            }
            return "Doesn't have an account? Register now!";
        }
    }
};
</script>