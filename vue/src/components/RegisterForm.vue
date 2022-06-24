<template>
    <form id="form-register">
        <label for="username">Username:</label>
        <input type="text" required minlength="3" maxlength="50" name="username" id="username" class="form-control">
        <label for="email">Email:</label>
        <input type="email" required name="email" id="email" class="form-control">
        <label for="password">Password:</label>
        <input type="password" required name="password" id="password" class="form-control">
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" required name="password_confirmation" id="password_confirmation" class="form-control">
        <AuthButton/>
    </form>
</template>

<script>
import AuthButton from './AuthButton.vue';

export default {
    components: {
        AuthButton
    }
};
</script>
