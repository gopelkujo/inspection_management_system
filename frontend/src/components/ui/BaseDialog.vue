<template>
  <Transition name="dialog">
    <div
      v-if="isOpen"
      class="fixed inset-0 z-100 overflow-y-auto"
      aria-labelledby="modal-title"
      role="dialog"
      aria-modal="true"
    >
      <!-- Background backdrop -->
      <div
        class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0"
      >
        <div
          class="fixed inset-0 bg-gray-500/75 transition-opacity"
          aria-hidden="true"
          @click="handleBackdropClick"
        ></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span
          class="hidden sm:inline-block sm:h-screen sm:align-middle"
          aria-hidden="true"
          >&#8203;</span
        >

        <div
          class="relative inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
        >
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div
                class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10"
                :class="iconBgClass"
              >
                <svg
                  v-if="type === 'warning'"
                  class="h-6 w-6 text-amber-600"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  aria-hidden="true"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                  />
                </svg>
                <svg
                  v-else-if="type === 'danger'"
                  class="h-6 w-6 text-red-600"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"
                  />
                </svg>
                <svg
                  v-else-if="type === 'success'"
                  class="h-6 w-6 text-emerald-600"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M4.5 12.75l6 6 9-13.5"
                  />
                </svg>
                <svg
                  v-else
                  class="h-6 w-6 text-blue-600"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"
                  />
                </svg>
              </div>
              <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                <h3
                  class="text-base font-semibold leading-6 text-gray-900"
                  id="modal-title"
                >
                  {{ title }}
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    {{ message }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
            <button
              type="button"
              class="inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto transition-colors"
              :class="confirmButtonClass"
              @click="onConfirm"
            >
              {{ confirmText }}
            </button>
            <button
              v-if="showCancel"
              type="button"
              class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors"
              @click="onCancel"
            >
              {{ cancelText }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  isOpen: Boolean,
  title: String,
  message: String,
  confirmText: String,
  cancelText: String,
  type: String, // info, success, warning, danger
  showCancel: Boolean,
});

const emit = defineEmits(['confirm', 'cancel']);

const onConfirm = () => emit('confirm');
const onCancel = () => emit('cancel');
const handleBackdropClick = () => {
  if (!props.showCancel) {
    onConfirm(); // For alerts, clicking backdrop acts as OK
  } else {
    onCancel(); // For confirms, acts as Cancel
  }
};

const iconBgClass = computed(() => {
  switch (props.type) {
    case 'warning':
      return 'bg-amber-100';
    case 'danger':
      return 'bg-red-100';
    case 'success':
      return 'bg-emerald-100';
    default:
      return 'bg-blue-100';
  }
});

const confirmButtonClass = computed(() => {
  switch (props.type) {
    case 'warning':
      return 'bg-amber-600 hover:bg-amber-500';
    case 'danger':
      return 'bg-red-600 hover:bg-red-500';
    case 'success':
      return 'bg-emerald-600 hover:bg-emerald-500';
    default:
      return 'bg-indigo-600 hover:bg-indigo-500';
  }
});
</script>
