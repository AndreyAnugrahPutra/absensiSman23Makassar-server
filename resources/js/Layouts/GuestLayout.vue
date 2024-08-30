<script setup>
import { onMounted, ref} from 'vue';
import LogoSekolah from '@/Components/LogoSekolah.vue'

import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'

onMounted(()=>
{
    checkNotif()
})

const toast = useToast()

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)

const pageProps  = defineProps({flash : Object});

const showToast = () => {

if(notif_status.value == 'success') {
    toast.add({ severity: 'success', summary: 'Info', detail: notif_message, life: 4000, group : 'tc1'});
}
else if(notif_status.value == 'failed') {
    toast.add({ severity: 'error', summary: 'Info', detail: notif_message, life: 4000, group : 'tc1'});
}
}

const checkNotif = () =>
{
    if(pageProps.flash.show == true)
    {
        notif_show.value = pageProps.flash.show
        notif_status.value = pageProps.flash.status
        notif_message.value = pageProps.flash.message

        setTimeout(() =>
        {
            showToast()
        },500)
    }
}
</script>

<template>
     <section class="min-h-screen w-full py-8 flex flex-col gap-y-8 justify-center items-center">
        <Toast position="top-center" group="tc1"/>
        <LogoSekolah class="max-w-[120px]" />
        <span class="text-xl">Selamat Datang Di Sistem Absensi SMAN 23 Makassar</span>
        <slot name="form" />
    </section>
</template>

<style scoped>
</style>