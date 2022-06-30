<template>
    <div class="container">
        <div class="row justify-content-between">
            <div class="card col-md-8 mb-4">
                <main class="card-body">
                    <router-view/>
                </main>
            </div>
            <aside class="offset-md-1 col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        aside
                    </div>
                </div>
            </aside>
        </div>
    </div>
</template>
