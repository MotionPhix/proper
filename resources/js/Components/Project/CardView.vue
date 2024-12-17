<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

defineProps<{
  projects: Array<any>
}>()

function deleteProject(projectId: number) {
  // Implement project deletion logic here
}
</script>

<template>
  <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <li v-for="project in projects" :key="project.id" class="bg-white shadow rounded-lg p-4 hover:shadow-lg transition">
      <h3 class="text-lg font-semibold text-gray-900">{{ project.name }}</h3>
      <p class="text-sm text-gray-500">Company: {{ project.contact?.company?.name || 'No company' }}</p>
      <p class="text-sm text-gray-500">Contact: {{ project.contact?.first_name }} {{ project.contact?.last_name }}</p>
      <p class="mt-2 text-sm text-gray-600">{{ project.description }}</p>
      <div class="mt-4 flex justify-between items-center">
        <Link :href="route('projects.show', project.id)" class="text-blue-500 hover:underline">View Details</Link>
        <button
          @click="deleteProject(project.id)"
          class="text-red-500 hover:underline">
          Delete
        </button>
      </div>
    </li>
  </ul>
</template>

<style scoped>
/* Custom styles for card view if needed */
</style>
