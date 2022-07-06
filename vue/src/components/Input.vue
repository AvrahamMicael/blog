<template>
    <div>
        <label v-if="label" class="text-capitalize" :for="id">{{ label }}:</label>
        <input
            v-model="value_input"
            :type="type ?? 'text'"
            :id="id"
            .required="required ?? false"
            class="form-control"
        >
    </div>
</template>

<script>
export default {
    props: ['label', 'type', 'modelValue', 'required'],
    computed: {
        value_input: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        },
        id() {
            return this.label
                .replace(' ', '')
                .toLowerCase();
        }
    }
}
</script>
