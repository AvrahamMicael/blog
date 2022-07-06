<template>
    <div id="popup-outer">
        <div id="popup-inner">
            <div class="d-flex justify-content-between">
                <span class="my-auto text-capitalize h3">
                    {{ popup }}
                </span>
                <button @click="changeAuthPopup(null)" class="btn btn-sm btn-outline-danger">
                    <i class="fa-solid fa-x"/>
                </button>
            </div>
            <hr>
            <AuthForm/>
        </div>
    </div>
</template>

<script>
import { mapMutations, mapState } from 'vuex';
import RegisterForm from './RegisterForm.vue';
import AuthForm from './AuthForm.vue';

export default {
    methods: {
        ...mapMutations(['changeAuthPopup'])
    },
    computed: {
        ...mapState(['popup'])
    },
    components: {
        RegisterForm,
        AuthForm
    }
};
</script>
