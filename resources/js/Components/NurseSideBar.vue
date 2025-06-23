<template>
    <div class="h-fit w-full text-white flex flex-col ">
        <div>
            <p>You are logged in as Nurse.</p>
            <h2 class="font-bold pb-6">{{ $page.props.auth.user.username }}</h2>
        </div>
        <AuthLinks
            v-for="nurseLink in nurseLinks"
            :link="nurseLink" :key="nurseLink.id || nurseLink.name"
        />
    </div>
</template>

<script setup>
import AuthLinks from "@/Components/AuthLinks.vue";
let props = defineProps({
    nurseLinks: Array,
})
</script> 