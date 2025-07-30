<template>
    <Link :href="planHasExpired == 0 ? '/patient-available-plans' : `/${props.link.name}`"
        class="mb-4 hover:text-blue-300 transition-colors bg-gray-500 w-full py-2 px-3 rounded-md h-10 flex items-center">
    <div class="flex items-center justify-start">
        <div v-html="link.icon" class="w-6 h-6 mx-2 flex items-center justify-center"></div>
        <div>
            {{ capitalizeFirstLetter(props.link.name) }}
        </div>
    </div>
    </Link>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { onMounted } from "vue";
let props = defineProps({
    link: Object,
    planHasExpired: Number
})

let capitalizeFirstLetter = (str) => {
    let processLetter = str.replace(/[-_]/g, ' ')
    let words = processLetter.split(' ');
    words.shift();
    words = words.join(' ')
    words = words[0].charAt(0).toUpperCase() + words.slice(1)
    return words;
}
</script>

<style scoped></style>
