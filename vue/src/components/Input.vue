<template>
    <div>
        <label v-if="label" class="text-capitalize" :for="label">{{ label }}:</label>
        <input
            v-model="value_input"
            :type="type ?? 'text'"
            :name="name"
            :id="name"
            .required="required ?? false"
            class="form-control"
        >
    </div>
</template>

<script>
export default {
    props: ['label', 'name', 'type', 'modelValue', 'required'],
    computed: {
        value_input: {
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
