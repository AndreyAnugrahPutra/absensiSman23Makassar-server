<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import GuruLayout from '@/Layouts/Guru/GuruLayout.vue'
import NavLink from '@/Components/NavLink.vue'

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

const pageProps = defineProps({dataAbsensi : Array, jumlahStatus : Array, flash : Object})

onMounted(()=>
{
    dataAbsensiFix.value = pageProps.dataAbsensi?.map((p, i) => ({ 
        index : i+1, 
        tanggal_dibuat : `${formatDate(p.created_at)}`,
        ...p}))
    jumlahStatusFix.value = pageProps?.jumlahStatus
    namaKelas.value = pageProps?.dataAbsensi[0]?.kelas

    checkNotif()
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const dataAbsensiFix = ref([])
const jumlahStatusFix = ref([])
const toast = useToast();
const isLoading = ref()

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)

const dt = ref()
const namaKelas = ref()
const formatDate = (date) =>
{
    const newDate = new Date(date)
    return `${newDate.getDate()}-${newDate.getMonth()}-${newDate.getFullYear()}`
}

const refreshPage = () =>
{
    isLoading.value = true

    setTimeout(()=>
    {
        isLoading.value = false 
        router.reload() 
    }, 1000)
}

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

const exportCSV = () =>
{
    dt.value.exportCSV()
}
</script>

<template>
    <Head title="Absensi" />

    <GuruLayout pageTitle="Absensi">
        <template #pageContent>
            <Toast />
            <DataTable v-model:filters="filters" ref="dt" rowGroupMode="subheader" groupRowsBy="dataAbsensiFix.created_at" paginator :rows="10" :value="dataAbsensiFix" removableSort scrollable  size="small" stripedRows tableStyle="min-width: 50rem" class="mt-4">
                <template #header>
                    <div class="flex justify-between items-center mb-5">
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari Data" :disabled="dataAbsensiFix.length < 1" />
                        </IconField>

                        <div class="flex items-center gap-x-4">
                            <Button :loading="isLoading" icon="pi pi-refresh" severity="secondary" outlined size="small" @click="refreshPage"/>
                            <Button :disabled="dataAbsensiFix.length < 1" @click="exportCSV" severity="help" label="export CSV" icon="pi pi-upload" size="small"/>
                        </div>
                    </div>
                    <!-- jumlah kehadiran -->
                     <span class="text-lg font-bold">KELAS : {{ namaKelas }}</span>
                </template>
                <template #empty>
                    <Message severity="secondary">Tidak ada data absensi</Message> 
                </template>
                <Column field="index" sortable header="No" class="min-w-[80px]"></Column>
                <Column field="nama_siswa" sortable header="Nama Siswa" class="min-w-[200px]"></Column>
                <Column field="status" sortable header="Status" class="min-w-[200px]"/>
                <Column field="deskripsi" sortable header="Deskripsi" class="min-w-[200px]"/>
                <Column sortable header="Lampiran" class="min-w-[200px]">
                    <template #body="{data}">
                        <Button label="Lihat Lampiran" severity="help" size="small" icon="pi pi-eye" iconPos="right" target="_blank" v-if="data.lampiran_path" as="a" :href="data.lampiran_path"/>
                        <span class="text-gray-400" v-else>Tidak ada lampiran</span>
                    </template>
                </Column>
                <Column field="waktu_absen" sortable header="Waktu Absen" class="min-w-[200px]"/>
                <template #groupfooter="{data}">
                    <div class="flex items-center gap-2">
                        <div class="flex flex-col py-4 font-semibold" v-for="statusAbsen in jumlahStatusFix" :key="statusAbsen.index">
                            <span>{{ statusAbsen.status+' : '+statusAbsen.jumlah }}</span>
                        </div>
                    </div>
                    <span class="font-semibold">Tanggal Absen dibuat : {{ data.tanggal_dibuat }}</span>
                </template>
                <!-- <Column header="Action" class="min-w-[80px]" frozen alignFrozen="right">
                    <template #body="{data}">
                            <NavLink class="border-none p-0 m-0" :href="`form/`+data.id_form">
                                <Button label="Detail" size="small" icon="pi pi-eye" iconPos="right" severity="success"/>
                            </NavLink>
                    </template>
                </Column> -->
            </DataTable>
        </template>
    </GuruLayout>
</template>

<style scoped>
</style>