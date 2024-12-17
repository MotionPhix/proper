<script setup lang="ts">
import PageHeader from '@/Components/PageHeader.vue'
import Toggle from "@/Components/Project/Toggle.vue";
import ProjectCardView from "@/Components/Project/CardView.vue.vue.vue";
import ProjectTableView from "@/Components/Project/TableView.vue.vue";
import { ref, onMounted } from 'vue'

defineProps<{
  projects: any
}>()

let view = ref('card') // Default view can be 'card' or 'table'

onMounted(() => {
  // Get the saved view from localStorage (if it exists)
  const savedView = localStorage.getItem('project_view')
  if (savedView === 'card' || savedView === 'table') {
    view.value = savedView
  }
})

function updateView(newView: string) {
  view.value = newView
  localStorage.setItem('project_view', newView) // Save the view to localStorage
}
</script>

<template>
  <div>
    <PageHeader>
      <h2 class="text-xl font-semibold">Projects</h2>
      <Toggle :view="view" @updateView="updateView" />
    </PageHeader>

    <section class="mt-8">
      <component
        :is="view === 'card' ? 'ProjectCardView' : 'ProjectTableView'"
        :projects="projects.data"
      />
    </section>
  </div>
</template>

<style scoped>
/* Custom styles for main view if needed */
</style>
