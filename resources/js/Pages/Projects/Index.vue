<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { formatTimeAgo } from '@vueuse/core'
import type { Company, Project, ProjectApiResponse } from '@/types'
import PageHeader from '@/Components/PageHeader.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Nav from '@/Shared/Nav.vue'
import { IconList, IconPlus, IconTrash } from '@tabler/icons-vue'
import {Button} from '@/Components/ui/button'

defineProps<{
  project?: Project
  projects: ProjectApiResponse
  companies?: Array<Company>
}>()

function formatDate(dateString: string) {
  const date = new Date(dateString)
  return formatTimeAgo(date /* , 'dd MMM, yyyy HH:mm' */)
  // return format(date, 'dd MMM, yyyy HH:mm')
}

defineOptions({
  layout: AuthenticatedLayout,
})

function stringToColor(str: string) {
  let hash = 0
  for (let i = 0; i < str.length; i++)
    hash = str.charCodeAt(i) + ((hash << 5) - hash)

  const c = (hash & 0x00FFFFFF).toString(16).toUpperCase()
  return '00000'.substring(0, 6 - c.length) + c
}

function getProjectColor(name: string) {
  const color = stringToColor(name)
  return `#${color}`
}
</script>

<template>
  <Head title="Explore Projects" />

  <PageHeader>
    <h2 class="flex items-center gap-2 text-xl font-semibold leading-tight text-gray-900 dark:text-white">
      Explore Projects <span class="text-gray-400 dark:text-gray-600">({{ projects.total }})</span>
    </h2>

    <Link
      v-if="projects.total" as="button"
      :href="route('projects.create')"
      class="inline-flex items-center gap-2 px-3 py-2 ml-6 font-semibold transition duration-300 rounded-md dark:text-slate-300 bg-slate-100 dark:bg-slate-800 dark:hover:text-slate-900 dark:hover:bg-slate-500 hover:bg-gray-200">
      <IconPlus class="w-4 h-4" />
      <span>Create project</span>
    </Link>

    <span class="flex-1" />

    <section v-if="projects.total" class="px-6 py-2 rounded-lg bg-slate-100 dark:bg-slate-800">
      <Nav :pagination="projects" model-type="projects" />
    </section>
  </PageHeader>

  <section class="h-full py-12">

    <div class="mx-auto flex gap-8 overflow-hidden shadow-sm max-w-7xl sm:px-6 lg:px-8">

      <section class="max-w-80 mx-auto h-[80vh] overflow-y-auto scrollbar-none">

        <ul v-if="projects.total" class="grid grid-cols-1 gap-6">
          <Link
            v-for="project in projects.data" :key="project.id"
            as="li" class="relative flex-shrink-0 overflow-hidden transition duration-500 bg-orange-500 rounded-lg shadow-lg cursor-pointer group hover:bg-opacity-50"
            :style="{ backgroundColor: getProjectColor(project.name) }"
            :href="route('projects.show', project.pid)"
          >
            <Link
              as="button"
              method="delete"
              :href="route('projects.destroy', project.id)"
              class="absolute z-50 hidden p-2 transition duration-500 rounded-lg right-5 top-3 bg-slate-400 group-hover:grid place-content-center"
            >
              <IconTrash />
            </Link>

            <svg class="absolute bottom-0 left-0 mb-8" viewBox="0 0 375 283" fill="none" style="transform: scale(1.5); opacity: 0.1;">
              <rect x="159.52" y="175" width="152" height="152" rx="8" transform="rotate(-45 159.52 175)" fill="white" />
              <rect y="107.48" width="152" height="152" rx="8" transform="rotate(-45 0 107.48)" fill="white" />
            </svg>

            <div
              class="relative px-6 pt-6 text-sm text-gray-100"
            >
              <div
                class="absolute top-0 left-0 block w-48 h-48"
              />

              {{ project.contact?.company?.name }}
            </div>

            <div class="relative px-6 pb-6 mt-6 text-white">
              <span class="block -mb-1 opacity-75">
                {{ `${project.contact?.first_name} ${project.contact?.last_name}` }}
              </span>

              <div class="flex justify-between">
                <span class="block text-2xl font-thin leading-none">
                  {{ project.name }}
                </span>
              </div>
            </div>

            <div class="relative flex items-center px-6 bg-indigo-600 bg-opacity-80">
              <span class="block my-2 font-semibold text-gray-200 opacity-75">
                {{ `${project.deadline}` }}
              </span>
            </div>
          </Link>

          <!-- <Link v-for="project in projects.data" :key="project.id" as="li" class="relative block p-4 transition duration-200 bg-gray-600 cursor-pointer group rounded-xl hover:bg-gray-700" :href="route('projects.show', project.id)">
            <h4 class="mb-1 font-semibold leading-6 text-white">
              {{ project.name }}
            </h4>

            <div class="flex items-center mb-4">
              <span class="w-2 h-2 mr-1 rounded-full" :class="[project.status === 'open' ? 'bg-green-500' : 'bg-pink-400']" />
              <span class="text-xs font-medium capitalize" :class="[project.status === 'open' ? 'text-green-500' : 'text-pink-400']">{{ project.status }}</span>
            </div>

            <p class="mb-4 text-xs leading-normal text-gray-300 line-clamp-2">
              {{ project.description }}
            </p>

            <div class="pt-4 border-t border-gray-500">
              <div class="flex flex-wrap items-center justify-between -m-2">
                <div class="w-auto p-2">
                  <div class="flex items-center p-2 bg-gray-500 rounded-md">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M11.0001 2.33337H3.00008C2.2637 2.33337 1.66675 2.93033 1.66675 3.66671V11.6667C1.66675 12.4031 2.2637 13 3.00008 13H11.0001C11.7365 13 12.3334 12.4031 12.3334 11.6667V3.66671C12.3334 2.93033 11.7365 2.33337 11.0001 2.33337Z" stroke="#3D485B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                      <path d="M9.66675 1V3.66667" stroke="#3D485B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                      <path d="M4.3335 1V3.66667" stroke="#3D485B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                      <path d="M1.66675 6.33337H12.3334" stroke="#3D485B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                      <path d="M6.3335 9H7.00016" stroke="#3D485B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                      <path d="M7 9V11" stroke="#3D485B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="ml-2 text-xs font-medium text-gray-200">{{ project.deadline }}</span>
                  </div>
                </div>

                <div class="w-auto p-2">
                  <div class="flex items-center h-full">
                    <img
                      v-for="user in project.users"
                      :key="user.id" class="object-cover rounded-full w-7 h-7"
                      src="trizzle-assets/images/avatar-men-circle-border.png"
                      :alt="user.name"
                    >
                  </div>
                </div>
              </div>
            </div>

            <Link as="button" :href="route('projects.destroy', project.id)" method="delete" class="absolute z-10 hidden p-2 transition duration-500 rounded-lg right-5 top-3 bg-slate-400 group-hover:grid place-content-center">
              <Icon icon="ph-trash-bold" />
            </Link>
          </Link> -->
        </ul>

        <div v-else class="flex flex-col items-center justify-center p-6 sm:px-6 lg:px-8">
          <div class="flex flex-col items-center w-full gap-2 py-12 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <IconList class="text-gray-400" />

            <h2 class="text-lg font-semibold leading-none text-center text-gray-500">
              No projects found!
            </h2>

            <p class="text-sm text-center text-gray-500">
              You don't have projects yet.
            </p>

            <div>
              <Button as-child>
                <Link
                  as="button"
                  :href="route('projects.create')">
                  <IconPlus />
                  <span>Create project</span>
                </Link>
              </Button>
            </div>
          </div>
        </div>

      </section>
    </div>
  </section>
</template>
