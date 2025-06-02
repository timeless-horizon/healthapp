<template>
    <!-- Footer Section -->
    <footer class="bg-gray-900 text-gray-400">
        <div class="max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-12">
                <!-- Logo and Description -->
                <div class="lg:col-span-4">
                    <h2 class="text-xl font-semibold text-white">Timelesshealthcare247</h2>
                    <p class="mt-4 text-gray-400">
                        We provide excellent Teleconsultation to our global clients with 21st century technology.
                    </p>
                </div>

                <!-- Footer Links -->
                <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 lg:col-span-8 lg:grid-cols-3">
                    <!-- Contact Information -->
                    <div>
                        <h3 class="text-white font-semibold mb-4">Contact Information</h3>
                        <ul class="space-y-3">
                            <li>
                                <Link href="/faq" class="hover:text-white transition duration-300">FAQs</Link>
                            </li>
                            <li>
                                <a href="https://web.facebook.com/profile.php?id=61573695332609" target="_blank"
                                    rel="noopener noreferrer" class="hover:text-white transition duration-300">
                                    Facebook
                                </a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/timeless_health_care/?igsh=YWwwOWRtdjVmYnJm#"
                                    target="_blank" class="hover:text-white transition duration-300">
                                    Instagram
                                </a>
                            </li>
                            <li>
                                <a href="https://x.com/THC_247?t=3iRmpHz7ucAgqh_lniwsxQ&s=09" target="_blank"
                                    class="hover:text-white transition duration-300">
                                    X (Twitter)
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Useful Links -->
                    <div>
                        <h3 class="text-white font-semibold mb-4">Useful Links</h3>
                        <ul class="space-y-3">
                            <li>
                                <Link href="/" class="hover:text-white transition duration-300">Home</Link>
                            </li>
                            <li>
                                <Link href="/services" class="hover:text-white transition duration-300">Services</Link>
                            </li>
                            <li>
                                <Link href="/contact" class="hover:text-white transition duration-300">Contact</Link>
                            </li>
                            <li>
                                <Link href="/register" class="hover:text-white transition duration-300">Register</Link>
                            </li>
                            <li>
                                <Link href="/login" class="hover:text-white transition duration-300">Login</Link>
                            </li>
                        </ul>
                    </div>

                    <!-- Company -->
                    <div>
                        <h3 class="text-white font-semibold mb-4">Company</h3>
                        <ul class="space-y-3">
                            <li>
                                <Link href="/about" class="hover:text-white transition duration-300">About</Link>
                            </li>
                            <!-- <li>
                                <Link href="/blog" class="hover:text-white transition duration-300">Blog</Link>
                            </li> -->
                            <!-- <li>
                                <Link href="/jobs" class="hover:text-white transition duration-300">Jobs</Link>
                            </li> -->
                            <li>
                                <Link href="/privacypolicy" class="hover:text-white transition duration-300">Privacy
                                Policy</Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="mt-12 text-center border-t border-gray-700 pt-6">
                <p class="text-gray-400">© {{ currentYear }} timelesshealthcare247, Inc. All rights reserved.</p>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const currentYear = ref(new Date().getFullYear());
</script>