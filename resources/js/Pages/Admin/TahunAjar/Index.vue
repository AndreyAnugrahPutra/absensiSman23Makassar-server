<script setup>
import { ref, onMounted, watchEffect } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

import NavLink from '@/Components/NavLink.vue'
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue'


import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select'
import ToggleSwitch from 'primevue/toggleswitch'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'


import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Message from 'primevue/message'
import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'

import {FilterMatchMode} from '@primevue/core/api'


import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

const pageProps = defineProps({dataTahunAjar : Array ,flash : Object})

onMounted(()=>
{
    dataTahunAjarFix.value = pageProps?.dataTahunAjar.map((p, i) => ({ 
        index : i+1, 
        aktif : p.is_active === 1 ? true : false,
        ...p}))
    checkNotif()
})

const refreshLoading = ref(false)

const confirm = useConfirm()
const toast = useToast()

const showForm = ref(false)

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const semester = [{ nama : "Ganjil"} ,{nama : "Genap"}]

let dataTahunAjarFix = ref([])

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

const form = useForm({
    semester : null,
    mulai : null,
    selesai : null,
    is_active : null,
})

const exportCSV = () =>
{
    dt.value.exportCSV()
}

const uploadTahunAjar = () =>
{
    form.post(route('admin.tahun_ajar.create'),
    {
        onSuccess : () => refreshPage()
    })
}

const refreshPage = () =>
{
    showForm.value = false
    refreshLoading.value = true
    router.visit(route('admin.tahun_ajar.index'))
    refreshLoading.value = false
    checkNotif()
}

const confirmActive = (id) => 
{
    confirm.require({
        message: 'Aktifkan Tahun Ajaran ?',
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Aktifkan',
            severity: 'info'
        },
        accept: () => {
            tahunAjarAktif(id)
        },

    });
};

const tahunAjarAktif = (id) =>
{
    form.post(`/admin/tahun_ajar/${id}/active`, 
    {
        onSuccess : () => refreshPage()
    })
}
</script>

<template>
    <Head title="Tahun Ajaran" />
    <AdminLayout pageTitle="Tahun Ajaran">
        <template #pageContent v-if="pageProps">
            <div class="flex justify-between items-center">
                <Toast/>
                <ConfirmDialog />
                <!--left  -->
                <h1 class="text-2xl">Data Tahun Ajaran</h1>
                <!-- right -->
                <div class="flex items-center gap-x-4">
                    <Button :loading="refreshLoading" icon="pi pi-refresh" severity="secondary" outlined size="small" @click="refreshPage"/>
                    <Button label="Tambah Tahun Ajaran" size="small" icon="pi pi-plus" @click="showForm = true"/>
                    <Button label="Export CSV" size="small" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                </div>
            </div>

            <!--modal tambah tahun ajar  -->
            <Dialog v-model:visible="showForm" modal header="Data Tahun Ajaran" :style="{width:'36rem'}">
                <form @submit.prevent="uploadTahunAjar">
                    <span class="text-surface-500 dark:text-surface-400 block mb-8">Tambahkan data tahun ajaran</span>
                    <div v-if="form.errors">
                        <Message closable v-for="error in form.errors" :key="error" severity="error" class="my-2">{{ error }}</Message>
                    </div>
                    <div class="flex items-center gap-4 mb-4">
                        <label for="semester" class="font-semibold w-36">Semester</label>
                        <Select id="semester" :options="semester" v-model="form.semester" optionValue="nama"
                        optionLabel="nama" placeholder="Ganjil/Genap" fluid class="flex-auto"/> 
                    </div>
                    <div class="flex items-center gap-4 mb-4">
                        <label for="mulai" class="font-semibold w-36">Tahun Mulai</label>
                        <InputText v-model="form.mulai" id="mulai" class="flex-auto" autocomplete="off" placeholder="Masukkan Tahun Mulai"/>
                    </div>
                    <div class="flex items-center gap-4 mb-4">
                        <label for="selesai" class="font-semibold w-36">Tahun Selesai</label>
                        <InputText v-model="form.selesai" id="selesai" class="flex-auto" autocomplete="off" placeholder="Masukkan Tahun Selesai"/>
                    </div>
                    <div class="flex justify-end gap-2">
                        <Button type="button" label="Reset" severity="danger" @click="form.reset()" />
                        <Button type="submit" label="Simpan Data"/>
                    </div>
                </form>
            </Dialog>

            <!-- table data tahun ajar -->
            <DataTable v-model:filters="filters" ref="dt" paginator :rows="10" :value="dataTahunAjarFix" removableSort scrollable
             size="small" stripedRows tableStyle="min-width: 50rem" class="mt-4" v-if="pageProps.dataTahunAjar.length > 0">
                <template #header>
                    <div class="flex mb-5">
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari Data" />
                        </IconField>
                    </div>
                </template>
                <template #empty>
                    <Message severity="secondary" class="mt-8">Tidak ada data tahun ajaran</Message> 
                </template>
                <Column field="index" sortable header="No" class="min-w-[80px]" />
                <Column field="semester" sortable header="Semester" class="min-w-[100px]" />
                <Column field="mulai" sortable header="Tahun mulai" class="min-w-[100px]"/>
                <Column field="selesai" sortable header="Tahun selesai" class="min-w-[100px]"/>
                <Column header="Action" class="min-w-[80px]" frozen alignFrozen="right">
                        <template #body="{data}">
                                <NavLink class="border-none p-0 m-0" :href="`tahun_ajar/`+data.id_tahun_ajar" :active="route().current('admin.tahun_ajar.view')">
                                    <Button label="Edit" size="small" icon="pi pi-pen-to-square" iconPos="right" severity="info"/>
                                </NavLink>
                                <ToggleSwitch v-model="data.aktif" @change="confirmActive(data.id_tahun_ajar)"/>
                        </template>
                </Column>

            </DataTable>

        </template>
    </AdminLayout>
</template>

<style scoped>
</style>