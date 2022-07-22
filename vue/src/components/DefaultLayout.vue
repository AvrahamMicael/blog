<template>
    <div class="container">
        <div class="row justify-content-center">
            <router-view/>
            <aside class="col-lg-3 col-md-4 mb-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <Img src="/author.jpg" alt="author" classes="rounded-circle"/>
                        <p class="mb-0 mt-3 text-secondary fw-light">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio praesentium fugit consequuntur, odit exercitationem optio rem placeat quibusdam voluptatibus fuga repudiandae consequatur voluptate aliquam ea at doloremque aspernatur ipsa. Quo.
                        </p>
                        <div class="d-flex justify-content-center mt-2">
                            <span v-for="(link, index) in social_medias" :key="link.icon" class="text-nowrap">

                                <span v-if="index != 0">
                                    &nbsp;
                                </span>

                                <a :href="link.href" class="btn btn-outline-info">
                                    <span class="sr-only">{{ link.sr_only }}</span>
                                    <i :class="link.icon"/>
                                </a>
                                
                            </span>
                        </div>
                    </div>
                </div>
                <div v-if="subscribers !== null" class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            Subscribe to Blog via Email
                        </h5>
                    </div>
                    <div class="card-body">
                        <span class="fs-6">
                            Enter your email address to subscribe to this blog and receive notifications of new posts by email.
                        </span>

                        <div class="mt-3">
                            <Alert
                                v-if="subscribe_error"
                                :content="subscribe_error"
                                @toggle="subscribe_error = null"
                            />
                            <Alert
                                v-if="subscribe_success"
                                content="Successfully Subscribed!"
                                type="success"
                                @toggle="subscribe_success = false"
                            />
                        </div>

                        <form @submit.prevent="subscribeSubmit" class="d-block mt-2">
                            Join {{ subscribers }} other followers
                            <label for="subscribe_email" class="sr-only">Email Address:</label>
                            <input v-model="subscribe_email" id="subscribe_email" type="email" maxlength="255" placeholder="Enter your email address" class="form-control" required>
                            <div class="text-center mt-2">
                                <SecondaryLoader v-if="subscribe_loading"/>
                                <button v-else class="btn btn-outline-primary">
                                    Subscribe
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div v-else class="card">
                    <div class="card-body">
                        <SecondaryLoader/>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</template>

<script>
import Img from './Img.vue';
import Alert from './Alert.vue';
import { mapMutations, mapState } from 'vuex';
import SecondaryLoader from './SecondaryLoader.vue';
import axiosClient from '../axios.js';

export default {
    components: {
        Img,
        Alert,
        SecondaryLoader
    },
    data: () => ({
        subscribe_email: null,
        subscribe_loading: false,
        subscribe_error: null,
        subscribe_success: false,
    }),
    methods: {
        ...mapMutations(['setSubscribers']),
        async subscribeSubmit() {
            this.subscribe_loading = true;
            await axiosClient.post('/subscriber', { email: this.subscribe_email })
                .then(( { data } ) => {
                    this.setSubscribers(data);
                    this.subscribe_success = true;
                    this.subscribe_error = null;
                    this.subscribe_email = null;
                })
                .catch(( { response } ) => {
                    this.subscribe_success = false;
                    this.subscribe_error = response.data.message;
                });
            this.subscribe_loading = false;
        },
    },
    computed: {
        ...mapState(['subscribers']),
        social_medias() {
            return [
                { sr_only: 'My facebook', href: 'javascript:;', icon: 'fa-brands fa-facebook-f' },
                { sr_only: 'My instagram', href: 'javascript:;', icon: 'fa-brands fa-instagram' },
                { sr_only: 'My twitter', href: 'javascript:;', icon: 'fa-brands fa-twitter' },
                { sr_only: 'My youtube channel', href: 'javascript:;', icon: 'fa-brands fa-youtube' },
            ];
        },
    },
};
</script>
