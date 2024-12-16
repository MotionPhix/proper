<script setup lang="ts">
import type { ContactApiResponse, Interaction } from '@/types'

const props = defineProps<{
  interactions: Interaction[]
  contact: ContactApiResponse
}>()

function formated(dateStr: string | Date) {
  const date = new Date(dateStr)
  const now = new Date()

  const diff = date.getTime() - now.getTime()
  const seconds = Math.floor(diff / 1000)
  const minutes = Math.floor(seconds / 60)
  const hours = Math.floor(minutes / 60)
  const days = Math.floor(hours / 24)

  if (diff <= 0) {
    // past dates
    if (days >= 2)
      return `Closed ${days} days ago`

    else if (days === 1)
      return 'Closed yesterday'

    else if (hours >= 2)
      return `Closed ${hours} hours ago`

    else if (hours === 1)
      return 'closed an hour ago'

    else if (minutes >= 2)
      return `Closed ${minutes} minutes ago`

    else if (minutes === 1)
      return 'Closed a minute ago'

    else
      return 'Closing just now'
  }
  else {
    // future dates
    if (days >= 2)
      return `Closes in ${days} days`

    else if (days === 1)
      return 'Closes tomorrow'

    else if (hours >= 2)
      return `Closes in ${hours} hours`

    else if (hours === 1)
      return 'Closes in an hour'

    else if (minutes >= 2)
      return `Closes in ${minutes} minutes`

    else if (minutes === 1)
      return 'Closing in a minute'

    else
      return 'Closed just now'
  }
}
</script>

<template>
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-2">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
      <caption class="p-5 text-lg font-semibold text-left text-gray-900 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        Interactions
        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
          Shows timeframe of conversations <strong>{{ $page.props.auth.user.id === contact.user.id ? 'you' : contact.user.name }}</strong> have had with <strong>{{ `${contact.full_name}` }}</strong>
        </p>
      </caption>

      <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
        <tr>
          <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
            Topic
          </th>
          <th scope="col" class="px-6 py-3">
            Type
          </th>
          <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
            Interacted
          </th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="interact in interactions" :key="interact.id" class="border-b border-gray-200 dark:border-gray-700">
          <th scope="row" class="px-6 py-4 font-medium text-gray-900 bg-gray-50 dark:text-white dark:bg-gray-800">
            {{ interact.topic }}
          </th>
          <td class="px-6 py-4">
            {{ interact.type }}
          </td>
          <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800 w-1/5">
            <span class="block leading-none">{{ interact.interacted_on }}</span>
            <span class="block text-xxs font-semibold">on {{ interact.interaction_date }}</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
