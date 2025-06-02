<template>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-6">
      <div v-if="!meetingScheduled" class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4 text-center">Book an Appointment</h2>
  
        <!-- Date Picker -->
        <label class="block mb-2 text-gray-700">Select Date:</label>
        <input type="date" v-model="selectedDate" class="w-full p-2 mb-4 border rounded-lg focus:ring focus:ring-blue-300">
  
        <!-- Time Picker -->
        <label class="block mb-2 text-gray-700">Select Time:</label>
        <input type="time" v-model="selectedTime" class="w-full p-2 mb-4 border rounded-lg focus:ring focus:ring-blue-300">
  
        <!-- Doctor Selection -->
        <label class="block mb-2 text-gray-700">Select Doctor:</label>
        <select v-model="selectedDoctor" class="w-full p-2 mb-4 border rounded-lg focus:ring focus:ring-blue-300">
          <option value="" disabled>Select a Doctor</option>
          <option v-for="doctor in doctors" :key="doctor.id" :value="doctor.name">{{ doctor.name }} ({{ doctor.department }})</option>
        </select>
  
        <!-- Submit Button -->
        <button 
          @click="scheduleMeeting" 
          :disabled="!selectedDate || !selectedTime || !selectedDoctor"
          class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 disabled:bg-gray-300">
          Send Information
        </button>
      </div>
  
      <!-- Countdown & Join Meeting Button -->
      <div v-else class="text-center bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <p class="text-lg font-semibold text-gray-700">Joining meeting in: <span class="text-blue-600">{{ countdown }}</span></p>
  
        <button 
          :disabled="!meetingReady" 
          @click="joinMeeting" 
          class="w-full mt-4 py-2 text-white rounded-lg"
          :class="meetingReady ? 'bg-blue-500 hover:bg-green-600' : 'bg-gray-300'">
          {{ meetingReady ? 'Join Meeting Now!' : 'Waiting for Meeting Time...' }}
        </button>
      </div>
    </div>
  </template>
<script>
export default {
  data() {
    return {
      selectedDate: "",
      selectedTime: "",
      selectedDoctor: "",
      meetingScheduled: false,
      countdown: "",
      meetingReady: false,
      doctors: [
        { id: 1, name: "Dr. Jane Doe", department: "Cardiology" },
        { id: 2, name: "Dr. John Smith", department: "Dermatology" },
      ],
      intervalId: null,
    };
  },
  methods: {
    scheduleMeeting() {
      if (!this.selectedDate || !this.selectedTime || !this.selectedDoctor) return;

      this.meetingScheduled = true;
      this.startCountdown();
    },

    startCountdown() {
      const meetingTime = new Date(`${this.selectedDate}T${this.selectedTime}:00`).getTime();
      this.intervalId = setInterval(() => {
        const now = new Date().getTime();
        const timeLeft = meetingTime - now;

        if (timeLeft <= 0) {
          clearInterval(this.intervalId);
          this.meetingReady = true;
          this.countdown = "00:00";
        } else {
          const minutes = Math.floor(timeLeft / (1000 * 60));
          const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
          this.countdown = `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;
        }
      }, 1000);
    },

    joinMeeting() {
      if (this.meetingReady) {
        window.location.href = "https://meet.google.com/ubj-ascs-kmr"; // Replace with dynamic meeting link
      }
    },
  },
};
</script>
  