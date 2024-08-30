<script setup>
import { ref, onMounted } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import NavLink from '@/Components/NavLink.vue';
import AdminLayout from '../../../Layouts/Admin/AdminLayout.vue'
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Message from 'primevue/message'
import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'

import InputError from '@/Components/Form/InputError.vue'

import {FilterMatchMode} from '@primevue/core/api'

import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

const pageProps = defineProps({
    dataKelas : Array, 
    dataTahunAjar : Array, 
    dataGuru : Array, 
    flash : Object
})

onMounted(()=>
{
    dataKelasFix.value = pageProps.dataKelas?.map((p, i) => ({ index : i+1, ...p}))
    tahunAjarFix.value = pageProps.dataTahunAjar?.map((p, i) => ({ 
        tahun_ajar : `${p.mulai}/${p.selesai}`, ...p}));
    checkNotif()
})

const dataKelasFix = ref([])
const tahunAjarFix = ref([])

const toast = useToast();

const showForm = ref(false)

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const form = useForm({
    nama_kelas : '',
    id_wali_kelas : null,
    nama_wali_kelas : null,
    tahun_ajar : null,
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
        toast.add({ severity: 'success', summary: 'Info', detail: notif_message, life: 2000 });
    }
    else if(notif_status.value == 'failed') {
        toast.add({ severity: 'error', summary: 'Info', detail: notif_message, life: 2000 });
    }
}

const exportCSV = () =>
{
    dt.value.exportCSV()
}

const refreshPage = () =>
{
    showForm.value = false
    refreshLoading.value = true
    router.visit(route('admin.kelas.index'))
    form.reset()
    checkNotif()
    setTimeout(() => refreshLoading.value = false, 800)
}

const uploadDataKelas = () =>
{
    form.post(route('admin.kelas.create'), 
    {
        onSuccess : () => refreshPage()
    })
}


</script>

<template>
     <Head title="Kelas" />

    <AdminLayout pageTitle="Kelas">
        <template #pageContent>
            <div class="flex justify-between items-center">
                <Toast/>
                <!--left  -->
                <h1 class="text-2xl">Data Kelas</h1>
                <!--right  -->
                <div class="flex items-center gap-x-4">
                    <Button :loading="refreshLoading" icon="pi pi-refresh" severity="secondary" outlined size="small" @click="refreshPage"/>
                    <Button label="Tambah Kelas" size="small" icon="pi pi-plus" @click="showForm = true"/>
                    <Button label="Export CSV" size="small" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                </div>

                <Dialog v-model:visible="showForm" modal header="Data Kelas" :style="{ width: '36rem' }">
                    <form @submit.prevent="uploadDataKelas" class="flex flex-col gap-6">
                        <span class="text-surface-500 dark:text-surface-400 block">Tambahkan Data Kelas</span>
                        <InputError class="mt-2" :message="form.errors.nama_kelas"/>
                        <InputError :message="form.errors.id_wali_kelas"/>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="nama_kelas" class="font-semibold w-36">Nama Kelas</label>
                            <InputText v-model="form.nama_kelas" id="nama_kelas" class="flex-auto" autocomplete="off" placeholder="Masukkan Nama Kelas" />
                        </div>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="id_wali_kelas" class="font-semibold w-36">Wali Kelas</label>
                            <Select id="id_wali_kelas" v-model="form.id_wali_kelas" :options="pageProps.dataGuru" optionValue="user_id" optionLabel="nama" :placeholder="`Wali Kelas `+form.nama_kelas" class="flex-auto" />
                        </div>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="tahun_ajaran" class="font-semibold w-36">Tahun Ajaran</label>
                            <Select id="tahun_ajaran" v-model="form.tahun_ajar" :options="tahunAjarFix" optionValue="tahun_ajar" optionLabel="tahun_ajar" placeholder="Tahun Ajaran" class="flex-auto" />
                        </div>
                        <div class="flex justify-end gap-2">
                            <Button type="button" label="Reset" severity="danger" @click="form.reset()"/>
                            <Button type="submit" label="Simpan Data" severity="info"/>
                        </div>
                    </form>
                </Dialog>
            </div>

            <DataTable v-model:filters="filters" ref="dt" :value="dataKelasFix" paginator :rows="10" removableSort scrollable  size="small" stripedRows tableStyle="min-width: 50rem" class="mt-4">
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
                    <Message severity="secondary" class="p-2">Tidak ada data kelas</Message> 
                </template>
                <Column field="index" sortable header="No" class="min-w-[80px]"></Column>
                <Column field="nama_kelas" sortable header="Nama Kelas" class="min-w-[150px]"></Column>
                <Column field="nama_wali_kelas" sortable header="Wali Kelas" class="min-w-[200px]"></Column>
                <Column field="siswa_count" sortable header="Jumlah Siswa"></Column>
                <Column field="tahun_ajaran" sortable header="Tahun Ajaran"></Column>
                <Column header="Action" class="w-[160px] flex flex-col" frozen alignFrozen="right">
                    <template #body="{data}">
                            <NavLink class="border-none p-0 m-0" :href="`kelas/view/`+data.id_kelas">
                                <Button label="Data Siswa" size="small" icon="pi pi-eye" iconPos="right" severity="contrast" outlined/>
                            </NavLink>
                            <NavLink class="border-none p-0 m-0" :href="`kelas/`+data.id_kelas">
                                <Button label="Edit" size="small" icon="pi pi-pen-to-square" iconPos="right" severity="info"/>
                            </NavLink>
                    </template>
                </Column>
            </DataTable>
        </template>
    </AdminLayout> 
</template>

<style scoped>

</style>