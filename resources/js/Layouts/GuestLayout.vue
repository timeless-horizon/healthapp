
<template>
    <div
        class=""
    >
        <div>
            <Customnav />
        </div>

        <div
            class=""
        >
            <slot />
        </div>
        <Customfooter />

    </div>
</template>

<script setup>
import Customnav from "@/Components/Customnav.vue"
import Customfooter from "@/Components/Customfooter.vue"
</script>
