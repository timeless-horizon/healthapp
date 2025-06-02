<template>
        <AuthenticatedLayout>
            <Plans
                id="planSection"
            :plans="plans"
            />

        </AuthenticatedLayout>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Plans from "@/Components/Plans.vue";

let props = defineProps({
    plans: Array,
})
</script>
