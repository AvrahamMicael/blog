<template>
    <div>
        <label v-if="label" class="text-capitalize" :for="id">{{ label }}:</label>
        <input
            v-model="value_input"
            :type="type"
            :id="id"
            :required="required"
            :placeholder="placeholder"
            class="form-control"
        >
    </div>
</template>

<script>
export default {
    props: {
        label: String,
        type: {
            type: String,
            default: 'text',
        },
        modelValue: { required: true },
        required: { default: false },
        placeholder: {
            type: String,
            default: '',
        },
    },
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
                ?   this.label
                    .replace(' ', '')
                    .toLowerCase()
                : null;
        }
    }
}
</script>
