<template>
    <div class="dropdown">
        <a href="javascript:;" class="text-decoration-none link-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            {{ user.data.name }}
            <span class="badge rounded-pill bg-danger">
                {{ user.notifications }}
            </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end text-small">
            <li v-for="menu_item in dropdown_menu" :key="menu_item.name">
                <router-link
                    v-if="
                        menu_item.to
                        && checkUserAdminIfAdminOnly(menu_item)
                    "
                    :to="menu_item.to"
                    class="dropdown-item"
                >
                    {{ menu_item.name }}
                    <span v-if="menu_item.name == 'Replies'" class="badge rounded-pill bg-danger">
                        {{ user.notifications }}
                    </span>
                </router-link>
                <hr
                    v-else-if="
                        !menu_item.to
                        && checkUserAdminIfAdminOnly(menu_item)
                    "
                    class="dropdown-divider"
                >
            </li>
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
                    if(this.$route.meta.isAdmin
                    || this.$route.meta.isLogged)
                    {
                        this.$router.push({
                            name: 'Home'
                        });
                    }
                });
        },
        checkUserAdminIfAdminOnly(item) {
            return item.onlyAdmin
                ? this.user.data.role == admin
                : true
        },
    },
    computed: {
        ...mapState(['user']),
        dropdown_menu() {
            return [
                { to: { name: 'UserComments' }, name: 'Comments' },
                { to: { name: 'UserReplies' }, name: 'Replies' },
                { to: { name: 'Settings' }, name: 'Settings' },
                { to: null, name: 'divider' },
                { to: { name: 'NewPost' }, name: 'New Post', onlyAdmin: true },
                { to: null, name: 'divider', onlyAdmin: true },
            ];
        },
    },
}
</script>