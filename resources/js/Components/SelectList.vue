<script setup>
import { ref } from 'vue'
import BaseList from './BaseList.vue'

const people = [
  { value: 1, label: 'Wade Cooper' },
  { value: 2, label: 'Arlene Mccoy' },
  { value: 3, label: 'Devon Webb' },
  { value: 4, label: 'Tom Cook' },
  { value: 5, label: 'Tanya Fox' },
  { value: 6, label: 'Hellen Schmidt' },
]

const form = ref({
  person_ids: [],
})
</script>

<template>
  <div class="mx-auto max-w-2xl">
    <BaseList v-model="form.person_ids" placeholder="Select a person for the project" :options="people" :multiple="true" />

    {{ form }}
  </div>
</template>
