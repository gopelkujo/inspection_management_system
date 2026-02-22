<template>
  <div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
      <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
        Create New Inspection
      </h2>
    </div>

    <form @submit.prevent="submitInspection" class="space-y-6">
      <!-- Section 1: Header Info -->
      <BaseCard>
        <template #header>
          <h3 class="text-lg font-medium text-gray-900">General Information</h3>
        </template>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <BaseSelect
            v-model="form.type"
            label="Service Type / Scope"
            :required="true"
            placeholder="Select Type"
          >
            <option
              v-for="type in inspectionTypes"
              :key="type.code"
              :value="type.code"
            >
              {{ type.label }}
            </option>
          </BaseSelect>
        </div>
      </BaseCard>

      <!-- Section 2: Order Information (Items) -->
      <div class="space-y-4">
        <div class="flex justify-between items-center">
          <h3 class="text-lg font-medium text-gray-900">
            Order Information (Items)
          </h3>
          <BaseButton
            type="button"
            variant="secondary"
            size="sm"
            @click="addItem"
          >
            + Add Item
          </BaseButton>
        </div>

        <div
          v-if="form.items.length === 0"
          class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-12 w-12 mx-auto text-gray-400 mb-2"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
            />
          </svg>
          <p class="text-gray-500">No items added yet.</p>
          <BaseButton
            type="button"
            variant="primary"
            size="sm"
            class="mt-4"
            @click="addItem"
          >
            Add First Item
          </BaseButton>
        </div>

        <div v-else class="space-y-4">
          <BaseCard
            v-for="(item, index) in form.items"
            :key="index"
            class="relative transition-all duration-200 hover:shadow-md"
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
      </div>

      <!-- Actions -->
      <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
        <router-link to="/">
          <BaseButton variant="secondary">Cancel</BaseButton>
        </router-link>
        <BaseButton type="submit" variant="primary">
          Submit Inspection
        </BaseButton>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import BaseCard from '../components/ui/BaseCard.vue';
import BaseInput from '../components/ui/BaseInput.vue';
import BaseSelect from '../components/ui/BaseSelect.vue';
import BaseButton from '../components/ui/BaseButton.vue';

const router = useRouter();
const store = useStore();

const inspectionTypes = computed(
  () => store.getters['masterData/inspectionTypes'],
);
const allocations = computed(() => store.getters['masterData/allocations']);
const owners = computed(() => store.getters['masterData/owners']);
const conditions = computed(() => store.getters['masterData/conditions']);

const form = ref({
  type: '',
  items: [],
});

const addItem = () => {
  form.value.items.push({
    lot_no: '',
    allocation: '',
    owner: '',
    condition: '',
    qty_required: 1,
    qty_available: 0,
  });
};

const removeItem = (index) => {
  form.value.items.splice(index, 1);
};

const submitInspection = async () => {
  if (form.value.items.length === 0) {
    await store.dispatch('ui/alert', {
      title: 'Validation Error',
      message: 'Please add at least one item.',
      type: 'warning',
    });
    return;
  }

  store.dispatch('ui/startLoading', 'Creating inspection...');
  try {
    await store.dispatch('inspection/createInspection', form.value);
    store.dispatch('ui/stopLoading');

    await store.dispatch('ui/alert', {
      title: 'Success',
      message: 'Inspection created successfully.',
      type: 'success',
    });
    router.push('/');
  } catch (error) {
    store.dispatch('ui/stopLoading');
    await store.dispatch('ui/alert', {
      title: 'Error',
      message:
        'Failed to create inspection: ' +
        (error.response?.data?.message || error.message),
      type: 'danger',
    });
  }
};
</script>
