<script setup>
import { ref, onMounted } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

import AdminLayout from '@/Layouts/Admin/AdminLayout.vue'

import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"

import DatePicker from 'primevue/datepicker'

const confirm = useConfirm();

const pageProps = defineProps({dataGuru : Array, dataMapel : Array })

const isReset = ref(false)

const form = useForm({
    id_mapel : pageProps?.dataMapel[0]['id_mapel'],
    nama_mapel : pageProps?.dataMapel[0]['nama_mapel'],
    id_guru : pageProps?.dataMapel[0]['id_guru'],
    nama_guru : pageProps?.dataMapel[0]['nama_guru'],
    waktu_mulai : pageProps?.dataMapel[0]['waktu_mulai'],
    waktu_selesai : pageProps?.dataMapel[0]['waktu_selesai'],
})

const resetForm = () =>
{
    isReset.value = true
    setTimeout(()=>
    {
        form.reset()
        isReset.value = false
    } ,1000)
}

const uploadDataMapel = () =>
{
    form.post(`/admin/mapel/`+form.id_mapel+`/update`, {
        onFinish : () => {
            form.reset()
        },
    })
}
const confirmHapus = () => {

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
            hapusDataMapel()
            // router.reload()
        },

    });
};

const hapusDataMapel = () =>
{
    form.post(`/admin/mapel/`+form.id_mapel+`/delete`, {
        onFinish : () => {
            form.reset()
        },
    })
}
</script>

<template>
    <Head title="DataMapel"/>
    <AdminLayout pageTitle="DataMapel">
        <template #pageContent>
            <ConfirmDialog></ConfirmDialog>
            <div class="flex justify-between items-center">
                <!-- left -->
                <h1 class="text-2xl mb-8">Detail DataMapel</h1>
                <!-- right -->
                <Button size="small" icon-pos="right" severity="danger" :loading="isReset" icon="pi pi-refresh" @click="resetForm" label="Reset form" />
            </div>
            <form submit.prevent="updateDataKelas" class="flex flex-col gap-8 py-8">
                <div class="flex items-center gap-4">
                    <label for="nama_mapel" class="font-semibold w-36">NAMA MAPEL</label>
                    <InputText v-model="form.nama_mapel" id="nama_mapel" class="flex-auto" autocomplete="off" :value="form.nama_mapel" />
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label for="guru_mapel" class="font-semibold w-36">GURU MAPEL</label>
                    <Select id="guru_mapel" v-model="form.id_guru" :options="pageProps.dataGuru" optionValue="user_id" optionLabel="nama" class="flex-auto" />
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label for="waktu_mulai" class="font-semibold w-44">Waktu Mulai Mapel</label>
                    <DatePicker id="waktu_mulai" v-model="form.waktu_mulai" timeOnly fluid class="flex-auto" />
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label for="waktu_selesai" class="font-semibold w-44">Waktu Selesai Mapel</label>
                    <DatePicker id="waktu_selesai" v-model="form.waktu_selesai" timeOnly fluid class="flex-auto" />
                </div>
                <div class="flex flex-col gap-2">
                    <Button label="Update Data" severity="info" @click="uploadDataMapel" :disabled="form.processing"/>
                    <Button label="Hapus Data" severity="danger" @click="confirmHapus"/>
                </div>
            </form>
        </template>
    </AdminLayout>
</template>

<style scoped>
</style>