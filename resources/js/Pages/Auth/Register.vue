<template>

    <Head title="Register" />
    <GuestLayout>
        <section
            class="pl-4 pr-4 pb-4 pt-8 bg-[url('https://images.unsplash.com/photo-1504813184591-01572f98c85f?q=80&w=2071&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')] bg-gray-700 bg-blend-multiply">
            <div
                class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-md transform transition duration-500 ease-in-out animate-fade-in delay-300 mt-24 mb-10">
                <h2 class="text-2xl font-semibold mb-4">Sign Up Form</h2>

                <form @submit.prevent="submitForm">
                    <div v-if="currentStep === 1">
                        <h3 class="text-xl font-bold mb-3">User Profile Information</h3>
                        <div class="flex flex-col md:flex-row items-center gap-2 mb-4 w-full">
                            <div class="w-full">
                                <label class="block text-gray-700">Surname</label>
                                <input type="text" v-model="form.surname"
                                    class="bg-gray-50 w-full border-gray-300 rounded-md p-2" />
                            </div>
                            <div class="w-full">
                                <label class="block text-gray-700">Other Names</label>
                                <input type="text" v-model="form.otherNames"
                                    class="bg-gray-50 w-full border-gray-300 rounded-md p-2" />
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row items-center gap-2 mb-4 w-full">
                            <div class="w-full">
                                <label class="block text-gray-700">Gender</label>
                                <select v-model="form.gender" class="bg-gray-50 w-full border-gray-300 rounded-md p-2"
                                    name="" id="">
                                    <option selected disabled value="">Select an option</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="w-full">
                                <label class="block text-gray-700">Date of Birth</label>
                                <input type="date" v-model="form.dateOfBirth"
                                    class="bg-gray-50 w-full border-gray-300 rounded-md p-2" />
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row items-center gap-2 mb-4 w-full">
                            <div class="w-full">
                                <label for="phone" class="block text-gray-700">Phone number</label>
                                <input type="tel" id="phone" v-model="form.tel"
                                    class="bg-gray-50 w-full border-gray-300 rounded-md p-2"
                                    placeholder="123-456-7890" />
                            </div>
                            <div class="w-full">
                                <label for="email" class="block text-gray-700">Email address</label>
                                <input type="email" id="email" v-model="form.email"
                                    class="bg-gray-50 w-full border-gray-300 rounded-md p-2"
                                    placeholder="john.doe@company.com" />
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row items-center gap-2 mb-4 w-full">
                            <div class="w-full">
                                <label class="block text-gray-700">Address</label>
                                <input type="text" v-model="form.address"
                                    class="bg-gray-50 w-full border-gray-300 rounded-md p-2" />
                            </div>
                            <div class="w-full">
                                <label class="block text-gray-700">Country</label>
                                <select v-model="selectedCountry" @change="updateStates"
                                    class="bg-gray-50 w-full border-gray-300 rounded-md p-2">
                                    <option value="" disabled>Select a country</option>
                                    <option v-for="(states, country) in countries" :key="country" :value="country">{{
                                        country }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4" v-if="stateOptions.length > 0">
                            <label class="block text-gray-700">State</label>
                            <select v-model="selectedState" class="bg-gray-50 w-full border-gray-300 rounded-md p-2">
                                <option value="" disabled>Select a state</option>
                                <option v-for="state in stateOptions" :key="state" :value="state">{{ state }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div v-if="currentStep === 2">
                        <h3 class="text-xl font-bold mb-3">Medical History and Information</h3>
                        <div class="mb-4">
                            <label class="block text-gray-700">Medical Conditions</label>
                            <textarea v-model="form.medicalConditions" class="w-full border-gray-300 rounded-md p-2"
                                rows="3"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Medications</label>
                            <select v-model="form.medications" class="bg-gray-50 w-full border-gray-300 rounded-md p-2">
                                <option value="" disabled>Select</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>

                    <div v-if="currentStep === 3">
                        <h3 class="text-xl font-bold mb-3">Security and Authentication</h3>
                        <div class="mb-4">
                            <label class="block text-gray-700">Username</label>
                            <input type="text" v-model="form.username"
                                class="bg-gray-50 w-full border-gray-300 rounded-md p-2" />
                        </div>
                        <div class="mb-6">
                            <label for="password" class="block text-gray-700">Password</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" v-model="form.password" id="password"
                                    class="bg-gray-50 w-full border-gray-300 rounded-md p-2" />
                                <button type="button" @click="togglePasswordVisibility"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" v-if="showPassword">
                                        <path
                                            d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                        </path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" v-else>
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label for="confirm_password" class="block text-gray-700">Confirm password</label>
                            <div class="relative">
                                <input :type="showConfirmPassword ? 'text' : 'password'"
                                    v-model="form.password_confirmation" id="confirm_password"
                                    class="bg-gray-50 w-full border-gray-300 rounded-md p-2" />
                                <button type="button" @click="toggleConfirmPasswordVisibility"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" v-if="showConfirmPassword">
                                        <path
                                            d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                        </path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" v-else>
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-if="currentStep === 4">
                        <h3 class="text-xl font-bold mb-3">Additional Information</h3>
                        <div class="mb-4">
                            <label class="block text-gray-700">Preferred Language</label>
                            <select v-model="form.preferredLanguage"
                                class="bg-gray-50 w-full border-gray-300 rounded-md p-2">
                                <option value="" disabled>Select language</option>
                                <option value="english">English</option>
                                <option value="french">French</option>
                                <option value="spanish">Spanish</option>
                            </select>
                        </div>
                        <div class="mb-4 flex items-start">
                            <input type="checkbox" v-model="form.termsAccepted" class="mr-2" />
                            <label class="text-gray-700">I agree to the <a href="https://mega.nz/file/lb1XkQDD#gB27ksTspCbpNN52gx34siiVaYZqok7YA0BKM1fmI04" target="blank" class="underline hover:text-blue-700">terms and conditions</a> </label>
                        </div>
                        <button type="submit" :disabled="loading"
                            class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 disabled:bg-blue-300 flex items-center justify-center disabled:cursor-not-allowed">
                            <span v-if="loading" class="animate-spin mr-2">⟳</span>
                            <span>{{ loading ? 'Submitting...' : 'Submit' }}</span>
                        </button>
                    </div>

                    <div class="flex items-center justify-between mt-6 mb-4">
                        <button type="button" class="bg-gray-500 text-white py-2 px-6 rounded-md hover:bg-gray-600"
                            @click="previousStep" v-if="currentStep > 1">Previous</button>
                        <button type="button"
                            class="bg-blue-500 text-white py-2 px-6 rounded-md hover:bg-blue-600 disabled:bg-blue-300 disabled:cursor-not-allowed"
                            @click="nextStep" v-if="currentStep < totalSteps">Next</button>
                    </div>
                </form>
                <p class="text-sm text-gray-600 text-center mb-4">Already have an account?
                    <Link href="/login" class="underline text-gray-600 hover:text-blue-700 dark:hover:text-blue-500">
                    Login</Link>
                </p>
            </div>
        </section>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ref, reactive, computed } from 'vue';
import axios from 'axios';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { Link, Head } from '@inertiajs/vue3';

const currentStep = ref(1);
const totalSteps = 4;
const loading = ref(false);
const showPassword = ref(false);
const showConfirmPassword = ref(false);

const form = reactive({
    surname: '',
    otherNames: '',
    address: '',
    tel: '',
    email: '',
    medicalConditions: '',
    medications: '',
    username: '',
    password: '',
    password_confirmation: '',
    preferredLanguage: '',
    gender: '',
    dateOfBirth: '',
    termsAccepted: false,
});
const selectedCountry = ref('');
const selectedState = ref('');
const stateOptions = ref([]);
const countries = {
    // USA: ['California', 'Texas', 'Florida', 'New York'],
    // Canada: ['Ontario', 'Quebec', 'British Columbia', 'Alberta'],
    // India: ['Maharashtra', 'Tamil Nadu', 'Delhi', 'Karnataka'],
    Nigeria: ['Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue',
        'Borno', 'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'Gombe',
        'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos',
        'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers',
        'Sokoto', 'Taraba', 'Yobe', 'Zamfara', 'Abuja'
    ],
};

// Toggle password visibility functions
const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

const toggleConfirmPasswordVisibility = () => {
    showConfirmPassword.value = !showConfirmPassword.value;
};

// Add a function to validate individual fields
const validateStep = () => {
    const errors = [];

    switch (currentStep.value) {
        case 1:
            if (!form.surname.trim()) errors.push("Surname is required");
            if (!form.otherNames.trim()) errors.push("Other names are required");
            if (!form.address.trim()) errors.push("Address is required");
            if (!/^[\d-]{9,}$/.test(form.tel)) errors.push("Please enter a valid phone number (minimum 9 digits)");
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) errors.push("Please enter a valid email address");
            if (!selectedCountry.value) errors.push("Please select a country");
            if (stateOptions.value.length > 0 && !selectedState.value) errors.push("Please select a state");
            break;

        case 2:
            if (form.medications === '') errors.push("Please specify if you take medications");
            break;

        case 3:
            if (!form.username.trim()) errors.push("Username is required");
            if (form.password.length < 8) errors.push("Password must be at least 8 characters long");
            if (form.password !== form.password_confirmation) errors.push("Passwords do not match");
            break;
    }

    return errors;
};

// Update canProceedToNextStep to just check if there are no errors
const canProceedToNextStep = computed(() => {
    return validateStep().length === 0;
});

// Modified nextStep function
const nextStep = () => {
    const errors = validateStep();
    if (errors.length === 0 && currentStep.value < totalSteps) {
        currentStep.value++;
    } else {
        // Show all errors in separate toasts
        errors.forEach(error => toast.error(error));
    }
};

const previousStep = () => {
    if (currentStep.value > 1) currentStep.value--;
};

const updateStates = () => {
    stateOptions.value = countries[selectedCountry.value] || [];
    selectedState.value = '';
};

// Modified submitForm function
const submitForm = async () => {
    if (!form.termsAccepted) {
        toast.error('Please accept the terms and conditions');
        return;
    }

    const errors = validateStep();
    if (errors.length > 0) {
        errors.forEach(error => toast.error(error));
        return;
    }

    loading.value = true;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formData = {
        ...form,
        country: selectedCountry.value,
        state: selectedState.value,
    };

    try {
        const response = await axios.post('/register', formData, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
        });
        toast.success('Registration successful!');
        setTimeout(() => {
            window.location.href = '/dashboard';
            // window.location.href = '/patient-dashboard-overview';
        }, 1500);
    } catch (error) {
        if (error.response && error.response.status === 422) {
            const errorData = error.response.data.errors;
            if (errorData) {
                Object.values(errorData).forEach(errorArray => {
                    errorArray.forEach(error => {
                        toast.error(error);
                    });
                });
            } else {
                toast.error('Validation failed');
            }
        } else {
            toast.error('An unexpected error occurred');
        }
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 1s ease-out;
}
</style>