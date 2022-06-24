<template>
    <header class="bg-light">
        <div class="container d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <router-link :to="{name: 'Home'}" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
                <img src="../../public/logo.png" class="fluid-img" width="50" alt="logo">
            </router-link>

            <Navbar/>

            <div class="col-md-3 d-flex justify-content-end">
                <a href="javascript:;" class="link-dark my-auto mx-5 d-inline-block">
                    <i class="fas fa-search"/>
                </a>
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
    </header>
</template>

<script>
import { mapMutations, mapState } from 'vuex'
import HeaderDropdownButton from "./HeaderDropdownButton.vue";
import Navbar from "./Navbar.vue";

export default {
    methods: {
        ...mapMutations(['changeAuthPopup'])
    },
    computed: {
        ...mapState(['user'])
    },
    components: {
        HeaderDropdownButton,
        Navbar
    }    
}
</script>
