<template>
    <p
        :class="bg"
        class="my-3 px-3 py-3 fixed top-0 z-50 w-full" >{{ props.response }}</p>
</template>

<script setup>


let props = defineProps({
    response: String
})
</script>

<style scoped>

</style>
