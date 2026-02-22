<template>
  <Transition name="fade">
    <div
      v-if="active"
      class="fixed inset-0 z-200 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity"
    >
      <div
        class="bg-white rounded-lg shadow-xl p-6 flex flex-col items-center space-y-4"
      >
        <div class="relative w-12 h-12">
          <div
            class="absolute w-full h-full border-4 border-indigo-200 rounded-full"
          ></div>
          <div
            class="absolute w-full h-full border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"
          ></div>
        </div>
        <p class="text-gray-700 font-medium">{{ message }}</p>
      </div>
    </div>
  </Transition>
</template>

<script setup>
defineProps({
  active: Boolean,
  message: String,
});
</script>
