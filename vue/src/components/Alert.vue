<template>
    <div
        class="alert alert-dismissible fade show"
        :class="`alert-${type}`"
        role="alert"
    >
        <a @click="$emit('toggle')" href="javascript:;" class="btn-close"/>
        {{ content }}
    </div>
</template>

<script>
export default {
    emits: ['toggle'],
    props: {
        content: {
            required: true,
            type: String,
        },
        type: {
            required: false,
            type: String,
            default: 'danger',
        }
    },
};
</script>