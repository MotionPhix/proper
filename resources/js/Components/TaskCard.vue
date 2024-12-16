<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { CalendarDaysIcon } from '@heroicons/vue/24/outline'
import { computed } from 'vue'
import type { Task } from '@/types'

const props = defineProps<{
  task: Task
  icon: string
}>()

const iconComponent = computed(() => {
  const icons = {
    calendar: CalendarDaysIcon,
  }

  return icons[props.icon]
})
</script>

<template>
  <div
    class="p-3 rounded-lg shadow group transition duration-300 ease-in-out"
  >
    <div class="mb-2 flex flex-col justify-between gap-3">
      <div class="text-gray-600 mb-1 flex justify-between">
        <p class="flex gap-2 items-center">
          <component :is="iconComponent" class="w-4" />

          <span class="text-sm">
          <!-- End{{ task.project.end_date->lt(now()) ? 'ed' : 's' }} {{ $task->project->end_date->format('j F, y') }} -->
          </span>
        </p>

        <Link href="{{ route('tasks.show', $task) }}" as="button" preserve-scroll>
          <!-- <x-phosphor-arrow-square-out-bold class="w-4 group-hover:text-rose transition duration-300 ease-in-out" /> -->
          Link out
        </Link>
      </div>

      <p class="text-2xl text-gray-500 font-thin leading-none">
        {{ task.title }}
      </p>

      <div class="flex justify-end gap-2 font-semibold">
        <span>
        <!-- <x-phosphor-user-bold class="w-4 text-gray-400" /> -->
        </span>
        <span class="text-xs text-gray-400">
          {{ task.user?.name }}
        </span>
      </div>
    </div>
  </div>
</template>
