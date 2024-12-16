<script setup lang="ts">
import ContactCard from '@/Components/ContactCard.vue'
import InteractionsTable from '@/Components/InteractionsTable.vue'
import PageHeader from '@/Components/PageHeader.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import FormContact from '@/Pages/Contact/FormContact.vue'
import type { Contact, ContactApiResponse } from '@/types'
import { Head, Link } from '@inertiajs/vue3'
import {
  IconArrowLeft,
  IconBuildingWarehouse,
  IconMail,
  IconPhoneCall,
  IconUser,
  IconUserCancel,
  IconUserEdit,
} from '@tabler/icons-vue'
import { ref } from 'vue'

const props = defineProps<{
  contact: Contact
  contacts: ContactApiResponse
  modal?: string | null
}>()

const open = ref(false)

defineOptions({
  layout: AuthenticatedLayout,
})

function close() {
  open.value = false
}
</script>

<template>
  <Head :title="props.contact.first_name" />

  <PageHeader>
    <Link :href="route('contacts.index')">
      <IconArrowLeft />
    </Link>

    <h2 class="flex items-center gap-2 text-xl font-semibold leading-tight text-gray-900 dark:text-white">
      {{ props.contact?.full_name }}'s details
    </h2>

    <span class="flex-1" />

    <button
      class="flex items-center gap-1 font-semibold transition duration-300 hover:text-gray-500 dark:hover:text-gray-500"
      @click="open = true"
    >
      <IconUserEdit />
      <span>Update contact</span>
    </button>

    <Link
      class="flex items-center gap-1 ml-3 font-semibold transition duration-300 text-rose-500 hover:text-rose-300"
      :href="route('contacts.destroy', contact.idx)"
      method="delete"
      preserve-scroll
      as="button"
    >
      <IconUserCancel />
      <span>Delete contact</span>
    </Link>
  </PageHeader>

  <div class="py-12">
    <div class="mx-auto overflow-hidden shadow-sm max-w-7xl sm:px-6 lg:px-8 sm:rounded-lg">
      <article class="relative flex gap-6 pb-12">
        <section class="flex flex-col sticky top-0 md:top-[calc(16px + 4rem)] gap-2 pb-1 max-w-xs w-full overflow-y-auto max-h-[60vh] scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-800 scrollbar-track-gray-100">
          <h2 class="sticky top-0 z-10 pb-4 mb-4 text-xl font-semibold bg-gray-100 border-b border-gray-400 dark:bg-gray-900 dark:text-gray-100">
            Contacts
          </h2>

          <!-- <article v-if="props.contacts.data"> -->
          <ContactCard
            v-for="selected in props.contacts?.data"
            :key="selected.id"
            :contact="selected"
            :href="route('contacts.show', selected.id)"
            class="mx-2"
          />
          <!-- </article> -->
        </section>

        <section class="flex-1">
          <div class="pb-6 border-b">
            <div class="text-gray-900 dark:text-white">
              <IconUser />

              <section class="flex items-start justify-between">
                <div class="">
                  <h3 class="mb-4 font-semibold leading-none text-gray-900 dark:text-white">
                    {{ contact.full_name }}
                  </h3>

                  <p class="flex items-center gap-2 leading-none text-gray-500 dark:text-gray-400">
                    <IconMail /> <span>{{ contact.email }}</span>
                  </p>

                  <p v-if="contact.phones?.length" class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                    <IconPhoneCall />

                    <template v-for="(phone, idx) in contact.phones" :key="phone.id">
                      <span v-if="idx > 0" class="font-semibold">|</span>
                      <span>{{ phone.number }}</span>
                    </template>
                  </p>

                  <p class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                    <IconBuildingWarehouse /> <strong>{{ contact.company?.name }}</strong> <span>in contact with</span> <strong>{{ contact.user?.id === $page.props.auth.user.id ? 'me' : contact.user.name }}</strong>
                  </p>
                </div>

                <div class="flex flex-col items-start md:items-end">
                  <p class="text-sm font-medium leading-none text-gray-600 dark:text-gray-300">
                    Revenue generated
                  </p>
                  <h2 class="text-base font-semibold text-gray-600 dark:text-gray-100">
                    {{ contact.revenue }}
                  </h2>
                </div>
              </section>
            </div>
          </div>

          <article class="py-1 mt-12 overflow-y-auto max-h-[65vh] scrollbar-thin">
            <InteractionsTable :interactions="contact.interactions" :contact="contact" />
          </article>
        </section>
      </article>
    </div>
  </div>

  <template v-if="open">
    <FormContact :action="props.contact.idx" :trigger="open" width="max-w-2xl" @close="close" />
  </template>
</template>
