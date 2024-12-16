<script setup lang="ts">
import { computed } from 'vue'

import {
  Listbox,
  ListboxButton,
  ListboxOption,
  ListboxOptions,
} from '@headlessui/vue'
import { IconCheck, IconSelector } from '@tabler/icons-vue'

const props = defineProps({
  options: Array,

  modelValue: [String, Number, Array],

  multiple: Boolean,

  error: String,

  placeholder: {
    type: String,
    default: 'Select option',
  },
})

defineEmits(['update:modelValue'])

// const label = computed(() => props.options.find(option => option.value === props.modelValue)?.label)
const label = computed(() => props.options.filter((option) => {
  if (Array.isArray(props.modelValue))
    return props.modelValue.includes(option.value)

  return option.value === props.modelValue
}).map(option => option.label).join(', '),
)
</script>

<template>
  <Listbox
    :model-value="modelValue"
    :multiple="props.multiple"
    @update:model-value="value => $emit('update:modelValue', value)"
  >
    <div class="relative mt-1">
      <ListboxButton
        class="relative w-full cursor-default rounded-lg bg-white dark:bg-gray-700 dark:text-gray-300 py-3 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
      >
        <span v-if="label" class="block truncate">{{ label }}</span>
        <span v-else class="block dark:text-gray-400 text-gray-600 truncate">
          {{ props.placeholder }}
        </span>
        <span
          class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
        >
          <IconSelector
            class="h-5 w-5 text-gray-400"
            aria-hidden="true"
          />
        </span>
      </ListboxButton>

      <transition
        leave-active-class="transition duration-100 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <ListboxOptions
          class="z-10 absolute  mt-1 max-h-40 w-full divide-y dark:divide-gray-800 overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
        >
          <ListboxOption
            v-for="option in options"
            v-slot="{ active, selected }"
            :key="option.label"
            :value="option.value"
            as="template"
          >
            <li
              class="relative cursor-default select-none py-2 pl-10 pr-4" :class="[
                active ? 'bg-amber-100 text-amber-900' : 'text-gray-900 dark:text-gray-300',
              ]"
            >
              <span
                class="block truncate" :class="[
                  selected ? 'font-semibold' : 'font-normal',
                ]"
              >{{ option.label }}</span>
              <span
                v-if="selected"
                class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
              >
                <IconCheck class="h-5 w-5" aria-hidden="true" />
              </span>
            </li>
          </ListboxOption>
        </ListboxOptions>
      </transition>

      <div v-if="error" class="text-sm text-red-500 mt-1">
        {{ error }}
      </div>
    </div>
  </Listbox>
</template>
