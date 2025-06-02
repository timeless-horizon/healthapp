<script setup>
import { onMounted, onUnmounted } from "vue";

const loadTawkTo = () => {
    const script = document.createElement("script");
    script.type = "text/javascript";
    script.async = true;
    script.src = "https://embed.tawk.to/67a21624825083258e0fe969/1ij8icvru"; 
    script.charset = "UTF-8";
    script.setAttribute("crossorigin", "*");

    document.body.appendChild(script);
};

onMounted(() => {
    loadTawkTo();
});

onUnmounted(() => {
    const chatScript = document.querySelector('script[src*="tawk.to"]');
    if (chatScript) chatScript.remove();
});
</script>

<template>
    <div>
        
    </div>
</template>


