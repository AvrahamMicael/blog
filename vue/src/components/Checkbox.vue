<template>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" v-model="input_value" :id="label">
        <label class="form-check-label" :for="label">
            {{ label }}
    </label>
</div>
</template>

<script>
export default {
    props: ['label', 'modelValue'],
    computed: {
        input_value: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        }
    }
}
</script>