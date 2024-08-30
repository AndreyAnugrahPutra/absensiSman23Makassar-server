<script setup>
import { ref, onMounted } from 'vue'
import { router, useForm } from '@inertiajs/vue3'

import GuruLayout from '@/Layouts/Guru/GuruLayout.vue'

import Button from 'primevue/button'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Message from 'primevue/message'
import InputText from 'primevue/inputtext'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"


import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'

import {FilterMatchMode} from '@primevue/core/api'

import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

const pageProps = defineProps({dataFormAbsen : Array, dataJadwal : Array, dataLokasi : Array, flash : Object})

onMounted(()=>
{
    dataFormAbsenFix.value = pageProps?.dataFormAbsen?.map((p, i) => ({ 
        index : i+1,
        tahun_ajar : `${p.mulai}/${p.selesai}`,
        ...p}))
    checkNotif
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const dataFormAbsenFix = ref([])

const toast = useToast()

const confirm = useConfirm()

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)
const refreshLoading = ref(false)

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
    id_jadwal : pageProps?.dataJadwal[0]['id_jadwal'],
    id_kelas : pageProps?.dataJadwal[0]['id_kelas'],
    kelas : pageProps?.dataJadwal[0]['kelas'],
    id_mapel : pageProps?.dataJadwal[0]['id_mapel'],
    mapel : pageProps?.dataJadwal[0]['mapel'],
    id_lokasi : pageProps?.dataLokasi[0]['id_lokasi']
})

const confirmHapus = (id_form) => 
{
    confirm.require({
        message: 'Yakin ingin menghapus data ?',
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Hapus',
            severity: 'danger'
        },
        accept: () => {
            hapusFormAbsen(id_form)
        },

    });
};

const refreshPage = () => 
{
    refreshLoading.value = true
    router.visit(pageProps?.dataJadwal[0]['id_jadwal'])
    checkNotif()
    setTimeout(() => refreshLoading.value = false, 500)
}

const hapusFormAbsen = (id_form) =>
{
    console.log(id_form)
    form.post(`/guru/mapel/absensi/${id_form}/delete`,
    {
        onSuccess : () => refreshPage()
    }
    )
} 

const uploadFormAbsensi = () =>
{
    form.post(route('guru.absen.create'),
    {
        onSuccess : () => refreshPage()
    })
}
</script>

<template>
    <Head title="Form Absensi" />

    <GuruLayout pageTitle="Form Absensi">
        <template #pageContent>
            <Toast />
            <ConfirmDialog />
            <div class="flex justify-between items-center">
                <Button label="Tambah Form Absensi" size="small" icon="pi pi-plus" @click="uploadFormAbsensi" />
                <Button :loading="refreshLoading" size="small" icon="pi pi-refresh" @click="refreshPage" severity="secondary" />
            </div>
            <DataTable v-model:filters="filters" ref="dt" paginator :rows="10" :value="dataFormAbsenFix" removableSort scrollable  size="small" stripedRows tableStyle="min-width: 50rem" class="mt-4">
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
                    <Message severity="secondary" class="p-2">Tidak ada data form absensi</Message> 
                </template>
                <Column field="index" sortable header="No" class="min-w-[80px]"></Column>
                <Column field="created_at" sortable header="Tanggal dibuat" class="min-w-[200px]"></Column>
                <Column field="mapel" sortable header="Nama Mapel" class="min-w-[200px]"></Column>
                <Column field="kelas" sortable header="Kelas" class="min-w-[200px]"></Column>
                <Column field="nama_guru" sortable header="Nama Guru" class="min-w-[200px]"/>
                <Column field="waktu_mulai" sortable header="Waktu Mulai" class="min-w-[200px]"/>
                <Column field="waktu_selesai" sortable header="Waktu Selesai" class="min-w-[200px]"/>
                <Column field="tahun_ajar" sortable header="Tahun Ajaran" class="min-w-[200px]"/>
                <Column header="Action" class="min-w-[120px] flex flex-col gap-y-2" frozen alignFrozen="right">
                    <template #body="{data}">
                            <Button as="a" :href="`absensi/${data.id_form}`" label="Absensi" size="small" icon="pi pi-eye" iconPos="right" severity="info"/>
                            <Button label="Hapus" size="small" icon="pi pi-trash" iconPos="right" severity="danger" @click="confirmHapus(data.id_form)"/>
                    </template>
                </Column>
            </DataTable>
        </template>
    </GuruLayout>
</template>

<style scoped>
</style>