<template>
    <form @submit.prevent="submitAuthForm">
        <label for="email">Email:</label>
        <input v-model="user.email" type="email" required name="email" id="email" class="form-control">
        <label for="password">Password:</label>
        <input v-model="user.password" type="password" required name="password" id="password" class="form-control">
        <Checkbox label="Remember me" v-model="user.remember"/>
        <AuthErrorsList v-model="errors"/>
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
        AuthErrorsList,
    },
    methods: {
        async submitAuthForm() {
            this.errors = await this.$store.dispatch('submitAuthForm', this.user);
        },
    },
};
</script>
