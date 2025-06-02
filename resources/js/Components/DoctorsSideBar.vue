<template>
    <div
        className="h-fit w-full  text-white flex flex-col ">
        <div>
            <p>You are logged in as Dr.</p>
            <h2 className="font-bold pb-6">{{ $page.props.auth.user.username }}</h2>

        </div>

        <AuthLinks
            v-for="doctorLink in doctorLinks"
            :link="doctorLink" :key="doctorLink.id"
        />
    </div>
</template>

<script setup>
import AuthLinks from "@/Components/AuthLinks.vue";
import {ref} from "vue";

let props = defineProps({
    doctorLinks: Array,
})
</script>

