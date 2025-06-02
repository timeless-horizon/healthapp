<template>
    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-sm">
            <h1 class="text-2xl font-bold mb-4">Create a New Plan</h1>
            <ResponseMessage :response="response" :class="bg" />
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Plan Title</label>
                    <input type="text" id="title" v-model="form.title"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Enter Plan Title" required />
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Plan Description</label>
                    <textarea id="description" v-model="form.description" rows="3"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Enter Plan Description" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700">Plan Price</label>
                    <input type="number" id="price" v-model="form.price"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Enter Plan Price" step="0.01" required />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Features</label>
                    <div v-for="(feature, index) in form.features" :key="index" class="flex items-center mb-2">
                        <input type="text" v-model="form.features[index]"
                            class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Enter Feature" required />
                        <button type="button" @click="removeFeature(index)"
                            class="ml-2 text-red-500 hover:text-red-700">
                            Remove
                        </button>
                    </div>
                    <button type="button" @click="addFeature"
                        class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600">
                        Add Feature
                    </button>
                </div>
                <button type="submit"
                    class="w-full px-4 py-2 bg-green-500 text-white font-bold rounded-lg shadow hover:bg-green-600">
                    Create Plan
                </button>
            </form>
        </div>
    </AuthenticatedLayout>

</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { ref } from "vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import ResponseMessage from "@/Components/ResponseMessage.vue";
let form = useForm({
    title: '',
    description: '',
    price: '',
    features: [],
})

let response = ref('')
let bg = ref('')
let addFeature = () => {
    form.features.push('');
}
let removeFeature = (index) => {
    form.features.splice(index, 1);
}
let submit = async () => {
    await axios.post('/admin-add-plan', { form })
        .then(res => {
            if (res) {
                toast.success(res.data.message);
                form.reset()
                setTimeout(() => {
                    window.location.href = '/admin-plans';
                }, 3000);
            }
        })
        .catch(err => {
            if (err) {
                toast.error("Couldn't save Plan \n Please make sure Plan doesn't already exist");
            }
        })
}
</script>
