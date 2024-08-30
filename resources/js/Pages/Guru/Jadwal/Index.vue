<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

import NavLink from '@/Components/NavLink.vue'
import GuruLayout from '@/Layouts/Guru/GuruLayout.vue'


import Button from 'primevue/button'
import InputText from 'primevue/inputtext'

import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Message from 'primevue/message'

import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'
import {FilterMatchMode} from '@primevue/core/api'


const pageProps = defineProps({dataJadwal : Array, flash : Object})

onMounted(()=>
{
    dataJadwalFix.value = pageProps?.dataJadwal.map((p, i) => ({ 
        index : i+1,
        label : `${p.mulai}/${p.selesai}`,
        ...p}))
    checkNotif()
})

const refreshLoading = ref(false)

const toast = useToast()


const dt = ref()

const exportCSV = () =>
{
    dt.value.exportCSV()
}

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)

let dataJadwalFix = ref([])

const filters = ref({
    global : { value: null, matchMode: FilterMatchMode.CONTAINS },
});

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

const showToast = () => {

    if(notif_status.value == 'success') {
        toast.add({ severity: 'success', summary: 'Info', detail: notif_message, life: 4000 });
    }
    else if(notif_status.value == 'failed') {
        toast.add({ severity: 'error', summary: 'Info', detail: notif_message, life: 4000 });
    }
}

const refreshPage = () =>
{
    refreshLoading.value = true
    setTimeout(()=>
        {
            router.visit(route('guru.jadwal.index'))
            refreshLoading.value = false
        }
    ,1000)
}
</script>

<template>
    <Head title="Jadwal" />
    <GuruLayout pageTitle="Jadwal">
        <template #pageContent>
            <div class="flex justify-between items-center">
                <Toast v-if="notif_show == true"/>
                <!--left  -->
                <h1 class="text-2xl">Data Jadwal Pelajaran</h1>
                <!-- right -->
            </div>

            <!-- table data tahun ajar -->
            <DataTable v-model:filters="filters" sortMode="multiple" ref="dt" paginator :rows="10" :value="dataJadwalFix" removableSort scrollable
             size="small" stripedRows tableStyle="min-width: 50rem" class="mt-4">
                <template #header>
                    <div class="flex justify-between items-center mb-5">
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari Data" :disabled="!dataJadwalFix.length"/>
                        </IconField>

                        <div class="flex items-center gap-x-4">
                            <Button :loading="refreshLoading" icon="pi pi-refresh" severity="secondary" outlined size="small" @click="refreshPage"/>
                            <Button :disabled="!dataJadwalFix.length" label="Export CSV" size="small" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                        </div>
                    </div>
                </template>
                <template #empty>
                    <Message severity="secondary" class="p-2">Tidak ada data jadwal</Message> 
                </template>
             <Column field="index" sortable header="No" class="min-w-[80px]" />
             <Column field="hari" sortable header="Hari" class="min-w-[100px]" />
             <Column field="waktu_mulai" sortable header="Waktu mulai" class="min-w-[140px]"/>
             <Column field="waktu_selesai" sortable header="Waktu selesai" class="min-w-[150px]"/>
             <Column field="kelas" sortable header="Kelas" class="min-w-[100px]"/>
             <Column field="mapel" sortable header="Mapel" class="min-w-[100px]"/>
             <Column field="nama_guru" sortable header="Guru" class="min-w-[200px]"/>
             <Column field="semester" sortable header="Semester" class="min-w-[100px]"/>
             <Column field="label" sortable header="Tahun Ajaran" class="min-w-[150px]"/>
             <Column header="Action" class="min-w-[180px]" frozen alignFrozen="right">
                    <template #body="{data}">
                        <!-- <NavLink class="border-none p-0 m-0" :href="`absensi/daftar_absen/${data.id_jadwal}/view`">
                            <Button label="Absensi" size="small" icon="pi pi-eye" iconPos="right" severity="contrast"/>
                        </NavLink> -->
                        <NavLink class="border-none p-0 m-0" :href="`mapel/`+data.id_jadwal">
                            <Button label="Form Absensi" size="small" icon="pi pi-eye" iconPos="right" severity="success"/>
                        </NavLink>
                    </template>
                </Column>
            </DataTable>

        </template>
    </GuruLayout>
</template>

<style scoped>
</style>