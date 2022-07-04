<template>
    <div class="mt-2">
        <label v-if="name" class="text-capitalize" :for="name">{{ name }}:</label>
        <textarea
            v-model="value_input"
            :name="name"
            :id="name"
            .required="required ?? false"
            rows="3"
            class="form-control"
        ></textarea>
    </div>
</template>

<script>
export default {
    props: ['name', 'modelValue', 'returnAs', 'required'],
    computed: {
        value_input: {
            get() {
                return this.returnAs == 'object'
                    ? this.modelValue?.value
                    : this.modelValue;
            },
            set(value) {
                if(this.returnAs == 'object')
                {
                    value = {
                        type: 'text',
                        value
                    };
                }
                this.$emit('update:modelValue', value);
            }
        }
    }
}

</script>
