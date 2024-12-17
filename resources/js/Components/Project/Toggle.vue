<script setup lang="ts">
defineProps<{
  view: string
}>()
</script>

<template>
  <div class="flex items-center">
    <button
      :class="{'bg-blue-500 text-white': view === 'card', 'bg-gray-100 text-gray-900': view !== 'card'}"
      class="px-4 py-2 rounded-l-md"
      @click="$emit('updateView', 'card')">
      Card View
    </button>
    <button
      :class="{'bg-blue-500 text-white': view === 'table', 'bg-gray-100 text-gray-900': view !== 'table'}"
      class="px-4 py-2 rounded-r-md"
      @click="$emit('updateView', 'table')">
      Table View
    </button>
  </div>
</template>

<style scoped>
button {
  transition: all 0.2s;
}
</style>
