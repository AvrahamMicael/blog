<template>
    <form @submit.prevent="register" id="form-register">
        <label for="name">Name:</label>
        <input v-model="user.name" type="text" required minlength="3" maxlength="50" name="name" id="name" class="form-control">
        <label for="email">Email:</label>
        <input v-model="user.email" type="email" required name="email" id="email" class="form-control">
        <label for="password">Password:</label>
        <input v-model="user.password" type="password" required name="password" id="password" class="form-control">
        <label for="password_confirmation">Confirm Password:</label>
        <input v-model="user.password_confirmation" type="password" required name="password_confirmation" id="password_confirmation" class="form-control">
        <AuthErrorsList :errors="errors" @cleanErrors="errors = {}"/>
        <AuthButton/>
    </form>
</template>

<script>
import AuthButton from './AuthButton.vue';
import AuthErrorsList from './AuthErrorsList.vue';

export default {
    data() {
        return {
            user: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            errors: {}
        };
    },
    components: {
        AuthButton,
        AuthErrorsList
    },
    methods: {
        register() {
            auth_button.disabled = true;
            this.$store.dispatch('register', this.user)
                .catch(error => {
                    this.errors = error.response.data.errors;
                });
            auth_button.disabled = false;
        }
    },
};
</script>
