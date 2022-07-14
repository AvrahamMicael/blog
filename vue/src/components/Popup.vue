<template>
    <div id="popup-outer">
        <div id="popup-inner">
            <div class="d-flex justify-content-between">
                <span class="my-auto text-capitalize h3">
                    {{ title }}
                </span>
                <button @click="$emit('toggle')" class="btn btn-sm btn-outline-danger">
                    <i class="fa-solid fa-x"/>
                </button>
            </div>
            <hr>
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            type: 'text',
        };
    },
    props: {
        title: {
            required: true,
            type: String,
        }
    },
};
</script>
