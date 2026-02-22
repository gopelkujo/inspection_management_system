<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
          Inspections
        </h1>
        <p class="text-sm text-gray-500 mt-1">
          Manage and track your quality inspections.
        </p>
      </div>
      <router-link to="/create">
        <BaseButton variant="primary" size="md">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 mr-2"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 4v16m8-8H4"
            />
          </svg>
          Create Inspection
        </BaseButton>
      </router-link>
    </div>

    <BaseCard class="min-h-[600px]">
      <template #header>
        <div class="flex space-x-1">
          <button
            v-for="tab in tabs"
            :key="tab.name"
            @click="currentTab = tab.name"
            class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-200"
            :class="[
              currentTab === tab.name
                ? 'bg-indigo-50 text-indigo-700'
                : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50',
            ]"
          >
            {{ tab.label }}
          </button>
        </div>
      </template>

      <!-- List -->
      <Transition name="tab-slide" mode="out-in">
        <div v-if="loading" key="loading" class="flex justify-center py-12">
          <div
            class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"
          ></div>
        </div>
        <div
          v-else-if="filteredInspections.length === 0"
          key="empty"
          class="text-center py-12"
        >
          <div
            class="bg-gray-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-8 w-8 text-gray-400"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
              />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900">
            No inspections found
          </h3>
          <p class="text-gray-500 mt-1">
            There are no inspections in
            {{ currentTab.toLowerCase().replace("_", " ") }} status.
          </p>
        </div>
        <div v-else :key="currentTab" class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th
                  class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
                >
                  ID
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
                >
                  Type
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
                >
                  Status
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
                >
                  Created At
                </th>
                <th
                  class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              <tr
                v-for="inspection in filteredInspections"
                :key="inspection.id"
                class="hover:bg-gray-50 transition-colors"
              >
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                >
                  #{{ inspection.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ getInspectionLabel(inspection.type) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <BaseBadge :variant="getStatusVariant(inspection.status)">
                    {{ inspection.status }}
                  </BaseBadge>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(inspection.created_at) }}
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                >
                  <router-link
                    :to="`/inspection/${inspection.id}`"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    View
                  </router-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </Transition>
    </BaseCard>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import BaseCard from "../components/ui/BaseCard.vue";
import BaseButton from "../components/ui/BaseButton.vue";
import BaseBadge from "../components/ui/BaseBadge.vue";

const store = useStore();

const tabs = [
  { name: "OPEN", label: "Open" },
  { name: "FOR_REVIEW", label: "For Review" },
  { name: "COMPLETED", label: "Completed" },
];

const currentTab = ref("OPEN");

onMounted(() => {
  store.dispatch("inspection/fetchAllInspections");
});

const loading = computed(() => store.state.inspection.loading);

const filteredInspections = computed(() => {
  return store.state.inspection.inspections.filter(
    (i) => i.status === currentTab.value,
  );
});

const getInspectionLabel = (code) => {
  const type = store.getters["masterData/inspectionTypes"].find(
    (t) => t.code === code,
  );
  return type ? type.label : code;
};

const getStatusVariant = (status) => {
  switch (status) {
    case "OPEN":
      return "info";
    case "FOR_REVIEW":
      return "warning";
    case "COMPLETED":
      return "success";
    default:
      return "neutral";
  }
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString(undefined, {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};
</script>
