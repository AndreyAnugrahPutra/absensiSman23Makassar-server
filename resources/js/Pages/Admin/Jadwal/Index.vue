<script setup>
import { ref, onMounted } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

import NavLink from '@/Components/NavLink.vue'
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue'


import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select'

import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Message from 'primevue/message'

import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'
import {FilterMatchMode} from '@primevue/core/api'


const pageProps = defineProps({dataJadwal : Array, dataTahunAjar : Array, dataKelas : Array, dataMapel : Array, flash : Object})

onMounted(()=>
{
    dataJadwalFix.value = pageProps?.dataJadwal.map((p, i) => ({ 
        index : i+1,
        label : `${p.mulai}/${p.selesai}`,
        ...p}))
    dataMapelFix.value = pageProps?.dataMapel.map((p, i) => ({ label : `${p.nama_mapel}/${p.waktu_mulai}-${p.waktu_selesai}/${p.nama_guru}`,...p}))

    tahunAjarFix = pageProps.dataTahunAjar.map((p,i) => 
    ({ label : `${p.semester} ${p.mulai}/${p.selesai}`, ...p}))

    checkNotif()
})

const refreshLoading = ref(false)

const toast = useToast()

const showForm = ref(false)

const dt = ref()

const exportCSV = () =>
{
    dt.value.exportCSV()
}

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)

const hari = [
    {nama : "Senin"},
    {nama : "Selasa"},
    {nama : "Rabu"},
    {nama : "Kamis"},
    {nama : "Jumat"},
    {nama : "Sabtu"},
]

let dataJadwalFix = ref([])
let dataMapelFix = ref([])

console.log([dataJadwalFix])

let tahunAjarFix = ref([])

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

const form = useForm({
    hari : null,
    id_kelas : null,
    id_mapel : null,
    id_tahun_ajar : null,
})

const uploadJadwal = () =>
{
    form.post(route('admin.jadwal.create'), 
    {
        onSuccess : () => refreshPage()
    })
}

const refreshPage = () =>
{
    refreshLoading.value = true
    showForm.value = false
    router.visit(route('admin.jadwal.index'))
    form.reset()
    checkNotif()
    refreshLoading.value = false
}
</script>

<template>
    <Head title="Jadwal" />
    <AdminLayout pageTitle="Jadwal">
        <template #pageContent>
            <div class="flex justify-between items-center">
                <Toast/>
                <!--left  -->
                <h1 class="text-2xl">Data Jadwal Pelajaran</h1>
                <!-- right -->
            </div>

            <!--modal tambah tahun ajar  -->
            <Dialog v-model:visible="showForm" modal header="Data Jadwal" :style="{width:'36rem'}">
                <form @submit.prevent="uploadJadwal">
                    <span class="text-surface-500 dark:text-surface-400 block mb-8">Tambahkan data jadwal pelajaran </span>
                    <div v-if="form.errors">
                        <Message closable v-for="error in form.errors" :key="error" severity="error" class="my-2">{{ error }}</Message>
                    </div>
                    <div class="flex items-center gap-4 mb-4">
                        <label for="hari" class="font-semibold w-36">Hari</label>
                        <Select id="hari" :options="hari" v-model="form.hari" optionValue="nama"
                        optionLabel="nama" placeholder="Pilih Hari" fluid class="flex-auto"/> 
                    </div>

                    <div class="flex items-center gap-4 mb-4">
                        <label for="kelas" class="font-semibold w-36">Kelas</label>
                        <Select id="kelas" filter :options="pageProps.dataKelas" v-model="form.id_kelas" optionValue="id_kelas"
                        optionLabel="nama_kelas" placeholder="Pilih Kelas" fluid class="flex-auto"/> 
                    </div>

                    <div class="flex items-center gap-4 mb-4">
                        <label for="mapel" class="font-semibold w-36">Mapel</label>
                        <Select id="mapel" filter :options="dataMapelFix" v-model="form.id_mapel" optionValue="id_mapel"
                        optionLabel="label" placeholder="Pilih mapel" fluid class="flex-auto"/> 
                    </div>

                    <div class="flex items-center gap-4 mb-4">
                        <label for="tahun_ajar" class="font-semibold w-36">Tahun Ajaran</label>
                        <Select id="tahun_ajar" filter :options="tahunAjarFix" v-model="form.id_tahun_ajar" optionValue="id_tahun_ajar"
                        optionLabel="label" placeholder="Pilih Tahun Ajaran" fluid class="flex-auto"/> 
                    </div>

                    <div class="flex justify-end gap-2">
                        <Button type="button" label="Reset" severity="danger" @click="form.reset()" />
                        <Button type="submit" severity="info" label="Simpan Data"/>
                    </div>
                </form>
            </Dialog>

            <!-- table data tahun ajar -->
            <DataTable v-model:filters="filters" sortMode="multiple" ref="dt" paginator :rows="10" :value="dataJadwalFix" removableSort scrollable
             size="small" stripedRows tableStyle="min-width: 50rem" class="mt-4" v-if="pageProps.dataJadwal.length > 0">
                <template #header>
                    <div class="flex justify-between items-center mb-5">
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari Jadwal" />
                        </IconField>

                        <div class="flex items-center gap-x-4">
                            <Button :loading="refreshLoading" icon="pi pi-refresh" severity="secondary" outlined size="small" @click="refreshPage"/>
                            <Button label="Tambah Jadwal Pelajaran" size="small" icon="pi pi-plus" @click="showForm = true"/>
                            <Button label="Export CSV" size="small" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                        </div>
                    </div>
                </template>
                <template #empty>
                    <Message severity="secondary">Tidak ada data jadwal</Message> 
                </template>
             <Column field="index" sortable header="No" class="min-w-[80px]" />
             <Column field="hari" sortable header="Hari" class="min-w-[100px]" />
             <Column field="waktu_mulai" sortable header="Waktu mulai" class="min-w-[150px]" />
             <Column field="waktu_selesai" sortable header="Waktu selesai" class="min-w-[150px]" />
             <Column field="kelas" sortable header="Kelas" class="min-w-[100px]"/>
             <Column field="mapel" sortable header="Mapel" class="min-w-[100px]"/>
             <Column field="nama_guru" sortable header="Guru" class="min-w-[200px]"/>
             <Column field="semester" sortable header="Semester" class="min-w-[100px]"/>
             <Column field="label" sortable header="Tahun Ajaran" class="min-w-[150px]"/>
             <Column header="Action" class="min-w-[80px]" frozen alignFrozen="right">
                    <template #body="{data}">
                        <NavLink class="border-none p-0 m-0" :href="`absensi/daftar_absen/`+data.id_jadwal" :active="route().current('admin.absensi.daftar')">
                            <Button label="Absensi" size="small" icon="pi pi-eye" iconPos="right" severity="contrast"/>
                        </NavLink>
                        <NavLink class="border-none p-0 m-0" :href="`jadwal/`+data.id_jadwal" :active="route().current('admin.jadwal.view')">
                            <Button label="Edit" class="w-full" size="small" icon="pi pi-pen-to-square" iconPos="right" severity="info"/>
                        </NavLink>
                    </template>
                </Column>

            </DataTable>

            <Message severity="secondary" class="mt-8" v-else>Tidak Ada Data Jadwal</Message>

        </template>
    </AdminLayout>
</template>

<style scoped>
</style>