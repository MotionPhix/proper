import './bootstrap'

import '../css/app.css'

import 'v-calendar/style.css'

import type {DefineComponent} from 'vue'

import {createApp, h} from 'vue'

import {createInertiaApp} from '@inertiajs/vue3'

import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers'

import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Kwik'

createInertiaApp({
  title: title => `${title} - ${appName}`,
  resolve: name => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
  setup({
    el,
    App,
    props,
    plugin
  }) {
    createApp({render: () => h(App, props)})
      // .component('draggable', VueDraggableNext)
      .use(plugin)
      .use(ZiggyVue, Ziggy)
      .mount(el)
  },
  progress: {
    color: '#4B5563',
  },
})
