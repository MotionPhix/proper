<script setup lang="ts">
import BaseList from '@/Components/BaseList.vue'
import InputError from '@/Components/InputError.vue'
import type { Company, ContactApiResponse } from '@/types'
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'
import { Link, useForm } from '@inertiajs/vue3'
import { computed, onMounted, reactive } from 'vue'

const props = defineProps<{
  action: number | undefined
  trigger: boolean
  width: string
  companies: Company[]
  contact: ContactApiResponse
}>()

const emits = defineEmits(['close'])
const filtered_companies = reactive([])
const isOpen = props.trigger

const form = useForm({
  first_name: props.contact.first_name,
  last_name: props.contact.last_name,
  email: props.contact.email,
  status: props.contact.status,
  company_id: props.contact.id ? props.contact.company_id : '',
})

const computed_width = computed(() => props.width)

// const actionPath = computed(() => props.action ? `/projects/${props.action}/edit` : '/projects/create')

function handleClose() {
  emits('close')
}

onMounted(() => {
  // axios.get(actionPath.value).then((response) => {
  // if (response.data.hasOwnProperty('companies')) {
  // Object.assign(project, response.data.project)
  // Object.assign(companies, response.data.companies)
  // project = {...project, ...response.data.project}

  // form.name = props.project?.name
  // form.description = props.project?.description
  // form.start_date = props.project?.start_date
  // form.company_id = props.project?.id ? props.project?.company_id : ''

  // isOpen.value = true
  // }
  // })

  props.companies.forEach((company) => {
    filtered_companies.push({
      value: company.id,
      label: company.name,
    })
  })
})

function submit() {
  if (props.action) {
    form.patch(`/contacts/${props.contact.id}`, {

      preserveScroll: true,

      onSuccess: () => {
        form.reset()
        handleClose()
      },
    })
  }
  else {
    form.post('/contacts', {

      preserveScroll: true,

      onSuccess: () => {
        form.reset()
        handleClose()
      },
    })
  }
}
</script>

<template>
  <div v-if="isOpen" class="fixed inset-0 z-30 flex items-center justify-center backdrop-blur-sm bg-gray-400/70" />

  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" class="relative z-40">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black bg-opacity-25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div
          class="flex items-center justify-center min-h-full p-4 text-center"
        >
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel
              class="relative p-4 overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5"
              :class="computed_width"
            >
              <DialogTitle as="template">
                <div class="flex items-center justify-between pb-4 mb-4 border-b rounded-t sm:mb-5 dark:border-gray-600">
                  <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ contact.id ? 'Edit' : 'Add' }} contact
                  </h3>

                  <Link
                    as="button"
                    :href="contact.id ? route('contacts.show', contact.id) : route('contacts.index')"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" @click="$emit('close')"
                  >
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    <span class="sr-only">Close modal</span>
                  </Link>
                </div>
              </DialogTitle>

              <form
                @submit.prevent="submit"
              >
                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                  <div class="col-span-2">
                    <label for="company" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact's company</label>
                    <BaseList v-model="form.company_id" :error="form.errors.company_id" placeholder="Select project's company" :options="filtered_companies" />
                  </div>

                  <div>
                    <label
                      for="name"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >
                      Contact's first name
                    </label>

                    <input
                      id="name"
                      v-model="form.first_name"
                      type="text"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-lime-600 focus:border-lime-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-lime-500 dark:focus:border-lime-500"
                      placeholder="Type contact's first name"
                      required
                    >

                    <InputError :message="form.errors.first_name" />
                  </div>

                  <div>
                    <label
                      for="start_date"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >
                      Contact's last name
                    </label>
                    <input
                      id="start_date"
                      v-model="form.last_name"
                      type="text"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-lime-600 focus:border-lime-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-lime-500 dark:focus:border-lime-500"
                      placeholder="Type contact's last name"
                      required
                    >

                    <InputError :message="form.errors.last_name" />
                  </div>

                  <div>
                    <label
                      for="email"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >
                      Email address
                    </label>
                    <input
                      id="end_date"
                      v-model="form.email"
                      type="email"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-lime-600 focus:border-lime-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-lime-500 dark:focus:border-lime-500"
                      placeholder="Type contact's email address"
                      required
                    >

                    <InputError :message="form.errors.email" />
                  </div>

                  <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact's status</label>
                    <BaseList v-model="form.status" :error="form.errors.status" placeholder="Select contact's status" :options="[{ value: 'active', label: 'Active' }, { value: 'in-active', label: 'Inactive' }]" />
                  </div>

                  <div class="flex items-center justify-end col-span-2 gap-4 pt-4">
                    <button
                      type="submit"
                      :disabled="form.processing"
                      class="text-white inline-flex items-center bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
                    >
                      <svg class="w-6 h-6 mr-1 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                      </svg>

                      {{ contact.id ? 'Update ' : 'Create ' }} contact
                    </button>
                  </div>
                </div>
              </form>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
