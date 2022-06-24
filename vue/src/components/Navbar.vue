<template>
    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li v-for="item in navigation" :key="item.name">
            <router-link
                :to="item.to"
                active-class="link-secondary"
                :class="[
                    this.$route.name == item.to.name
                    ? null
                    : 'link-dark',
                    'nav-link px-2'
                ]"
            >
                {{ item.name }}
            </router-link>
        </li>
    </ul>        
</template>

<script>
export default {
    data() {
        return {
            navigation: [
                {name: 'Home', to: {name: 'Home'}},
                {name: 'Contact', to: '#'},
                {name: 'About', to: '#'}
            ]
        };
    }
}
</script>