<template>
    <section class="text-center">
        <div class="fw-bold fs-1 link-danger">
            404 - Page not found
        </div>
        <div class="fs-5">
            The Page you are looking for might have been removed or temporarily unavailable.
        </div>
        <router-link
            :to="{ name: 'Home' }"
            class="btn btn-outline-dark mt-3 mb-2"
        >
            <i class="fa-solid fa-arrow-left"/>
            Go back to home
        </router-link>
    </section>
</template>
