<template>
    <div class="text-center mt-3">
        <a @click="changeAuthMethod" class="mb-1 link-primary text-decoration-none small d-block" href="javascript:;">
            <small>
                {{ changeAuthWords }}
            </small>
        </a>

        <button id="auth_button" class="btn btn-outline-success text-capitalize">
            {{ popup }}
        </button>
    </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex';

export default {
    computed: {
        ...mapState(['popup']),
        ...mapMutations(['changeAuthMethod']),
        changeAuthWords() {
            if(this.popup == 'register')
            {
                return 'Already have an account? Login now!';
            }
            return "Doesn't have an account? Register for free!";
        }
    }
};
</script>