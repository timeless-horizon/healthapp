<template>
  <nav class="fixed top-0 w-full bg-white border-gray-300 shadow-sm z-50">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto px-4 py-6">
      <!-- Logo Section -->
      <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="https://i.ibb.co/2WBm3ff/logo.png" class="h-8" alt="Timeless Healthcare Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap text-gray-600">
          <Link href="/">Timeless Healthcare</Link>
        </span>
      </a>

      <!-- Hamburger Menu Button -->
      <button @click="toggleDrawer" type="button"
        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
        aria-controls="navbar-default" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M1 1h15M1 7h15M1 13h15" />
        </svg>
      </button>

      <!-- Large screen Nav -->
      <div class="hidden w-full md:block md:w-auto" id="navbar-default">
        <ul
          class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg md:flex-row md:space-x-4 rtl:space-x-reverse md:mt-0 md:border-0">
          <li>
            <Link href="/" class="block py-2 px-3 text-gray-600">
            Home</Link>
          </li>
          <li>
            <Link href="/services" class="block py-2 px-3 text-gray-600">
            Services</Link>
          </li>
          <li>
            <Link href="/pricing" class="block py-2 px-3 text-gray-600">
            Pricing</Link>
          </li>
          <li>
            <Link href="/about" class="block py-2 px-3 text-gray-600">
            About Us</Link>
          </li>
          <li>
            <Link href="/contact" class="block py-2 px-3 text-gray-600">
            Contact Us</Link>
          </li>
          <li v-if="!page.props.auth?.user">
            <Link href="/register" class="block py-2 px-3 text-gray-600">
            Register</Link>
          </li>
          <li v-if="!page.props.auth?.user">
            <Link href="/login" class="block py-2 px-3 text-gray-600">
            Login</Link>
          </li>
          <li v-if="page.props.auth?.user">
            <Link href="/dashboard" class="block py-2 px-3 text-gray-600">
            Dashboard</Link>
          </li>
        </ul>
      </div>

    </div>

    <!-- Sliding Drawer -->
    <div
      :class="['fixed top-0 left-0 h-full bg-gray-50 z-50 transform transition-transform', isDrawerOpen ? 'translate-x-0' : '-translate-x-full']"
      class="w-72">
      <div class="p-4">
        <button @click="toggleDrawer"
          class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none">
          <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <ul class="font-medium text-xl flex flex-col gap-5 items-center w-full h-full mt-24">
        <li>
          <Link href="/" class="block text-blue-700 hover:text-blue-500">Home
          </Link>
        </li>
        <li>
          <Link href="/services" class="block text-blue-700 hover:text-blue-500">Services</Link>
        </li>
        <li>
          <Link href="/pricing" class="block text-blue-700 hover:text-blue-500">Pricing</Link>
        </li>
        <li>
          <Link href="/about" class="block text-blue-700 hover:text-blue-500">
          About Us</Link>
        </li>
        <li>
          <Link href="/contact" class="block text-blue-700 hover:text-blue-500">Contact Us</Link>
        </li>
        <li v-if="!page.props.auth?.user">
          <Link href=" /register" class="block text-blue-700 hover:text-blue-500">Register</Link>
        </li>
        <li v-if="!page.props.auth?.user">
          <Link href="/login" class="block text-blue-700 hover:text-blue-500">
          Login</Link>
        </li>
        <li v-if="page.props.auth?.user">
          <Link href="/dashboard" class="block  text-blue-700 hover:text-blue-500">
          Dashboard</Link>
        </li>
      </ul>
    </div>
  </nav>

</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
const page = usePage();

</script>

<script>
export default {
  data() {
    return {
      isDrawerOpen: false,
    };
  },
  methods: {
    toggleDrawer() {
      this.isDrawerOpen = !this.isDrawerOpen;
    },
  },
};
</script>
