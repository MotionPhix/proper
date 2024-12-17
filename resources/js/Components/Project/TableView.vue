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
  <table class="min-w-full bg-white rounded-lg shadow-md">
    <thead>
    <tr class="bg-gray-100 text-left">
      <th class="py-2 px-4">Project Name</th>
      <th class="py-2 px-4">Company</th>
      <th class="py-2 px-4">Contact</th>
      <th class="py-2 px-4">Actions</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="project in projects" :key="project.id" class="border-b">
      <td class="py-2 px-4">{{ project.name }}</td>
      <td class="py-2 px-4">{{ project.contact?.company?.name || 'No company' }}</td>
      <td class="py-2 px-4">{{ project.contact?.first_name }} {{ project.contact?.last_name }}</td>
      <td class="py-2 px-4 flex items-center space-x-2">
        <Link :href="route('projects.show', project.id)" class="text-blue-500 hover:underline">View</Link>
        <button
          @click="deleteProject(project.id)"
          class="text-red-500 hover:underline">
          Delete
        </button>
      </td>
    </tr>
    </tbody>
  </table>
</template>

<style scoped>
/* Custom styles for table view if needed */
</style>
