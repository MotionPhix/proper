<script setup>
import autosize from 'autosize/dist/autosize'
import { onBeforeUnmount, onMounted, ref } from 'vue'

const props = defineProps({
  modelValue: String,
})

const emit = defineEmits(['update:modelValue'])

const textarea = ref()

onMounted(() => autosize(textarea.value))

onBeforeUnmount(() => autosize.destroy(textarea.value))
</script>

<template>
  <textarea
    ref="textarea"
    :value="modelValue"
    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-lime-600 focus:border-lime-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-lime-500 dark:focus:border-lime-500"
    @input="emit('update:modelValue', $event.target.value)"
  />
</template>
