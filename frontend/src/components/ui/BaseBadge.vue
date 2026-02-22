<template>
  <span
    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
    :class="variantClasses[variant]"
  >
    <slot></slot>
  </span>
</template>

<script setup>
const props = defineProps({
  variant: {
    type: String,
    default: 'neutral',
    validator: (value) =>
      ['neutral', 'success', 'warning', 'info', 'danger'].includes(value),
  },
});

const variantClasses = {
  neutral: 'bg-slate-100 text-slate-800',
  success: 'bg-emerald-100 text-emerald-800',
  warning: 'bg-amber-100 text-amber-800',
  info: 'bg-blue-100 text-blue-800',
  danger: 'bg-red-100 text-red-800',
};
</script>
