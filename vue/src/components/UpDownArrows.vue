<template>
    <div class="col-1 d-flex flex-column justify-content-center">
        <a @click="$emit('clickArrow', -1)" href="javascript:;" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-up"/>
        </a>
        <a @click="$emit('clickArrow', 1)" href="javascript:;" class="btn btn-outline-success">
            <i class="fa-solid fa-arrow-down"/>
        </a>
    </div>
</template>
