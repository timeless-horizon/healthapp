<template>
  <section class="bg-gray-100 py-20 px-6 lg:px-20">
    <div class="text-center mb-8">
      <h2 class="text-3xl font-extrabold text-gray-800">Meet the Team</h2>
      <p class="mt-2 text-gray-600">Our dedicated professionals</p>
    </div>

    <div class="flex justify-center">
      <div class="relative overflow-hidden w-full max-w-4xl">
        <div class="flex" :class="{ 'transition-transform duration-500 ease-linear': !isResetting }"
          :style="{ transform: `translateX(${currentOffset}px)` }">

          <div v-for="(member, index) in extendedTeam" :key="`member-${index}`" class="flex-shrink-0 text-center"
            :style="{ width: `${slideWidth}px` }">
            <div class="w-32 h-32 mx-auto rounded-full overflow-hidden shadow-lg border-2 border-teal-500">
              <img :src="member.image" :alt="member.name" class="w-full h-full object-cover" />
            </div>
            <h3 class="mt-4 text-lg font-semibold text-gray-800">{{ member.name }}</h3>
            <p class="text-sm text-gray-600">{{ member.position }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "MeetTheTeam",
  data() {
    return {
      team: [
        { name: "David Edu", position: "CO-Founder", image: "/images/david.png" },
        { name: "Donatus Victor", position: "CO-Founder", image: "/images/donatus.png" },
        {
          name: "Edim Michael", position: "Business Development Manager", image: "/images/daniel.png"
        },
        { name: "Williams Undebe", position: "Chief Medical Director", image: "/images/willians.png" },
        { name: "Emediong Moses", position: "Customer Experience Manager", image: "/images/emed.png" },
        { name: "Bright Antak", position: "Social Media Strategist", image: "/images/bright.png" },
        { name: "Ndianabasi Moses", position: "Head Product Design", image: "/images/moses.png" }
      ],
      currentOffset: 0,
      slideWidth: 160,
      interval: null,
      isResetting: false,
    };
  },
  computed: {
    extendedTeam() {
      return [
        ...this.team.slice(-4),
        ...this.team,
        ...this.team.slice(0, 4)
      ];
    },
  },
  mounted() {
    this.currentOffset = -this.slideWidth * 4;
    this.startAutoSlide();
  },
  methods: {
    startAutoSlide() {
      this.interval = setInterval(() => {
        this.isResetting = false;
        this.currentOffset -= this.slideWidth;
        if (this.currentOffset <= -this.slideWidth * (this.team.length + 4)) {
          setTimeout(() => {
            this.isResetting = true;
            this.currentOffset = -this.slideWidth * 4;
          }, 500);
        }
      }, 3000);
    },
  },
  beforeUnmount() {
    clearInterval(this.interval);
  },
};
</script>

<style scoped>
.transition-transform {
  will-change: transform;
}
</style>