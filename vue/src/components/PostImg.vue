<template>
    <div v-if="content_src" class="col-12">
        <img
            :src="content_src"
            :alt="`content_img_${index}`"
            class="img-fluid mx-auto d-block mb-3"
        >
    </div>
</template>


<script>
export default {
    props: ['content_src', 'index']
};
</script>
