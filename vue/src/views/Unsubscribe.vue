<template>
    <div v-if="loaded" class="row text-center justify-content-center">
        <h4 class="mb link-danger">
            Are your sure you want to unsubscribe?
        </h4>

        <Alert
            v-if="error"
            :content="error"
            @toggle="error = null"
            class="mt-2 col-md-6"
        />
        
        <SecondaryLoader v-if="delete_loading"/>
        <form
            v-else
            @submit.prevent="unsubscribeSubmit"
            class="d-block mt-1"
        >
            <button class="btn btn-outline-danger">
                Yes
            </button>
            &nbsp;
            <router-link
                :to="{ name: 'Home' }"
                class="btn btn-outline-success"
            >
                No
            </router-link>
        </form>
    </div>
    <SecondaryLoader v-else/>
</template>

<script>
import Alert from '../components/Alert.vue'
import axiosClient from '../axios.js';
import SecondaryLoader from '../components/SecondaryLoader.vue';

export default {
    data: () => ({
        loaded: false,
        delete_loading: false,
        error: null,
    }),
    components: {
    Alert,
        SecondaryLoader,
    },
    methods: {
        async unsubscribeSubmit() {
            this.delete_loading = true;

            await axiosClient.delete(this.path)
                .then(() => {
                    this.$router.push({
                        name: 'Home'
                    });
                })
                .catch(() => this.error = 'Something went Wrong!');

            this.delete_loading = false;
        },
    },
    computed: {
        path() {
            return `/subscriber/${this.$route.params.id}/${this.$route.params.token}`;
        },
    },
    async created() {
        await axiosClient.get(this.path)
            .then(() => this.loaded = true)
            .catch(() => this.$router.push({
                name: 'NotFound'
            }));
    },
};
</script>
