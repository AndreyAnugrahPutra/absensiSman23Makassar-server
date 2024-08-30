import './bootstrap';
import '../css/app.css'

import { createApp, h } from 'vue'

import { createInertiaApp, Head } from '@inertiajs/vue3'

import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import 'primeicons/primeicons.css'

import ToastService from 'primevue/toastservice'
import ConfirmationService from 'primevue/confirmationservice';

import {ZiggyVue} from '../../vendor/tightenco/ziggy'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
//   id: 'my-app',
  title: (title) => `${title} - ${appName}`,
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(PrimeVue, {
        theme:{
            preset: Aura,
            options: {
                darkModeSelector: '.my-app-dark',
            }
        }
      })
      .use(ToastService)
      .use(ConfirmationService)
      .use(ZiggyVue)
      .component('Head', Head)
      .mount(el)
  },
  progress: {
    color: '#4338ca',
  },
})