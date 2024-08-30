<script setup>
import { ref, onMounted } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

import AdminLayout from '@/Layouts/Admin/AdminLayout.vue'

import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm";

onMounted(()=>
{
    tahunAjarFix.value = pageProps.dataTahunAjar?.map((p, i) => ({ 
        tahun_ajar : `${p.mulai}/${p.selesai}`, ...p}));
})


const confirm = useConfirm();

const pageProps = defineProps({dataGuru : Array, dataKelas : Array, dataTahunAjar : Array })

// const confirmHapus = ref(false)

const isReset = ref(false)

const tahunAjarFix = ref([])

const form = useForm({
    id_kelas : pageProps?.dataKelas[0]['id_kelas'],
    nama_kelas : pageProps?.dataKelas[0]['nama_kelas'],
    id_wali_kelas : pageProps?.dataKelas[0]['id_wali_kelas'],
    tahun_ajaran : pageProps?.dataKelas[0]['tahun_ajaran']
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

const uploadDataKelas = () =>
{
    form.post(`/admin/kelas/`+form.id_kelas+`/update`, {
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
            hapusDataKelas()
            // router.reload()
        },

    });
};

const hapusDataKelas = () =>
{
    form.post(`/admin/kelas/`+form.id_kelas+`/delete`, {
        onFinish : () => {
            form.reset()
        },
    })
}
</script>

<template>
    <Head title="Data Kelas"/>
    <AdminLayout pageTitle="Data Kelas">
        <template #pageContent>
            <ConfirmDialog></ConfirmDialog>
            <div class="flex justify-between items-center">
                <!-- left -->
                <h1 class="text-2xl mb-8">Detail Data Kelas</h1>
                <!-- right -->
                <Button size="small" icon-pos="right" severity="danger" :loading="isReset" icon="pi pi-refresh" @click="resetForm" label="Reset form" />
            </div>
            <form submit.prevent="updateDataKelas" class="flex flex-col gap-8 py-8">
                <div class="flex items-center gap-4">
                    <label for="nama_kelas" class="font-semibold w-36">NAMA KELAS</label>
                    <InputText v-model="form.nama_kelas" id="nama_kelas" class="flex-auto" autocomplete="off" :value="form.nama_kelas" />
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label for="wali_kelas" class="font-semibold w-36">WALI KELAS</label>
                    <Select id="wali_kelas" v-model="form.id_wali_kelas" :options="pageProps.dataGuru" optionValue="user_id" optionLabel="nama" class="flex-auto" />
                </div>
                <div class="flex items-center gap-4 mb-4">
                    <label for="tahun_ajaran" class="font-semibold w-36">WALI KELAS</label>
                    <Select id="tahun_ajaran" v-model="form.tahun_ajaran" :options="tahunAjarFix" optionValue="tahun_ajar" optionLabel="tahun_ajar" class="flex-auto" />
                </div>
                <div class="flex flex-col gap-2">
                    <Button label="Update Data" severity="info" @click="uploadDataKelas" :disabled="form.processing"/>
                    <Button label="Hapus Data" severity="danger" @click="confirmHapus"/>
                </div>
            </form>
        </template>
    </AdminLayout>
</template>

<style scoped>
</style>