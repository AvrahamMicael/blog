<template>
    <form id="form-login">
        <label for="email">Email:</label>
        <input type="email" required name="email" id="email" class="form-control">
        <label for="password">Password:</label>
        <input type="password" required name="password" id="password" class="form-control">
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
