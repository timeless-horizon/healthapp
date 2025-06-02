<template>
    <div class="h-fit w-full text-white flex flex-col">
        <div>
            <h2 class="font-bold pb-6">{{ $page.props.auth.user.username }}</h2>
        </div>
        <AuthLinks v-for="patientLink in patientLinks" :link="patientLink" :planHasExpired="planHasExpired"
            :key="patientLink.id" />
    </div>
</template>

<script setup>
import AuthLinks from "@/Components/AuthLinks.vue";
let props = defineProps({
    patientLinks: Array,
    planHasExpired: Number
});
</script>