<template>
    <button
        @click="logout"
        class="text-blue-500 w-full h-12 bg-red-600 rounded-md font-bold text-2xl text-center flex items-center justify-center">Logout </button>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import axios from "axios";

let logout = () =>{
    axios.post("/logout" )
        .then(
                window.location.href = '/login'
        )
}


</script>

<style scoped>

</style>
