<template>
    <div class="text-center">
        <div class="fw-bold fs-1 link-danger">
            Error
        </div>
        <div class="fs-5 mt-1 mb-3">
            {{ error }}
        </div>
        <button @click="$router.go()" class="btn btn-outline-dark mb-2">
            Try again
        </button>
    </div>
</template>

<script>
export default {
    props: {
        error: {
            type: String,
            required: true
        }
    }
}
</script>
