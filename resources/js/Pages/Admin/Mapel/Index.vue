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

import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'

import {FilterMatchMode} from '@primevue/core/api'

import DatePicker from 'primevue/datepicker'

import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

const pageProps = defineProps({dataGuru : Array, dataMapel : Array, flash : Object})

onMounted(()=>
{
    dataMapelFix.value = pageProps.dataMapel?.map((p, i) => ({ index : i+1, ...p}))
    checkNotif()
})

const dataMapelFix = ref([])

const toast = useToast()

const showForm = ref(false)

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
})

const form = useForm({
    nama_mapel : null,
    id_guru : null,
    waktu_mulai : null,
    waktu_selesai : null,
})

const refreshLoading = ref(false)

let dt = ref()

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

const exportCSV = () =>
{
    dt.value.exportCSV()
}

const refreshPage = () =>
{
    refreshLoading.value = true
    showForm.value = false
    router.visit(route('admin.mapel.index'))
    form.reset()
    checkNotif()
    setTimeout(() => refreshLoading.value = false, 800)
}

const uploadDataMapel = () =>
{
    form.post(route('admin.mapel.create'), 
    {
        onSuccess : () => refreshPage()
    })
}

</script>

<template>
     <Head title="Mata pelajaran" />

    <AdminLayout pageTitle="Mata pelajaran">
        <template #pageContent>
            <div class="flex justify-between items-center">
                <Toast/>
                <!--left  -->
                <h1 class="text-2xl">Data Mata pelajaran</h1>
                <!--right  -->
                <div class="flex items-center gap-x-4">
                    <Button :loading="refreshLoading" icon="pi pi-refresh" severity="secondary" outlined size="small" @click="refreshPage"/>
                    <Button label="Tambah Mapel" size="small" icon="pi pi-plus" @click="showForm = true"/>
                    <Button label="Export CSV" size="small" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                </div>

                <Dialog v-model:visible="showForm" modal header="Data Mapel" :style="{ width: '36rem' }">
                    <form @submit.prevent="uploadDataMapel" class="flex flex-col gap-6">
                        <span class="text-surface-500 dark:text-surface-400 block">Tambahkan Data Mapel</span>
                        <div v-if="form.errors">
                            <Message closable v-for="error in form.errors" :key="error" severity="error" class="mt-4">{{ error }}</Message>
                        </div>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="nama_mapel" class="font-semibold w-44">Nama Mapel</label>
                            <InputText v-model="form.nama_mapel" id="nama_mapel" class="flex-auto" autocomplete="off" placeholder="Masukkan Nama Mapel" />
                        </div>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="id_guru_mapel" class="font-semibold w-44">Guru Mapel</label>
                            <Select filter id="id_guru_mapel" v-model="form.id_guru" :options="pageProps.dataGuru" optionValue="user_id" optionLabel="nama" placeholder="Guru Mapel" class="flex-auto" />
                        </div>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="waktu_mulai" class="font-semibold w-44">Waktu Mulai Mapel</label>
                            <DatePicker id="waktu_mulai" v-model="form.waktu_mulai" timeOnly fluid class="flex-auto" />
                        </div>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="waktu_selesai" class="font-semibold w-44">Waktu Selesai Mapel</label>
                            <DatePicker id="waktu_selesai" v-model="form.waktu_selesai" timeOnly fluid class="flex-auto" />
                        </div>
                        <div class="flex justify-end gap-2">
                            <Button type="button" label="Reset" severity="danger" @click="form.reset()"/>
                            <Button type="submit" label="Simpan Data" severity="info"/>
                        </div>
                    </form>
                </Dialog>
            </div>

            <DataTable v-model:filters="filters" ref="dt" paginator :rows="10" :value="dataMapelFix" removableSort scrollable  size="small" stripedRows tableStyle="min-width: 50rem" class="mt-4" v-if="dataMapelFix?.length > 0">
                <template #header>
                    <div class="flex mb-5">
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari mata pelajaran" />
                        </IconField>
                    </div>
                </template>
                <template #empty>
                    <Message severity="secondary">Tidak ada data mata pelajaran</Message> 
                </template>
                <Column field="index" sortable header="No" class="min-w-[80px]"></Column>
                <Column field="nama_mapel" sortable header="Nama Mapel" class="min-w-[200px]"></Column>
                <Column field="nama_guru" sortable header="Nama Guru" class="min-w-[200px]"/>
                <Column field="waktu_mulai" sortable header="Waktu Mulai" class="min-w-[200px]"/>
                <Column field="waktu_selesai" sortable header="Waktu Selesai" class="min-w-[200px]"/>
                <Column header="Action" class="min-w-[80px]" frozen alignFrozen="right">
                    <template #body="{data}">
                            <NavLink class="border-none p-0 m-0" :href="`mapel/`+data.id_mapel">
                                <Button label="Edit" size="small" icon="pi pi-pen-to-square" iconPos="right" severity="info"/>
                            </NavLink>
                    </template>
                </Column>
            </DataTable>
            <Message severity="secondary" class="mt-8" v-else>Tidak Ada Data Mapel</Message>  
        </template>
    </AdminLayout> 
</template>

<style scoped>

</style>