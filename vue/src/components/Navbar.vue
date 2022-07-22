<template>
    <ul class="nav col-12 offset-md-2 col-md-auto mb-2 justify-content-center mb-md-0">
        <li>
            <router-link
                :to="{ name: 'Home' }"
                :class="classBasedOnRoute('Home')"
            >
                Home
            </router-link>
        </li>
        <li>
            <a
                :href="`mailto:${contact_email}`"
                :class="classBasedOnRoute()"
            >
                Contact
            </a>
        </li>
    </ul>        
</template>

<script>
export default {
    methods: {
        classBasedOnRoute(route_name = null) {
            return [
                    this.$route.name == route_name
                    ? 'link-secondary'
                    : 'link-dark',
                    'nav-link px-2'
                ];
        },
    },
    computed: {
        contact_email() {
            return import.meta.env.VITE_CONTACT_EMAIL;
        },
    },
}
</script>