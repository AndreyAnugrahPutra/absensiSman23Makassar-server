<script setup>
import { ref, onMounted } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

import NavLink from '@/Components/NavLink.vue'
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue'


import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select'

import Message from 'primevue/message'

import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'

import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"

const pageProps = defineProps({
    dataTahunAjar : Array,
    dataJadwal : Array,
    dataGuru : Array,
    dataKelas : Array,
    dataMapel : Array,
})

onMounted(()=>
{
    tahunAjarFix = pageProps.dataTahunAjar.map((p,i) => 
    ({ label : `${p.semester} ${p.mulai}/${p.selesai}`, ...p}))
    // checkNotif()
})

const confirm = useConfirm();

const isReset = ref(false)

const hari = [
    {nama : "Senin"},
    {nama : "Selasa"},
    {nama : "Rabu"},
    {nama : "Kamis"},
    {nama : "Jumat"},
]

let tahunAjarFix = ref([])

const form = useForm({
    hari : pageProps.dataJadwal[0]['hari'],
    id_kelas : pageProps.dataJadwal[0]['id_kelas'],
    id_mapel : pageProps.dataJadwal[0]['id_mapel'],
    id_tahun_ajar : pageProps.dataJadwal[0]['id_tahun_ajar'],
})

const updateData = () =>
{
    form.post(`/admin/jadwal/${pageProps.dataJadwal[0]['id_jadwal']}/update`)
}

const hapusData = () =>
{
    form.post(`/admin/jadwal/${pageProps.dataJadwal[0]['id_jadwal']}/delete`)
}

const resetForm = () => 
{
    isReset.value = true
    setTimeout(()=>
    {
        form.reset()
        isReset.value = false
    } ,1000)
}

const confirmHapus = () => 
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
            hapusData()
            // router.reload()
        },

    });
};

</script>

<template>
    <Head title="Edit Data Jadwal" />
    <AdminLayout pageTitle="Edit Data Jadwal">
        <template #pageContent>
            <ConfirmDialog></ConfirmDialog>
            <div class="flex justify-between items-center">
            <!-- <Toast v-if="notif_show == true"/> -->
            <!--left  -->
            <h1 class="text-2xl">Edit Data</h1>
            <!-- right -->
            <Button size="small" icon-pos="right" severity="danger" :loading="isReset" icon="pi pi-refresh" @click="resetForm" label="Reset form" />
            </div>
            <form @submit.prevent="updateData" class="flex flex-col gap-8 py-8">
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
                        <Select id="mapel" filter :options="pageProps.dataMapel" v-model="form.id_mapel" optionValue="id_mapel"
                        optionLabel="nama_mapel" placeholder="Pilih mapel" fluid class="flex-auto"/> 
                    </div>

                    <div class="flex items-center gap-4 mb-4">
                        <label for="tahun_ajar" class="font-semibold w-36">Tahun Ajaran</label>
                        <Select id="tahun_ajar" filter :options="tahunAjarFix" v-model="form.id_tahun_ajar" optionValue="id_tahun_ajar"
                        optionLabel="label" placeholder="Pilih Tahun Ajaran" fluid class="flex-auto"/> 
                    </div>

                    <div class="flex flex-col gap-2">
                        <Button type="submit" label="Simpan Data" severity="info"/>
                        <Button type="button" label="Hapus Data" severity="danger" @click="confirmHapus" />
                    </div>
                </form>
        </template> 
    </AdminLayout>  
</template>

<style scoped>
</style>