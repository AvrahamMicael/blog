<template>
    <div class="mt-3">
        <div
            v-for="(error, i) in errors"
            :key="i"
            class="text-center text-danger"
        >
            {{ error[0] }}
        </div>
    </div>
</template>

<script>
export default {
    props: ['errors']
}
</script>