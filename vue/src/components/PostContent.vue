<template>
    <div class="my-3">
        <p v-if="content.type == 'text'">
            {{ content.value }}
        </p>
        <img
            v-else
            :src="content.value"
            alt="content_img"
            class="img-fluid d-block mx-auto"
        >
    </div>
</template>

<script>
export default {
    props: ['content']
};
</script>