<template>
    <div class="dropdown">
        <a href="javascript:;" class="text-decoration-none link-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            {{ user.data.name }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end text-small">
            <li>
                <router-link class="dropdown-item" :to="{ name: 'UserComments' }">
                    Comments/Replies
                </router-link>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li v-if="user.data.role == admin">
                <router-link class="dropdown-item" :to="{name: 'NewPost'}">
                    New Post
                </router-link>
            </li>
            <li v-if="user.data.role == admin"><hr class="dropdown-divider"></li>
            <li><a @click="logout" class="dropdown-item" href="javascript:;">Logout</a></li>
        </ul>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import { admin } from '../constants/Roles.js';


export default {
    data() {
        return {
            admin
        };
    },
    methods: {
        logout() {
            this.$store
                .dispatch('logout')
                .then(() => {
                    if(this.$route.meta.isAdmin)
                    {
                        this.$router.push({
                            name: 'Home'
                        });
                    }
                });
        }
    },
    computed: {
        ...mapState(['user'])
    }
}
</script>