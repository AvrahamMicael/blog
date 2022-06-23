<template>
    <header class="bg-light">
        <div class="container d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <router-link :to="{name: Home}" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
                <img src="../../public/logo.png" class="fluid-img" width="50" alt="logo">
            </router-link>

            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><router-link to="#" class="nav-link px-2 link-secondary">Home</router-link></li>
                <li><router-link to="#" class="nav-link px-2 link-dark">Contact</router-link></li>
                <li><router-link to="#" class="nav-link px-2 link-dark">About</router-link></li>
            </ul>

            <div class="col-md-3 text-end">
                <a id="search" href="javascript:;" class="link-dark">
                    <i class="fas fa-search"/>
                </a>
            </div>
        </div>
    </header>
</template>