<template>
    <nav aria-label="Page navigation pagination-sm">
        <ul class="pagination justify-content-end">
            <slot name="paginas"></slot>
        </ul>
    </nav>
</template>
<style scoped>
    li {
        cursor:pointer
    }
</style>