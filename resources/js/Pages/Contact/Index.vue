<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

import {
  IconPlus, IconUser,
} from '@tabler/icons-vue'

import FormContact from './FormContact.vue'

import PageHeader from '@/Components/PageHeader.vue'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import ContactCard from '@/Components/ContactCard.vue'

import type { Company, Contact, ContactApiResponse } from '@/types'

interface Props {
  contacts: Contact
  companies?: Company[]
  contact?: ContactApiResponse
  canOpen: Boolean
}

const props = defineProps<Props>()

const open = props.canOpen

defineOptions({ layout: AuthenticatedLayout })
</script>

<template>
  <Head title="Explore Contacts" />

  <PageHeader>
    <h2 class="flex items-center gap-2 text-xl font-semibold leading-tight text-gray-900 dark:text-white">
      Explore contacts <span class="text-gray-400 dark:text-gray-600">({{ contacts?.total }})</span>
    </h2>

    <span class="flex-1" />

    <Link
      v-if="contacts?.data.length"
      as="button"
      :href="route('contacts.create')"
      class="flex items-center gap-2 py-2 pl-3 pr-4 font-semibold text-gray-300 rounded bg-primary-700 lg:bg-transparent lg:text-primary-700 lg:p-0 dark:text-white"
      aria-current="page"
    >
      <IconPlus class="h-5" />
      <span>Add contact</span>
    </Link>

    <!-- <Link
        v-if="contacts.data.length"
        class="flex items-center gap-2 py-2 pl-3 pr-4 font-semibold text-gray-300 rounded bg-primary-700 lg:bg-transparent lg:text-primary-700 lg:p-0 dark:text-white"
        aria-current="page"
        :href="route('contacts.create')"
      >
        <Icon icon="ph-plus-bold" class="h-5" />
        <span>Add contact</span>
      </Link> -->
  </PageHeader>

  <div class="py-12 h-[90dvh]">
    <div class="h-full mx-auto max-w-7xl sm:px-6 lg:px-8">
      <article v-if="contacts?.total" class="flex h-full overflow-y-auto">
        <!-- <div>
          <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead
              class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
            >
              <tr>
                <th scope="col" class="p-4">
                  <div class="flex items-center">
                    <input
                      id="checkbox-all-search" type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    >
                    <label for="checkbox-all-search" class="sr-only">checkbox</label>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3">
                  Company
                </th>

                <th scope="col" class="px-6 py-3">
                  Contact Person
                </th>

                <th scope="col" class="px-6 py-3">
                  Status
                </th>

                <th scope="col" class="px-6 py-3" />
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="(contact, index) in contacts?.data" :key="contact.id"
                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-opacity-75"
              >
                <td class="w-4 p-4">
                  <div class="flex items-center">
                    <input
                      :id="`checkbox-table-search-${index}`" type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    >
                    <label :for="`checkbox-table-search-${index}`" class="sr-only">checkbox</label>
                  </div>
                </td>

                <th scope="row" class="flex items-center px-6 py-2 text-gray-700 whitespace-nowrap dark:text-gray-200">
                  <span class="p-2 bg-gray-200 rounded-full dark:bg-gray-900">
                    <IconBuildingWarehouse class="w-5 h-5" />
                  </span>

                  <div class="pl-3 font-semibold text-gray-700 dark:text-gray-300">
                    <span class="block text-sm">{{ contact.company?.name }}</span>
                  </div>
                </th>

                <td class="px-6 py-2">
                  <span class="block text-base font-medium text-gray-500">
                    {{ contact.first_name }} {{ contact.last_name }}
                  </span>

                  <span class="block text-xs font-medium text-gray-500">
                    {{ contact.email }}
                  </span>
                </td>

                <td class="px-6 py-2">
                  <span
                    class="{{ contact.status === 'active' ? 'bg-green-200' : 'bg-red-200' }} capitalize px-3 py-1.5 rounded text-sm font-semibold text-gray-600"
                  >
                    {{ contact.status }}
                  </span>
                </td>

                <td class="px-6 py-2">
                  <ActionMenu>
                    <ul class="flex flex-row">
                      <li>
                        <Tooltip content="Details">
                          <Link as="button" preserve-scroll :href="route('contacts.show', contact.id)" class="relative flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                            <IconInfoSquareRounded />
                          </Link>
                        </tooltip>
                      </li>

                      <li>
                        <Tooltip content="Delete">
                          <Link
                            preserve-scroll
                            as="button"
                            type="button"
                            method="delete"
                            :href="route('contacts.destroy', contact.id)"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-left text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                          >
                            <IconTrash />
                          </Link>
                        </Tooltip>
                      </li>
                    </ul>
                  </ActionMenu>
                </td>
              </tr>
            </tbody>
          </table>
        </div> -->

        <section class="w-72">
          Contacts Details
        </section>

        <section class="flex flex-col flex-1 px-6 py-2 space-y-4">
          <!-- <Pagination :links="contacts.links" /> -->
          <!-- <Nav :pagination="contacts" /> -->
          <ContactCard
            v-for="(contact) in contacts?.data"
            :key="contact.id"
            :href="route('contacts.show', contact.id)"
            :contact="contact"
          />
        </section>
      </article>

      <article v-else class="py-12 sm:rounded-lg">
        <div class="flex flex-col items-center gap-3">
          <IconUser class="text-gray-400" />

          <h2 class="text-lg font-semibold leading-none text-center text-gray-500">
            No contacts found!
          </h2>

          <p class="text-sm text-center text-gray-500">
            You don't have contacts yet.
          </p>

          <div>
            <Link
              as="button"
              class="flex gap-2 items-center text-gray-500 border-gray-500 border hover:border-gray-900 rounded-lg dark:border-slate-600 dark:text-gray-500 font-semibold my-4 px-3 py-1.5 dark:hover:text-gray-400 dark:hover:border-gray-400 hover:text-gray-900 transition duration-300"
              preserve-scroll
              :href="route('contacts.index', 'modal')"
            >
              <IconPlus />

              <span>Add contact</span>
            </Link>
          </div>
        </div>
      </article>
    </div>
  </div>

  <template v-if="open">
    <FormContact
      :action="null"
      :contact="contacts"
      :companies="companies"
      :trigger="open"
      width="max-w-2xl"
    />
  </template>
</template>
