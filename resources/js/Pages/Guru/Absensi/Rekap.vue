<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import GuruLayout from '@/Layouts/Guru/GuruLayout.vue'

import Button from 'primevue/button'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ColumnGroup from 'primevue/columngroup'
import Row from 'primevue/row'
import Message from 'primevue/message'
import InputText from 'primevue/inputtext'


import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'

import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

import {FilterMatchMode} from '@primevue/core/api'

const pageProps = defineProps({dataAbsensi : Array, flash : Object, idJadwal : String, mapel : Object, statistikAbsen : Object, kelas : Object})

onMounted(()=>
{
    dataAbsensiFix.value = pageProps?.dataAbsensi?.map((p, i) => ({ 
        index : i+1, 
        tanggal_dibuat : `${formatDate(p.created_at)}`,
        ...p}))
    namaKelas.value = pageProps?.dataAbsensi[0]?.kelas
    namaMapel.value = pageProps?.dataAbsensi[0]?.mapel
    checkNotif()
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const dataAbsensiFix = ref([])
const toast = useToast();
const isLoading = ref()

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)

const dt = ref()
const namaKelas = ref()
const namaMapel = ref()
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
        router.visit(pageProps?.idJadwal) 
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
    <Head title="Rekap Absensi" />

    <GuruLayout pageTitle="Rekap Absensi">
        <template #pageContent>
            <Toast />
            <DataTable v-model:filters="filters" ref="dt" rowGroupMode="subheader" groupRowsBy="dataAbsensiFix.created_at" paginator :rows="35" :value="dataAbsensiFix" removableSort scrollable  size="small" stripedRows tableStyle="min-width: 50rem" class="mt-4">
                <template #header>
                    <div class="flex justify-between items-center mb-5">
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari Absensi" :disabled="dataAbsensiFix.length < 1" />
                        </IconField>

                        <div class="flex items-center gap-x-4">
                            <Button :loading="isLoading" icon="pi pi-refresh" severity="secondary" outlined size="small" @click="refreshPage"/>
                            <Button :disabled="dataAbsensiFix.length < 1" @click="exportCSV" severity="help" label="export CSV" icon="pi pi-upload" size="small"/>
                        </div>
                    </div>
                    <!-- jumlah kehadiran -->
                     <div class="flex flex-col gap-y-2">
                         <span class="font-semibold">MATA PELAJARAN : {{ pageProps?.mapel.namaMapel }}</span>
                         <span class="font-semibold">GURU : {{ pageProps?.mapel.namaGuru }}</span>
                         <span class="font-semibold">KELAS : {{ pageProps?.kelas.namaKelas }}</span>
                         <span class="font-semibold">WALI KELAS : {{ pageProps?.kelas.waliKelas }}</span>
                     </div>
                </template>
                <template #empty>
                    <Message severity="secondary">Tidak ada data absensi</Message> 
                </template>
                <Column field="index" sortable header="No" class="min-w-[80px]"></Column>
                <Column field="nisn" sortable header="NISN" class="min-w-[150px]"></Column>
                <Column field="nama_siswa" sortable header="Nama Siswa" class="min-w-[250px]"></Column>
                <Column field="kelas" sortable header="Kelas" class="min-w-[100px]"></Column>
                <Column field="mapel" sortable header="Mapel" class="min-w-[100px]"></Column>
                <Column field="waktu_mulai" sortable header="Mulai" class="min-w-[100px]"></Column>
                <Column field="waktu_selesai" sortable header="Selesai" class="min-w-[100px]"></Column>
                <Column field="hadir" sortable header="Hadir" class="min-w-[100px]"/>
                <Column field="sakit" sortable header="Sakit" class="min-w-[100px]"/>
                <Column field="izin" sortable header="Izin" class="min-w-[100px]"/>
                <Column field="alpha" sortable header="Alpha" class="min-w-[100px]"/>
                <ColumnGroup type="footer">
                    <Row>
                        <Column :colspan="6"/>
                        <Column footer="Hadir :" :colspan="1"/>
                        <Column :footer="pageProps?.statistikAbsen?.Hadir" :colspan="4"/>
                    </Row>
                    <Row>
                        <Column :colspan="6"/>
                        <Column footer="Sakit :" :colspan="2"/>
                        <Column :footer="pageProps?.statistikAbsen?.Sakit" :colspan="4"/>
                    </Row>
                    <Row>
                        <Column :colspan="6"/>
                        <Column footer="Izin :" :colspan="3"/>
                        <Column :footer="pageProps?.statistikAbsen?.Izin" :colspan="4"/>
                    </Row>
                    <Row>
                        <Column :colspan="6"/>
                        <Column footer="Alpha :" :colspan="4"/>
                        <Column :footer="pageProps?.statistikAbsen?.Alpha" :colspan="4"/>
                    </Row>
                </ColumnGroup>
            </DataTable>
        </template>
    </GuruLayout>
</template>

<style scoped>
</style>