<template>
    <div>
        <label class="text-capitalize" :for="name">{{ name }}:</label>
        <select
            v-model="value_input"
            class="text-capitalize form-select"
            :name="name"
            :id="name"
        >
            <option
                v-for="(option, index) in options"
                :key="index"
            >
                {{ option }}
            </option>
        </select>
    </div>
</template>
<script>
export default {
    props: ['name', 'modelValue', 'options'],
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