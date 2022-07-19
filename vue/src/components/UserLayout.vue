<template>
    <div class="container">
        <div class="row justify-content-center">
            <aside class="col-md-3 mb-4">
                <div class="card">
                    <nav class="btn-group-vertical" role="group" aria-label="">
                        <router-link :to="{ name: 'UserComments' }" class="btn btn-outline-dark" :class="getActiveClassIfCurrentRoute('UserComments')">Comments</router-link>
                        <router-link :to="{ name: 'UserReplies' }" class="btn btn-outline-dark" :class="getActiveClassIfCurrentRoute('UserReplies')">Replies</router-link>
                        <router-link :to="{ name: '' }" class="btn btn-outline-dark" :class="getActiveClassIfCurrentRoute('')">Settings</router-link>
                    </nav>
                </div>
            </aside>
            <main class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <h3 class="mb-0">
                                {{ title }}
                            </h3>
                            <hr>
                            <router-view/>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        getActiveClassIfCurrentRoute(routeName) {
            return this.$route.name == routeName
                ? 'active'
                : null;
        },
    },
    computed: {
        title() {
            switch (this.$route.name) {
                case 'UserComments':
                    return 'My Comments';
                case 'UserReplies':
                    return 'Replies';
                default:
                    return 'Settings';
            };
        },
    },
};
</script>
