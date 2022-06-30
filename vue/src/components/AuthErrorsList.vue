<template>
    <div v-if="showErrors" class="mt-3 d-flex justify-content-center">
        <div class="row bg-danger rounded text-light">
            <div
                v-for="(error, i) in errors"
                :key="i"
                class="text-center col-9"
            >
                {{ error[0] }}
            </div>
            <div class="d-flex col-3 justify-content-center">
                <i @click="errors = {}" class="my-auto fa-solid fa-x"/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['modelValue'],
    computed: {
        errors: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value)
            }
        },
        showErrors() {
            return Object.keys(this.errors).length !== 0;
        }
    }
}
</script>

<style lang="css">
    i
    {
        cursor: pointer;
    }
</style>
