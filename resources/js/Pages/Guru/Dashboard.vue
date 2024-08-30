<script setup>
import { onMounted, ref } from 'vue'
import { Link } from '@inertiajs/vue3'

import GuruLayout from '@/Layouts/Guru/GuruLayout.vue'

import Card from 'primevue/card'
import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

const pageProps = defineProps({
    tahunAjar : Object, jmlhKelas : Number, 
    jmlhMapel : Number, flash : Object
})

onMounted(()=>
{
    checkNotif()
})

const toast = useToast();

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)

const showToast = () => {

    if(notif_status.value == 'success') {
        toast.add({ severity: 'success', summary: 'Info', detail: notif_message, life: 4000, group : 'tc' });
    }
    else if(notif_status.value == 'failed') {
        toast.add({ severity: 'error', summary: 'Info', detail: notif_message, life: 4000 });
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
        },1000)
    }
}

</script>

<template>
    <Head title="Guru Dashboard" />

    <GuruLayout pageTitle="Dashboard">
        <template #pageContent>
            <Toast position="top-center" group="tc"/>
            <div class="flex flex-col gap-y-8">
                <Card class="rounded-lg p-4 border overflow-hidden shadow" unstyled>
                    <template #content>
                        <div class="flex items-center justify-between">
                            <span>
                                Semester : {{ pageProps.tahunAjar.semester }}
                            </span>
                            <span>
                                Tahun Ajaran : {{ pageProps.tahunAjar.mulai+'/'+pageProps.tahunAjar.selesai }}
                            </span>
                        </div>
                    </template> 
                </Card>
                <div class="flex flex-wrap items-center gap-y-8 gap-x-20 font-semibold">
                        <Link :href="route('guru.mapel.index')">
                            <Card class="w-[18rem] p-6 rounded-md shadow-md bg-yellow-400" unstyled>
                                <template #content>
                                    <div class="flex justify-between items-center text-white">
                                        <i class="pi pi-book" style="font-size: 2rem;"></i>
                                        <div class="flex flex-col items-center">
                                            <h1>MATA PELAJARAN</h1>
                                            <h1>{{ pageProps.jmlhMapel }}</h1>
                                        </div>
                                    </div>
                                </template>
                            </Card>
                        </Link>
                        <Link :href="route('guru.kelas.index')">
                            <Card class="w-[18rem] p-6 rounded-md shadow-md bg-emerald-400" unstyled>
                                <template #content>
                                    <div class="flex justify-between items-center text-white">
                                        <i class="pi pi-home" style="font-size: 2rem;"></i>
                                        <div class="flex flex-col items-center">
                                            <h1>KELAS</h1>
                                            <h1>{{ pageProps.jmlhKelas }}</h1>
                                        </div>
                                    </div>
                                </template>
                            </Card>
                        </Link>
                </div>
            </div>
        </template>
    </GuruLayout>

</template>
