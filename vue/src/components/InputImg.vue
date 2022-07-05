<template>
    <div>
        <label v-if="label" class="text-capitalize" :for="label">{{ label }}:</label>
        <input
            type="file"
            @change="changeFile"
            :name="name"
            :id="name"
            .required="required ?? false"
            class="form-control"
        >
    </div>
</template>

<script>
export default {
    props: ['label', 'name', 'modelValue', 'required'],
    methods: {
        changeFile(ev) {
            this.$emit('update:modelValue', ev.target.files[0]);
        }
    }
}
</script>
