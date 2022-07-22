<template>
    <form @submit.prevent="submitAuthForm">
        <Input
            v-if="popup == 'register'"
            label="name"
            v-model="user.name"
            type="text"
            :required="true"
        />
        <Input
            label="email"
            v-model="user.email"
            type="email"
            :required="true"
        />
        <Input
            label="password"
            v-model="user.password"
            type="password"
            :required="true"
        />
        <Input
            v-if="popup == 'register'"
            label="confirm password"
            v-model="user.password_confirmation"
            type="password"
            :required="true"
        />

        <Checkbox
            v-if="popup == 'login'"
            label="Remember me"
            v-model="user.remember"
        />
        <Checkbox
            v-if="popup == 'register'"
            label="Subscribe via Email"
            v-model="user.subscribe"
        />

        <AuthErrorsList v-model="errors"/>
        <AuthButton/>
    </form>
</template>

<script>
import { mapState } from 'vuex';
import AuthButton from './AuthButton.vue';
import AuthErrorsList from './AuthErrorsList.vue';
import Checkbox from './Checkbox.vue';
import Input from './Input.vue';

export default {
    data() {
        return {
            user: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                remember: false,
                subscribe: false,
            },
            errors: {}
        };
    },
    components: {
        AuthButton,
        Checkbox,
        AuthErrorsList,
        Input,
    },
    methods: {
        async submitAuthForm() {
            if(this.popup == 'login')
            {
                delete this.user.name;
                delete this.user.password_confirmation;
            }
            this.errors = await this.$store.dispatch('submitAuthForm', this.user);
        },
    },
    computed: {
        ...mapState(['popup'])
    }
};
</script>
