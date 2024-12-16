<script setup>
import { onUnmounted } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import ToastListItem from './ToastListItem.vue'
import notification from '@/Stores/toast'

const page = usePage()

function remove(idx) {
  notification.remove(idx)
}

const removeFinishedEventListener = router.on('finish', () => {
  if (page.props.toast) {
    notification.add({
      type: page.props.toast.type ?? null,
      title: page.props.toast.title ?? null,
      message: page.props.toast.message,
    })
  }
})

onUnmounted(() => removeFinishedEventListener())
</script>

<template>
  <TransitionGroup
    tag="div"
    class="fixed top-4 right-4 z-50 max-w-xs flex flex-col gap-2"
    enter-from-class="translate-x-full opacity-0"
    enter-active-class="duration-200"
    leave-active-class="duration-200"
    leave-to-class="translate-x-full opacity-0"
  >
    <ToastListItem
      v-for="(toast, idx) in notification.messages"
      :key="toast.key"
      :type="toast.type"
      :title="toast.title"
      :message="toast.message"
      @remove="remove(idx)"
    />
  </TransitionGroup>
</template>
