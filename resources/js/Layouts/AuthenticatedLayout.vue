<script setup lang="ts">
import SideBar from '@/Components/SideBar.vue';
import ToastList from '@/Components/ToastList.vue';
import { ref } from 'vue';

const showingNavigationDropdown = ref(false)
</script>

<template>
  <div>
    <ToastList />

    <div class="flex h-screen bg-gray-100 dark:bg-gray-900">

      <section class="w-64 h-screen">

        <SideBar />

      </section>

      <section class="flex-1 h-full overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-800 scrollbar-track-gray-100">

        <main>

          <slot />

        </main>

      </section>
    </div>
  </div>
</template>
