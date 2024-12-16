<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import type { Links } from '@/types'

const props = defineProps<Props>()

interface Props {
  links?: Links[]
}

const links = ref(props.links)
</script>

<template>
  <div v-if="links.length > 3">
    <div class="flex flex-wrap -mb-1">
      <template v-for="(link, key) in links">
        <div
          v-if="link.url === null" :key="key"
          class="px-4 py-3 mb-1 mr-1 text-sm leading-4 text-gray-400 border rounded dark:border-gray-700" v-html="link.label"
        />

        <Link
          v-else
          :key="`link-${key}`"
          preserve-scroll
          class="px-4 py-3 mb-1 mr-1 text-sm leading-4 border rounded dark:border-gray-700 focus:text-indigo-500 hover:bg-white focus:border-indigo-500"
          :class="{ 'bg-white': link.active }"
          :href="link.url"
          v-html="link.label"
        />
      </template>
    </div>
  </div>
</template>
