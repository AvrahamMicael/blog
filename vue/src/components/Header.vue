<template>
    <header class="bg-light">
        <div class="container d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <router-link :to="{name: 'Home'}" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
                <img src="/logo.png" class="img-fluid" width="50" alt="logo">
            </router-link>

            <Navbar/>

            <div class="col-md-3 d-flex justify-content-end">
                <a @click="toggleSearchPopup" href="javascript:;" class="link-dark my-auto mx-5 d-inline-block">
                    <span class="sr-only">Search</span>
                    <i class="fas fa-search"/>
                </a>

                <div v-if="$route.name != 'SubscribedDelete'">
                    <HeaderDropdownButton v-if="user.token"/>
                    <a
                        v-else
                        @click="changeAuthPopup('login')"
                        href="javascript:;"
                        class="btn btn-outline-dark btn-sm"
                    >
                        Login
                    </a>
                </div>
            </div>
        </div>

        <teleport to='body'>
            <Popup v-if="searchPopup" title="Search" @toggle="toggleSearchPopup">
                <form @submit.prevent="submitSearch" class="row justify-content-around">
                    <Input
                        v-model="search"
                        label="search term"
                        :type="search"
                        class="col-10"
                    />
                    <div class="col-2 d-flex">
                        <button class="btn btn-outline-success mt-auto">
                            <span class="sr-only">Search Button</span>
                            <i class="fas fa-search"/>
                        </button>
                    </div>
                </form>
            </Popup>
        </teleport>

    </header>
</template>

<script>
import Input from './Input.vue'
import Popup from './Popup.vue'
import { mapMutations, mapState } from 'vuex'
import HeaderDropdownButton from "./HeaderDropdownButton.vue";
import Navbar from "./Navbar.vue";
import router from '../router';

export default {
    data: () => ({
        searchPopup: false,
        search: '',
    }),
    methods: {
        ...mapMutations(['changeAuthPopup']),
        toggleSearchPopup () { 
            this.searchPopup = !this.searchPopup;
        },
        submitSearch() {
            this.toggleSearchPopup();
            router.push({
                name: 'Search',
                params: { search: this.search },
            });
            this.search = '';
        },
    },
    computed: {
        ...mapState(['user'])
    },
    components: {
        HeaderDropdownButton,
        Navbar, Popup, Input
    }    
}
</script>
