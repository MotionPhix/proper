<script setup lang="ts">

</script>

<template>
  <div class="relative flex justify-end order-2" x-data="{ open: false }" x-cloak>
    <button x-on:click="open = true" x-on:click.away="open = false" class="inline-block text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-1.5" type="button">
      <span class="sr-only">Open dropdown</span>
      <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
      </svg>
    </button>

    <!-- Dropdown menu -->
    <div x-show="open" class="absolute top-0 z-10 text-base list-none bg-white border rounded-lg shadow dark:border-gray-500 -right-4 dark:bg-gray-700">
      <slot />
    </div>
  </div>
</template>
