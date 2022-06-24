<template>
    <div class="dropdown">
        <a href="javascript:;" class="text-decoration-none link-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            {{ user.data.name }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end text-small">
            <li>
                <router-link class="dropdown-item" to="#">
                    My Profile
                </router-link>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li v-if="user.role == admin">
                <router-link class="dropdown-item" :to="{name: 'NewPost'}">
                    New Post
                </router-link>
            </li>
            <li v-if="user.role == admin"><hr class="dropdown-divider"></li>
            <li><a @click="logout" class="dropdown-item" href="javascript:;">Logout</a></li>
        </ul>
    </div>
</template>

<script>
import { mapMutations, mapState } from 'vuex'
import { admin } from '../constants/Roles.js';


export default {
    data() {
        return {
            admin
        };
    },
    methods: {
        ...mapMutations(['logout'])
    },
    computed: {
        ...mapState(['user'])
    }
}
</script>