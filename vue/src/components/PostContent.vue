<template>
    <div class="my-3">
        <p v-if="content.type == 'text'">
            {{ content.value }}
        </p>
        <Img
            v-else
            :src="content.value"
            classes="d-block mx-auto"
        />
    </div>
</template>

<script>
import Img from './Img.vue';

export default {
components: { Img },
props: {
    content: {
        type: Object,
        required: true,
    },
},
};
</script>