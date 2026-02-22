<template>
  <div v-if="inspection" class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center space-x-4">
      <router-link
        to="/"
        class="text-gray-500 hover:text-gray-700 transition-colors"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M10 19l-7-7m0 0l7-7m-7 7h18"
          />
        </svg>
      </router-link>
      <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
        Inspection Details
      </h2>
    </div>

    <!-- Header -->
    <BaseCard>
      <div
        class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0"
      >
        <div>
          <div class="flex items-center space-x-3">
            <h3 class="text-xl font-bold text-gray-900">
              #{{ inspection.id }}
            </h3>
            <BaseBadge :variant="getStatusVariant(inspection.status)">
              {{ inspection.status }}
            </BaseBadge>
          </div>
          <div
            class="mt-2 text-sm text-gray-500 flex flex-col sm:flex-row sm:space-x-6"
          >
            <span class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 mr-1.5 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                />
              </svg>
              {{ getInspectionLabel(inspection.type) }}
            </span>
            <span class="flex items-center mt-1 sm:mt-0">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 mr-1.5 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
              {{ formatDate(inspection.created_at) }}
            </span>
          </div>
        </div>

        <div class="flex space-x-3">
          <BaseButton
            v-if="inspection.status === 'OPEN'"
            @click="updateStatus('FOR_REVIEW')"
            variant="warning"
            size="sm"
          >
            Submit for Review
          </BaseButton>

          <BaseButton
            v-if="inspection.status === 'FOR_REVIEW'"
            @click="updateStatus('COMPLETED')"
            variant="success"
            size="sm"
          >
            Approve & Complete
          </BaseButton>
        </div>
      </div>
    </BaseCard>

    <!-- Items List -->
    <BaseCard>
      <template #header>
        <div
          class="flex flex-col sm:flex-row justify-between items-start sm:items-center w-full space-y-2 sm:space-y-0"
        >
          <h3 class="text-lg font-medium text-gray-900">Inspection Items</h3>
          <div class="flex space-x-2">
            <BaseButton
              v-if="inspection?.status === 'OPEN' && !isEditing"
              @click="startEditing"
              variant="secondary"
              size="sm"
            >
              Edit Items
            </BaseButton>
            <template v-else-if="isEditing">
              <BaseButton @click="addItem" variant="secondary" size="sm">
                + Add Item
              </BaseButton>
              <BaseButton @click="cancelEditing" variant="secondary" size="sm">
                Cancel
              </BaseButton>
              <BaseButton @click="saveItems" variant="primary" size="sm">
                Save Items
              </BaseButton>
            </template>
          </div>
        </div>
      </template>
      <div v-if="!isEditing" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50/50">
            <tr>
              <th
                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
              >
                Lot No
              </th>
              <th
                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
              >
                Allocation
              </th>
              <th
                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
              >
                Owner
              </th>
              <th
                class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
              >
                Condition
              </th>
              <th
                class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider"
              >
                Qty Req
              </th>
              <th
                class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider"
              >
                Qty Avail
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white">
            <tr
              v-for="item in inspection.items"
              :key="item.id"
              class="hover:bg-gray-50 transition-colors"
            >
              <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                {{ item.lot_no }}
              </td>
              <td class="px-4 py-3 text-sm text-gray-500">
                {{ getMasterLabel(item.allocation, 'allocations') }}
              </td>
              <td class="px-4 py-3 text-sm text-gray-500">
                {{ getMasterLabel(item.owner, 'owners') }}
              </td>
              <td class="px-4 py-3 text-sm text-gray-500">
                {{ getMasterLabel(item.condition, 'conditions') }}
              </td>
              <td
                class="px-4 py-3 text-sm text-right text-gray-900 font-medium"
              >
                {{ item.qty_required }}
              </td>
              <td class="px-4 py-3 text-sm text-right text-gray-500">
                {{ item.qty_available }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-else class="space-y-4 p-4 bg-gray-50/50">
        <div
          v-if="editForm.items.length === 0"
          class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300"
        >
          <p class="text-gray-500">No items left. Please add an item.</p>
          <BaseButton
            type="button"
            variant="primary"
            size="sm"
            class="mt-4"
            @click="addItem"
          >
            Add Item
          </BaseButton>
        </div>

        <BaseCard
          v-for="(item, index) in editForm.items"
          :key="index"
          class="relative transition-all duration-200 hover:shadow-md bg-white"
        >
          <button
            type="button"
            @click="removeItem(index)"
            class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors"
            title="Remove Item"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                clip-rule="evenodd"
              />
            </svg>
          </button>
          <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-2"
          >
            <BaseInput
              v-model="item.lot_no"
              label="Lot No"
              placeholder="Enter Lot No"
              required
            />
            <BaseSelect
              v-model="item.allocation"
              label="Allocation"
              placeholder="Select Allocation"
            >
              <option
                v-for="opt in allocations"
                :key="opt.code"
                :value="opt.code"
              >
                {{ opt.label }}
              </option>
            </BaseSelect>
            <BaseSelect
              v-model="item.owner"
              label="Owner"
              placeholder="Select Owner"
            >
              <option v-for="opt in owners" :key="opt.code" :value="opt.code">
                {{ opt.label }}
              </option>
            </BaseSelect>
            <BaseSelect
              v-model="item.condition"
              label="Condition"
              placeholder="Select Condition"
            >
              <option
                v-for="opt in conditions"
                :key="opt.code"
                :value="opt.code"
              >
                {{ opt.label }}
              </option>
            </BaseSelect>
            <BaseInput
              v-model.number="item.qty_required"
              label="Qty Required"
              type="number"
              min="1"
              required
            />
            <BaseInput
              v-model.number="item.qty_available"
              label="Qty Available"
              type="number"
              min="0"
            />
          </div>
        </BaseCard>
      </div>
    </BaseCard>
  </div>
  <div v-else class="flex justify-center py-20">
    <div
      class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"
    ></div>
  </div>
</template>

<script setup>
import { onMounted, computed, ref } from 'vue';
import { useStore } from 'vuex';
import BaseCard from '../components/ui/BaseCard.vue';
import BaseBadge from '../components/ui/BaseBadge.vue';
import BaseButton from '../components/ui/BaseButton.vue';
import BaseInput from '../components/ui/BaseInput.vue';
import BaseSelect from '../components/ui/BaseSelect.vue';

const props = defineProps(['id']);
const store = useStore();

const inspection = computed(() => store.state.inspection.currentInspection);

const allocations = computed(() => store.getters['masterData/allocations']);
const owners = computed(() => store.getters['masterData/owners']);
const conditions = computed(() => store.getters['masterData/conditions']);

const isEditing = ref(false);
const editForm = ref({ items: [] });

onMounted(() => {
  store.dispatch('inspection/fetchInspection', props.id);
});

const startEditing = () => {
  if (inspection.value && inspection.value.items) {
    editForm.value.items = JSON.parse(JSON.stringify(inspection.value.items));
  }
  isEditing.value = true;
};

const cancelEditing = () => {
  isEditing.value = false;
  editForm.value.items = [];
};

const addItem = () => {
  editForm.value.items.push({
    lot_no: '',
    allocation: '',
    owner: '',
    condition: '',
    qty_required: 1,
    qty_available: 0,
  });
};

const removeItem = (index) => {
  editForm.value.items.splice(index, 1);
};

const saveItems = async () => {
  if (editForm.value.items.length === 0) {
    await store.dispatch('ui/alert', {
      title: 'Validation Error',
      message: 'Please add at least one item.',
      type: 'warning',
    });
    return;
  }

  store.dispatch('ui/startLoading', 'Saving items...');
  try {
    await store.dispatch('inspection/updateInspection', {
      id: props.id,
      payload: { items: editForm.value.items },
    });
    await store.dispatch('inspection/fetchInspection', props.id); // Refresh data
    isEditing.value = false;
    store.dispatch('ui/stopLoading');
    await store.dispatch('ui/alert', {
      title: 'Success',
      message: 'Items updated successfully',
      type: 'success',
    });
  } catch (error) {
    store.dispatch('ui/stopLoading');
    await store.dispatch('ui/alert', {
      title: 'Error',
      message:
        'Failed to save items: ' +
        (error.response?.data?.message || error.message),
      type: 'danger',
    });
  }
};

const updateStatus = async (status) => {
  const confirmed = await store.dispatch('ui/confirm', {
    title: 'Update Status',
    message: `Are you sure you want to change status to ${status}?`,
    confirmText: 'Yes, Update',
    type: 'warning',
  });

  if (!confirmed) return;

  store.dispatch('ui/startLoading', 'Updating status...');
  try {
    await store.dispatch('inspection/updateInspection', {
      id: props.id,
      payload: { status },
    });
    await store.dispatch('inspection/fetchInspection', props.id); // Refresh data
    store.dispatch('ui/stopLoading');
    await store.dispatch('ui/alert', {
      title: 'Success',
      message: 'Status updated successfully',
      type: 'success',
    });
  } catch (error) {
    store.dispatch('ui/stopLoading');
    await store.dispatch('ui/alert', {
      title: 'Error',
      message: 'Failed to update status',
      type: 'danger',
    });
  }
};

const getInspectionLabel = (code) => {
  const type = store.getters['masterData/inspectionTypes'].find(
    (t) => t.code === code,
  );
  return type ? type.label : code;
};

const getMasterLabel = (code, type) => {
  if (!code) return '-';
  const list = store.getters[`masterData/${type}`] || [];
  const item = list.find((t) => t.code === code);
  return item ? item.label : code;
};

const getStatusVariant = (status) => {
  switch (status) {
    case 'OPEN':
      return 'info';
    case 'FOR_REVIEW':
      return 'warning';
    case 'COMPLETED':
      return 'success';
    default:
      return 'neutral';
  }
};

const formatDate = (dateString) => {
  return (
    new Date(dateString).toLocaleDateString(undefined, {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
    }) +
    ' ' +
    new Date(dateString).toLocaleTimeString(undefined, {
      hour: '2-digit',
      minute: '2-digit',
    })
  );
};
</script>
