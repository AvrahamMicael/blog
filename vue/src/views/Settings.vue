<template>
    <form @submit.prevent="updateUser" class="d-flex justify-content-center">
        <div class="col-lg-8 col-md-12">
            <Input label="Name" v-model="form.name" :placeholder="user.data.name" :required="true"/>
            <Input label="New Password" v-model="form.password" placeholder="Optional" type="password"/>
            <Input label="New Password Confirmation"  v-model="form.password_confirmation" type="password"/>
            <Input label="Old Password" v-model="form.old_password" type="password"/>

            <Alert v-if="error" :content="error" @toggle="error = null" class="mt-3"/>
            <Alert v-if="success" :content="success" @toggle="success = null" type="success" class="mt-3"/>
            
            <div class="d-flex justify-content-around mt-3">
                <a @click="deleteUser" class="btn btn-outline-danger">
                    Delete Account
                </a>
                <button class="btn btn-outline-primary">
                    Edit Information
                </button>
            </div>
        </div>
    </form>
</template>

<script>
import Alert from '../components/Alert.vue'
import Input from '../components/Input.vue'
import { mapState } from 'vuex';

export default {
    methods: {
        async updateUser () { 
            if(!this.form.password) delete this.form.password;
            if(!this.form.password_confirmation) delete this.form.password_confirmation;
            if(!this.form.old_password) delete this.form.old_password;

            await this.$store.dispatch('updateUser', this.form)
                .then(() => {
                    this.success = 'Successfully updated!'
                    delete this.form.password;
                    delete this.form.password_confirmation;
                    delete this.form.old_password;
                })
                .catch(( { response } ) => this.error = response.data.message);
        },
        async deleteUser() {
            await this.$store.dispatch('deleteUser')
                .catch(( { response } ) => this.error = response.data.message);
        },
    },
    components: { Input, Alert },
    data: () => ({
        form: {
            name: '',
            old_password: '',
            password: '',
            password_confirmation: '',
        },
        error: null,
        success: null,
    }),
    computed: {
        ...mapState(['user']),
    },
    created() {
        this.form.name = this.user.data.name;
    },
};
</script>
