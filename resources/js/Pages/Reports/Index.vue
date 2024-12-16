<script setup>
import { Head } from '@inertiajs/vue3'
import PageHeader from '@/Components/PageHeader.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineOptions({
  layout: AuthenticatedLayout,
})
</script>

<template>
  <Head title="Explore projects" />

  <PageHeader>
    <h2 class="flex items-center gap-2 text-xl font-semibold leading-tight text-gray-900 dark:text-white">
      Explore Reports
    </h2>
  </PageHeader>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      Reports coming shortly
    </div>
  </div>
</template>
