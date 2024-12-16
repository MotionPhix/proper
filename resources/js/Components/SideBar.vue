<script setup>
import UserNav from "@/Layouts/UserNav.vue";
import { UseDark } from '@vueuse/components'

import { Link, router, usePage } from '@inertiajs/vue3'

import { IconAddressBook, IconChartLine, IconChartPie, IconClock12, IconGrid3x3, IconLogout2, IconMoon, IconStack2, IconSun, IconUsers } from '@tabler/icons-vue'

import { computed } from 'vue'

function logout() {
  router.post('/logout')
}

const { props: { auth } } = usePage()

auth.role = auth.role ?? ''

const isAdmin = computed(() => auth.role.toLowerCase() === 'administrator')
const isManager = computed(() => auth.role.toLowerCase() === 'general manager')
const isDesigner = computed(() => auth.role.toLowerCase() === 'graphic designer')
const isArtisan = computed(() => auth.role.toLowerCase() === 'printing production')
const isSales = computed(() => auth.role.toLowerCase() === 'sales personnel')
</script>

<template>
  <aside class="flex flex-col h-full p-4 overflow-y-auto bg-white dark:bg-gray-800 scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-800 scrollbar-thin scrollbar-track-gray-100">
    <h3 class="flex items-center justify-between p-3 text-base font-bold text-gray-900 border-b border-gray-100 dark:text-gray-200 dark:border-gray-600">
      <span class="text-2xl">Kwik Tasker</span>

      <UseDark v-slot="{ isDark, toggleDark }">
        <button class="flex items-center justify-center p-1 rounded w-7 h-7 hover:bg-gray-300 dark:hover:bg-gray-700" @click="toggleDark()">
          <IconSun v-if="isDark" class="h-5" />
          <IconMoon v-else class="h-5" />
        </button>
      </UseDark>
    </h3>

    <div v-if="isAdmin || isManager || isSales" class="grid grid-cols-2 gap-2 mx-2 my-4">
      <Link
        :href="route('projects.index')"
        as="button"
        preserve-scroll
        :class="{ 'bg-gray-100 dark:bg-gray-500': $page.url.startsWith('/projects') }"
        class="flex flex-col items-center p-4 text-gray-500 rounded-lg hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-600"
      >
        <IconStack2 class="inline w-8 h-8" />

        <div class="font-medium text-center">
          Projects
        </div>
      </Link>

      <template v-if="isAdmin || isManager">
        <Link
          :href="route('users.index')"
          as="button"
          preserve-scroll
          :class="{ 'bg-gray-300 dark:bg-gray-900': $page.url.startsWith('/users') }"
          class="flex flex-col items-center p-4 text-gray-500 rounded-lg hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-600"
        >
          <IconUsers class="inline w-8 h-8" />

          <div class="font-medium text-center">
            Members
          </div>
        </Link>
      </template>

      <Link
        :href="route('tasks.index')"
        as="button"
        preserve-scroll
        :class="{ 'bg-gray-300 dark:bg-gray-900': $page.url.startsWith('/tasks') }"
        class="flex flex-col items-center p-4 text-gray-500 rounded-lg hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-600"
      >
        <IconClock12 class="inline w-8 h-8" />

        <div class="font-medium text-center">
          Tasks
        </div>
      </Link>

      <Link
        :href="route('reports.index')"
        as="button"
        preserve-scroll
        :class="{ 'bg-gray-300 dark:bg-gray-900': $page.url.startsWith('/reports') }"
        class="flex flex-col items-center p-4 text-gray-500 rounded-lg hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-600"
      >
        <IconChartPie class="inline w-8 h-8" />

        <div class="font-medium text-center">
          Reports
        </div>
      </Link>
    </div>

    <div v-if="isAdmin || isManager || isSales" class="block my-4 border-t border-gray-200 dark:border-gray-600" />

    <div class="py-4">
      <ul class="space-y-2 font-medium">
        <li>
          <Link
            :href="route('dashboard')"
            as="button"
            preserve-scroll
            :class="{ 'bg-gray-300 dark:bg-gray-900': $page.url === '/' }"
            class="flex items-center w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            <IconChartLine aria-hidden="true" class="text-gray-500 transition duration-75 w-7 h-7 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />

            <span class="ml-3">Dashboard</span>
          </Link>
        </li>

        <li>
          <Link
            v-if="isArtisan || isDesigner"
            :href="route('projects.index')"
            as="button"
            preserve-scroll
            :class="{ 'bg-gray-300 dark:bg-gray-900': $page.url.startsWith('/projects') }"
            class="flex items-center w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            <IconAddressBook class="inline w-7 h-7" />

            <span class="ml-3">Projects</span>
          </Link>
        </li>

        <li v-if="isAdmin || isManager || isSales">
          <Link
            preserve-scroll
            as="button"
            :href="route('contacts.index')"
            :class="{ 'bg-gray-300 dark:bg-gray-900': $page.url.startsWith('/contacts') }"
            class="flex items-center w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            <IconAddressBook class="w-6 h-6 text-gray-500 dark:text-gray-400" />
            <span class="ml-3 whitespace-nowrap">Contacts</span>
          </Link>
        </li>

        <li v-if="isAdmin || isManager || isSales">
          <Link
            :href="route('companies.index')"
            as="button"
            preserve-scroll
            :class="{ 'bg-gray-300 dark:bg-gray-900': $page.url.startsWith('/companies') }"
            class="flex items-center w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            <IconGrid3x3 aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />

            <span class="ml-3 whitespace-nowrap">Customers</span>
            <!-- <span class="inline-flex items-center justify-center px-2 ml-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">Pro</span> -->
          </Link>
        </li>

        <li v-if="isArtisan || isDesigner">
          <Link
            preserve-scroll
            as="button"
            :href="route('tasks.index')"
            :class="{ 'bg-gray-300 dark:bg-gray-900': $page.url.startsWith('/tasks') }"
            class="flex items-center w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            <svg class="inline w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>

            <span class="ml-3 whitespace-nowrap">Tasks</span>
          </Link>
        </li>

        <li v-if="isArtisan">
          <Link
            :href="route('reports.index')"
            as="button"
            preserve-scroll
            :class="{ 'bg-gray-300 dark:bg-gray-900': $page.url.startsWith('/reports') }"
            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>

            <span class="flex-1 ml-3 whitespace-nowrap">Reports</span>
          </Link>
        </li>
      </ul>
    </div>

    <span class="flex-1 block" />

    <section class="border-t pt-2">
      <UserNav />
    </section>
  </aside>
</template>
