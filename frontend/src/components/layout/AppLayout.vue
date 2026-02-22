<template>
  <div class="min-h-screen bg-slate-50 font-sans text-slate-900 flex flex-col">
    <!-- Top Navigation -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="shrink-0 flex items-center">
              <h1 class="text-xl font-bold tracking-tight text-slate-900">
                Inspect<span class="text-indigo-600">Pro</span>
              </h1>
            </div>
            <nav class="hidden sm:ml-8 sm:flex sm:space-x-8">
              <router-link
                to="/"
                class="inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium transition-colors"
                active-class="border-indigo-500 text-gray-900"
                :class="
                  $route.path === '/'
                    ? 'border-indigo-500 text-gray-900'
                    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                "
              >
                Inspections
              </router-link>
              <router-link
                to="/create"
                class="inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium transition-colors"
                active-class="border-indigo-500 text-gray-900"
                :class="
                  $route.path === '/create'
                    ? 'border-indigo-500 text-gray-900'
                    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                "
              >
                New Inspection
              </router-link>
            </nav>
          </div>

          <!-- Mobile menu button -->
          <div class="-mr-2 flex items-center sm:hidden">
            <button
              @click="isMobileMenuOpen = !isMobileMenuOpen"
              class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
            >
              <span class="sr-only">Open main menu</span>
              <svg
                v-if="!isMobileMenuOpen"
                class="block h-6 w-6"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                aria-hidden="true"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
              </svg>
              <svg
                v-else
                class="block h-6 w-6"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                aria-hidden="true"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile menu -->
      <div
        v-if="isMobileMenuOpen"
        class="sm:hidden bg-white border-b border-gray-200"
      >
        <div class="pt-2 pb-3 space-y-1">
          <router-link
            to="/"
            class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
            active-class="bg-indigo-50 border-indigo-500 text-indigo-700"
            :class="
              $route.path === '/'
                ? 'bg-indigo-50 border-indigo-500 text-indigo-700'
                : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700'
            "
            @click="isMobileMenuOpen = false"
          >
            Inspections
          </router-link>
          <router-link
            to="/create"
            class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
            active-class="bg-indigo-50 border-indigo-500 text-indigo-700"
            :class="
              $route.path === '/create'
                ? 'bg-indigo-50 border-indigo-500 text-indigo-700'
                : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700'
            "
            @click="isMobileMenuOpen = false"
          >
            New Inspection
          </router-link>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 py-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <slot></slot>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const isMobileMenuOpen = ref(false);
</script>
