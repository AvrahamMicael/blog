<template>
    <form @submit.prevent="login" id="form-login">
        <label for="email">Email:</label>
        <input v-model="user.email" type="email" required name="email" id="email" class="form-control">
        <label for="password">Password:</label>
        <input v-model="user.password" type="password" required name="password" id="password" class="form-control">
        <Checkbox label="Remember me" v-model="user.remember"/>
        <AuthErrorsList v-model="errors"/>
        <!-- <AuthErrorsList :errors="errors" @cleanErrors="errors = {}"/> -->
        <AuthButton/>
    </form>
</template>

<script>
import AuthButton from './AuthButton.vue';
import AuthErrorsList from './AuthErrorsList.vue';
import Checkbox from './Checkbox.vue';

export default {
    data() {
        return {
            user: {
                email: '',
                password: '',
                remember: false
            },
            disableButton: false,
            errors: {}
        };
    },
    components: {
        AuthButton,
        Checkbox,
        AuthErrorsList
    },
    methods: {
        login() {
            auth_button.disabled = true;
            this.$store.dispatch('login', this.user)
                .catch(error => {
                    this.errors = error.response.data.errors;
                });
            auth_button.disabled = false;
        }
    }
};
</script>
