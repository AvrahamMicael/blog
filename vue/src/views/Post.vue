<template>
    <div>
        test
        <h2>{{ post.title }}</h2>
        <p>
            {{ post.body }}
        </p>
    </div>
</template>

<script>
// import { mapState } from 'vuex';

export default {
    computed: {
        post() {
            return this.$store.state.posts.find(post => {
                return post.id == parseInt(this.$route.params.id);
            });
        }
    }
}
</script>