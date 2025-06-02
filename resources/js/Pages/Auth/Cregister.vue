<template>
    <div class="max-w-4xl mx-auto p-8 bg-white rounded-lg shadow-lg">
      <h1 class="text-2xl font-bold mb-6 text-center">Sign Up Form</h1>
      <form @submit.prevent="submitForm">
        <!-- User Profile Information -->
        <section class="mb-6">
          <h2 class="text-lg font-semibold mb-4">User Profile Information</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block font-medium">Surname</label>
              <input v-model="form.surname" type="text" class="w-full border rounded p-2" required />
            </div>
            <div>
              <label class="block font-medium">Other Names</label>
              <input v-model="form.otherNames" type="text" class="w-full border rounded p-2" required />
            </div>
            <div>
              <label class="block font-medium">Email Address</label>
              <input v-model="form.email" type="email" class="w-full border rounded p-2" required />
            </div>
            <div>
              <label class="block font-medium">Phone Number</label>
              <input v-model="form.phone" type="tel" class="w-full border rounded p-2" required />
            </div>
            <div>
              <label class="block font-medium">Date of Birth</label>
              <input v-model="form.dob" type="date" class="w-full border rounded p-2" required />
            </div>
            <div>
              <label class="block font-medium">Gender</label>
              <select v-model="form.gender" class="w-full border rounded p-2">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div>
              <label class="block font-medium">Address</label>
              <input v-model="form.address" type="text" class="w-full border rounded p-2" required />
            </div>
            <div>
              <label class="block font-medium">State of Origin</label>
              <input v-model="form.state" type="text" class="w-full border rounded p-2" required />
            </div>
            <div>
              <label class="block font-medium">Country</label>
              <input v-model="form.country" type="text" class="w-full border rounded p-2" required />
            </div>
          </div>
        </section>

        <!-- Medical History -->
        <section class="mb-6">
          <h2 class="text-lg font-semibold mb-4">Medical History and Information (Optional)</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block font-medium">Medical Conditions</label>
              <textarea v-model="form.medicalConditions" class="w-full border rounded p-2"></textarea>
            </div>
            <div>
              <label class="block font-medium">Medications</label>
              <select v-model="form.medications" class="w-full border rounded p-2">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
            <div>
              <label class="block font-medium">Allergies</label>
              <textarea v-model="form.allergies" class="w-full border rounded p-2"></textarea>
            </div>
            <div>
              <label class="block font-medium">Family Medical History</label>
              <textarea v-model="form.familyHistory" class="w-full border rounded p-2"></textarea>
            </div>
            <div>
              <label class="block font-medium">Previous Surgeries or Hospitalizations</label>
              <textarea v-model="form.surgeries" class="w-full border rounded p-2"></textarea>
            </div>
          </div>
        </section>

        <!-- Payment Information -->
        <section class="mb-6">
          <h2 class="text-lg font-semibold mb-4">Payment Information</h2>
          <div>
            <label class="block font-medium">Payment Method</label>
            <select v-model="form.paymentMethod" class="w-full border rounded p-2" required>
              <option value="Credit Card">Credit Card</option>
              <option value="PayPal">PayPal</option>
              <option value="Bank Transfer">Bank Transfer</option>
            </select>
          </div>
        </section>

        <!-- Security and Authentication -->
        <section class="mb-6">
          <h2 class="text-lg font-semibold mb-4">Security and Authentication</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block font-medium">Username</label>
              <input v-model="form.username" type="text" class="w-full border rounded p-2" required />
            </div>
            <div>
              <label class="block font-medium">Password</label>
              <input v-model="form.password" type="password" class="w-full border rounded p-2" required />
            </div>
            <div>
              <label class="block font-medium">Security Questions (Optional)</label>
              <textarea v-model="form.securityQuestions" class="w-full border rounded p-2"></textarea>
            </div>
          </div>
        </section>

        <!-- Additional Information -->
        <section class="mb-6">
          <h2 class="text-lg font-semibold mb-4">Additional Information</h2>
          <div>
            <label class="block font-medium">Preferred Language</label>
            <input v-model="form.language" type="text" class="w-full border rounded p-2" />
          </div>
        </section>

        <!-- Terms and Conditions -->
        <div class="mb-6">
          <label class="inline-flex items-center">
            <input v-model="form.terms" type="checkbox" class="form-checkbox text-blue-500" required />
            <span class="ml-2">I agree to the terms and conditions</span>
          </label>
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
          Submit
        </button>
      </form>
    </div>
  </template>


<script setup>
import {useForm} from "@inertiajs/vue3";
let form = useForm({
    surname: '',
    otherNames: '',
    email: '',
    phone: '',
    dob: '',
    gender: '',
    address: '',
    state: '',
    country: '',
    medicalConditions: '',
    medications: '',
    allergies: '',
    familyHistory: '',
    surgeries: '',
    paymentMethod: '',
    username: '',
    password: '',
    securityQuestions: '',
    language: '',
    terms: false,
})

let submitForm = () => {
    alert('Form submitted!');
}
</script>
