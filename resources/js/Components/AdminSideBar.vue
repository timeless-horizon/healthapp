<template>
    <div

        className="h-fit w-full  text-white flex flex-col ">
        <div>
            <p> you're logged in as Admin </p>
            <h2 className="font-bold pb-6">{{ $page.props.auth.user.username }}</h2>

        </div>
        <AuthLinks
            v-for="adminLink in adminLinks"
            :link="adminLink" :key="adminLink.id"
        />
    </div>
</template>

<script setup>
import AuthLinks from "@/Components/AuthLinks.vue";

let props = defineProps({
    adminLinks: Array,
})
</script>

<style scoped>
/* Add any custom styles here if needed */
</style>
