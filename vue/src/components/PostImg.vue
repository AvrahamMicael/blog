<template>
    <div v-if="content_value" class="col-12">
        <img
            :src="content_value"
            :alt="`content_img_${index}`"
            class="img-fluid mx-auto d-block mb-3"
        >
    </div>
</template>


<script>
export default {
    props: ['content_value', 'index']
};
</script>
