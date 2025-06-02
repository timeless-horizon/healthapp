<template>
    <!-- {{ appointmentData }} -->
    <div class="z-10 status-dropdown">
        <div class="relative">
            <div v-if="!['completed'].includes(modelValue)" ref="triggerRef" @click="toggleDropdown"
                class="px-2 py-1 rounded text-center cursor-pointer flex items-center justify-between" :class="{
                    'bg-yellow-500 text-white': modelValue === 'pending',
                    'bg-red-500 text-white': modelValue === 'missed',
                    'bg-green-100 text-black': modelValue === 'completed'
                }">
                <span class="capitalize">{{ modelValue }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div v-else class="px-2 py-1 rounded text-center" :class="{
                'bg-yellow-500 text-white': modelValue === 'pending',
                'bg-red-500 text-white': modelValue === 'missed',
                'bg-green-100 text-black': modelValue === 'completed'
            }">
                <span class="capitalize">{{ modelValue }}</span>
            </div>
            <Teleport to="body">
                <div v-if="isOpen" ref="dropdownRef"
                    class="dropdown-menu fixed z-50 mt-1 w-40 bg-white shadow-lg rounded-md py-1 border border-gray-300"
                    :class="{ 'dropdown-menu-up': position === 'top' }"
                    :style="{ top: `${dropdownTop}px`, left: `${dropdownLeft}px` }">
                    <div v-for="(option, index) in statusOptions" :key="index" @click="selectStatus(option)"
                        class="px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm capitalize">
                        {{ option }}
                    </div>
                </div>
            </Teleport>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, watch, onUnmounted } from 'vue';
import axios from 'axios';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
const dropdownTop = ref(0);
const dropdownLeft = ref(0);
const props = defineProps({
    modelValue: {
        type: String,
        required: true
    },
    appointmentId: {
        type: [Number, String],
        required: true
    },
    appointmentData: Object,
    docTime: String,
    meeting: String

});
const emit = defineEmits(['update:modelValue', 'statusChanged']);
const isOpen = ref(false);
const statusOptions = ['pending', 'completed', 'missed'];
const position = ref('bottom');
const triggerRef = ref(null);
const dropdownRef = ref(null);

const toggleDropdown = () => {
    if (!['completed'].includes(props.modelValue)) {
        isOpen.value = !isOpen.value;
        if (isOpen.value) {
            setTimeout(() => updatePosition(), 0);
        }
    }
};

const selectStatus = async (status) => {
    if (status !== props.modelValue) {
        try {
            const response = await axios.post(`/doctor-reschedule-appointment/${props.appointmentId}`, {
                status: status,
                doctorDateAndTime: props.appointmentData.clients_date_and_time,
                user_id: props.appointmentData.user_id,
                meeting_link: props.appointmentData.meeting_link
            });
            if (response) {
                toast.success('Status Updated')
            }
            emit('updated');
            emit('update:modelValue', status);
            emit('statusChanged', status);
            window.location.reload()
        } catch (error) {
            toast.error('Failed to update appointment status. Please try again.');
        }
    }
    isOpen.value = false;
};

const updatePosition = () => {
    if (!dropdownRef.value || !triggerRef.value) return;

    const triggerRect = triggerRef.value.getBoundingClientRect();
    const dropdownHeight = dropdownRef.value.offsetHeight;
    const viewportHeight = window.innerHeight;
    const viewportWidth = window.innerWidth;

    const spaceBelow = viewportHeight - triggerRect.bottom;
    const spaceAbove = triggerRect.top;

    position.value = spaceBelow < dropdownHeight && spaceAbove > dropdownHeight ? 'top' : 'bottom';

    // Compute position for Teleport
    dropdownTop.value = position.value === 'top'
        ? triggerRect.top - dropdownHeight
        : triggerRect.bottom;

    dropdownLeft.value = triggerRect.left;

    // Optional: Adjust to prevent right overflow
    const dropdownWidth = 160; // adjust based on your width (w-40 = 160px)
    if (dropdownLeft.value + dropdownWidth > viewportWidth) {
        dropdownLeft.value = viewportWidth - dropdownWidth - 10; // 10px margin
    }
};


const closeDropdown = (event) => {
    if (isOpen.value && dropdownRef.value && triggerRef.value &&
        !dropdownRef.value.contains(event.target) && !triggerRef.value.contains(event.target)) {
        isOpen.value = false;
    }
};

watch(isOpen, (value) => {
    if (value) {
        window.addEventListener('click', closeDropdown);
        window.addEventListener('scroll', updatePosition, true);
        window.addEventListener('resize', updatePosition);
    } else {
        window.removeEventListener('click', closeDropdown);
        window.removeEventListener('scroll', updatePosition, true);
        window.removeEventListener('resize', updatePosition);
    }
});

onUnmounted(() => {
    window.removeEventListener('click', closeDropdown);
    window.removeEventListener('scroll', updatePosition, true);
    window.removeEventListener('resize', updatePosition);
});
</script>

<style scoped>
.status-dropdown {
    position: relative;
    display: inline-block;
    width: 100%;
}

.dropdown-menu {
    min-width: 120px;
    max-height: 200px;
    overflow-y: auto;
    top: 100%;
}

.dropdown-menu-up {
    top: auto;
    bottom: 100%;
}
</style>