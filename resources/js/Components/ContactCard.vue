<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import type { Contact } from '@/types'

const props = defineProps<{
  href: string
  contact: Contact
}>()

const param = route().params.contact

const classes = computed(() =>
  `${route().params.contact == props.contact.id ? 'bg-rose-500/20 dark:bg-blue-200/40' : 'bg-white dark:bg-gray-800'} border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-700 items-center text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 transition duration-150 ease-in-out`,
)

const full_name = computed(() =>
  `${props.contact?.first_name} ${props.contact?.last_name}`,
)
</script>

<template>
  <Link :href="href" :class="classes" preserve-state preserve-scroll as="button" class="px-2 py-3">
    <div class="flex flex-col items-start">
      <p class="text-sm font-semibold text-gray-900 dark:text-white w-full flex justify-between items-center leading-none">
        <span>{{ full_name }}</span>
        <span
          :class="[contact?.status === 'active' ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800']"
          class="tracking-wider rounded-md p-1.5 text-xxs bg-opacity-50 uppercase"
        >
          {{ contact?.status }}
        </span>
      </p>

      <p class="text-sm text-gray-500 dark:text-gray-400">
        {{ contact.company?.name }}
      </p>
    </div>
  </Link>
</template>
