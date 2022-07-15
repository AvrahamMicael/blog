<template>
    <div>
        <img
            v-show="image_loaded"
            :src="src"
            @load="image_loaded = true"
            :alt="alt"
            class="img-fluid"
            :class="classes"
        >
        <SecondaryLoader v-if="!image_loaded"/>
    </div>
</template>

<script>
import SecondaryLoader from './SecondaryLoader.vue';

export default {
props: {
    src: {
        type: String,
        required: true,
    },
    alt: {
        type: String,
        default: 'content_img',
    },
    classes: String,
},
components: {
    SecondaryLoader,
},
data: () => ({
    image_loaded: false,
}),
};
</script>
