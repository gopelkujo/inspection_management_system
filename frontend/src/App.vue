<template>
  <AppLayout>
    <router-view v-slot="{ Component }">
      <Transition name="fade" mode="out-in">
        <component :is="Component" />
      </Transition>
    </router-view>
  </AppLayout>
  <BaseDialog
    :is-open="dialog.isOpen"
    :title="dialog.title"
    :message="dialog.message"
    :confirm-text="dialog.confirmText"
    :cancel-text="dialog.cancelText"
    :type="dialog.type"
    :show-cancel="dialog.showCancel"
    @confirm="handleConfirm"
    @cancel="handleCancel"
  />
  <BaseLoader :active="loading.active" :message="loading.message" />
</template>

<script setup>
import { onMounted, computed } from "vue";
import { useStore } from "vuex";
import AppLayout from "./components/layout/AppLayout.vue";
import BaseDialog from "./components/ui/BaseDialog.vue";
import BaseLoader from "./components/ui/BaseLoader.vue";

const store = useStore();

const dialog = computed(() => store.state.ui.dialog);
const loading = computed(() => store.state.ui.loading);

const handleConfirm = () => store.dispatch("ui/handleConfirm");
const handleCancel = () => store.dispatch("ui/handleCancel");

onMounted(() => {
  store.dispatch("masterData/fetchMasterData");
});
</script>
