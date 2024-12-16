<script setup lang="ts">
import PageHeader from '@/Components/PageHeader.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { IconPlus } from '@tabler/icons-vue'

defineOptions({
  layout: AuthenticatedLayout,
})
</script>

<template>
  <Head title="Dashboard" />

  <PageHeader>
    <h2 class="flex items-center gap-2 text-xl font-semibold leading-tight text-gray-900 dark:text-white">
      Explore dashboard
    </h2>

    <span class="flex-1" />

    <Link
      class="flex items-center gap-1"
      as="button"
      :href="route('projects.create')"
    >
      <IconPlus class="h-5" />
      <span>New project</span>
    </Link>
  </PageHeader>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          Dashboard coming soon!
        </div>
      </div>
    </div>
  </div>
</template>
