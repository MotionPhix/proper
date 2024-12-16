<script setup lang="ts">
import { format } from 'date-fns'
import { Head } from '@inertiajs/vue3'
import trashBold from '@iconify-icons/ph/trash-bold'
import pencilSimpleBold from '@iconify-icons/ph/pencil-simple-bold'
import checkSquare from '@iconify-icons/bx/check-square'
import infoBold from '@iconify-icons/ph/info-bold'
import { Icon, addIcon } from '@iconify/vue/dist/offline.js'
import Nav from '@/Shared/Nav.vue'
import PageHeader from '@/Components/PageHeader.vue'
import type { TaskApiResponse } from '@/types'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps<{
  tasks: TaskApiResponse
}>()

addIcon('ph-info-bold', infoBold)
addIcon('ph-pencil-simple-bold', pencilSimpleBold)
addIcon('ph-trash-bold', trashBold)
addIcon('bx-check-square', checkSquare)

// Format due date
// function formatDate(dateString) {
//   const date = new Date(dateString)
//   return format(date, 'dd MMM yyyy')
// }

// Format due time
// function formatTime(dateString) {
//   const date = new Date(dateString)
//   return format(date, 'HH:mm')
// }

function statusClasses(status) {
  switch (status) {
    case 'new':
      return 'bg-blue-100 text-blue-800 px-2 py-1 rounded-lg text-xxs font-medium'
    case 'in progress':
      return 'bg-yellow-100 text-yellow-800 px-2 py-1 rounded-lg text-xxs font-medium'
    case 'done':
      return 'bg-green-100 text-green-800 px-2 py-1 rounded-lg text-xxs font-medium'
    case 'blocked':
      return 'bg-red-100 text-red-800 px-2 py-1 rounded-lg text-xxs font-medium'
    default:
      return 'bg-gray-100 text-gray-800 px-2 py-1 rounded-lg text-xxs font-medium'
  }
}

function formatDate(dateString: string) {
  const date = new Date(dateString)
  return format(date, 'dd MMM, yyyy HH:mm')
}

defineOptions({
  layout: AuthenticatedLayout,
})
</script>

<!-- <template>
  <div class="p-4">
    <div class="mb-4 text-2xl font-bold">
      Tasks
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <template v-for="task in tasks.data" :key="task.id">
        <div class="p-4 bg-white rounded-lg shadow-md">
          <div class="mb-2 text-2xl font-thin">
            {{ task.title }}
          </div>
          <div class="mb-2 font-semibold text-gray-500">
            {{ task.project.name }}
          </div>
          <div class="mb-2 text-sm text-gray-500">
            {{ formatDate(task.end_date) }} {{ formatTime(task.end_date) }}
          </div>
          <div class="text-sm text-gray-500 line-clamp-2">
            {{ task.description }}
          </div>
        </div>
      </template>
    </div>
  </div>
</template> -->

<template>
  <Head title="Explore Tasks" />

  <PageHeader>
    <h2 class="flex items-center gap-2 text-xl font-semibold leading-tight text-gray-900 dark:text-white">
      Explore tasks <span class="text-gray-400 dark:text-gray-600">({{ tasks.total ?? 0 }})</span>
    </h2>

    <!-- <Link as="button" :href="route('projects.index', 'modal')" class="inline-flex items-center gap-2 px-3 py-2 ml-6 font-semibold transition duration-300 rounded-md dark:text-slate-300 bg-slate-100 dark:bg-slate-800 dark:hover:text-slate-900 dark:hover:bg-slate-500 hover:bg-gray-200">
      <PlusIcon class="w-4 h-4" />
      <span>Create task</span>
    </Link> -->

    <span class="flex-1" />

    <section v-if="tasks.total" class="px-6 py-2 rounded-lg bg-slate-100 dark:bg-slate-800">
      <Nav :pagination="tasks" model-type="tasks" />
    </section>
  </PageHeader>

  <section class="py-12">
    <div class="mx-auto overflow-hidden shadow-sm max-w-7xl sm:px-6 lg:px-8 sm:rounded-lg">
      <div v-if="tasks.total" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        <div v-for="task in tasks.data" :key="task.id" class="p-4 bg-white rounded-md shadow-md">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">
              {{ task.title }}
            </h3>
            <span :class="statusClasses(task.status)" class="capitalize">{{ task.status }}</span>
          </div>

          <div class="flex flex-col h-full mt-2">
            <p class="text-sm text-gray-500 line-clamp-2">
              {{ task.description }}
            </p>
            <div class="flex items-center justify-between mt-4">
              <div class="flex flex-col">
                <span class="text-xs font-medium text-gray-500">Project:</span>
                <span class="text-sm font-medium text-gray-900">{{ task.project.name }}</span>
              </div>

              <div class="flex flex-col">
                <span class="text-xs font-medium text-gray-500">Due Date:</span>
                <span class="text-sm font-medium text-gray-900">{{ formatDate(task.end_date) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="flex flex-col items-center justify-center p-6 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center w-full gap-2 py-12 mx-auto max-w-7xl sm:px-6 lg:px-8">
          <Icon icon="bx-check-square" height="80" class="text-gray-400" />

          <h2 class="text-lg font-semibold leading-none text-center text-gray-500">
            Tasks not found!
          </h2>

          <p class="text-sm text-center text-gray-500">
            You don't have tasks yet.
          </p>

          <!-- <div>
            <Link
              as="button"
              class="flex gap-2 items-center text-gray-500 border-gray-500 border hover:border-gray-900 rounded-lg dark:border-slate-600 dark:text-gray-500 font-semibold my-4 px-3 py-1.5 dark:hover:text-gray-400 dark:hover:border-gray-400 hover:text-gray-900 transition duration-300"
              :href="route('tasks.index', 'modal')"
            >
              <Icon icon="ph-plus-bold" width="24" />
              <span>Add task</span>
            </Link>
          </div> -->
        </div>
      </div>
    </div>
  </section>
</template>
