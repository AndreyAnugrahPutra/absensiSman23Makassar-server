<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import GuruLayout from '@/Layouts/Guru/GuruLayout.vue'

import Button from 'primevue/button'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Message from 'primevue/message'
import InputText from 'primevue/inputtext'


import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'

import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

import {FilterMatchMode} from '@primevue/core/api'

const pageProps = defineProps({dataMapel : Array, flash : Object})

onMounted(()=>
{
    dataMapelFix.value = pageProps.dataMapel?.map((p, i) => ({ index : i+1, ...p}))

    checkNotif()
})

const dt = ref()

const isLoading = ref(false)

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const dataMapelFix = ref([])
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

const refreshPage = () =>
{
    isLoading.value = true
    router.visit(route('guru.mapel.index'))
    setTimeout(() => isLoading.value = false,500)
}

const exportCSV = () =>
{
    dt.value.exportCSV()
}

</script>

<template>
    <Head title="Mata Pelajaran" />

    <GuruLayout pageTitle="Mata Pelajaran">
        <template #pageContent>
            <Toast />
            <DataTable v-model:filters="filters" ref="dt" paginator :rows="10" :value="dataMapelFix" removableSort scrollable  size="small" stripedRows tableStyle="min-width: 50rem" class="mt-4">
                <template #header>
                    <div class="flex  justify-between items-center mb-5">
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari Data" />
                        </IconField>
                        <div class="flex items-center gap-x-4">
                            <Button :loading="isLoading" icon="pi pi-refresh" severity="secondary" outlined size="small" @click="refreshPage"/>
                            <Button :disabled="!dataMapelFix || isLoading" label="Export CSV" size="small" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                        </div>
                    </div>
                </template>
                <template #empty>
                    <Message severity="secondary">Tidak ada data mata pelajaran</Message> 
                </template>
                <Column field="index" sortable header="No" class="min-w-[80px]"></Column>
                <Column field="nama_mapel" sortable header="Nama Mapel" class="min-w-[200px]"></Column>
                <Column field="hari" sortable header="Hari" class="min-w-[200px]"></Column>
                <Column field="nama_guru" sortable header="Nama Guru" class="min-w-[200px]"/>
                <Column field="waktu_mulai" sortable header="Waktu Mulai" class="min-w-[200px]"/>
                <Column field="waktu_selesai" sortable header="Waktu Selesai" class="min-w-[200px]"/>
            </DataTable>
        </template>
    </GuruLayout>
</template>

<style scoped>
</style>