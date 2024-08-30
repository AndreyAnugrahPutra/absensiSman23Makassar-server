<script setup>
import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'

import AdminLayout from '@/Layouts/Admin/AdminLayout.vue'

import DatePicker from 'primevue/datepicker'

import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select'
import Image from 'primevue/image'
import FileUpload from 'primevue/fileupload'
import Message from 'primevue/message'


import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"

import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

const confirm = useConfirm();

let pageProps = defineProps({dataSiswa : Array, dataOrangtua : Array, flash : Object})

onMounted(()=>
{
    checkNotif()
    dataOrangTua.value = pageProps.dataOrangtua
})

const toast = useToast();

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)

const showToast = () => {

    if(notif_status.value == 'success') {
        toast.add({ severity: 'success', summary: 'Info', detail: notif_message, life: 4000 });
    }
    else if(notif_status.value == 'failed') {
        toast.add({ severity: 'error', summary: 'Info', detail: notif_message, life: 4000 });
    }
    // else toast.add({ severity: 'info', summary: 'Info', detail: 'testtttt', life: 3000, styleClass : 'rounded-lg ', group: 'br', });
}

let dataSiswaTemp = pageProps.dataSiswa

// let confirmHapus = ref(false)

const isReset = ref(false)

const jkel = [{ jenis : 'Laki-Laki' }, { jenis : 'Perempuan' }]
const dataOrangTua = ref([])

const form = useForm({
    user_id : dataSiswaTemp[0]['user_id'],
    induk : dataSiswaTemp[0]['induk'],
    nisn : dataSiswaTemp[0]['nisn'],
    nama : dataSiswaTemp[0]['nama'],
    tgl_lahir : dataSiswaTemp[0]['tgl_lahir'],
    jkel : dataSiswaTemp[0]['jkel'],
    email : dataSiswaTemp[0]['email'],
    no_telp : dataSiswaTemp[0]['no_telp'],
    alamat : dataSiswaTemp[0]['alamat'],
    ortu1 : dataSiswaTemp[0]['orangtua_1'],
    ortu2 : dataSiswaTemp[0]['orangtua_2'],
    og_foto_profil : dataSiswaTemp[0]['foto_profil'],
    og_foto_path : dataSiswaTemp[0]['foto_path'],
    foto_profil : dataSiswaTemp[0]['foto_profil'],
    foto_path : dataSiswaTemp[0]['foto_path'],
    level : pageProps.dataSiswa[0]['level'],
});

let previewFoto = form.foto_path

const resetForm = () => 
{
    isReset.value = true
    setTimeout(()=>
    {
        form.reset()
        isReset.value = false
    } ,1000)
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
// const isUploaded = ref(false)

const uploadFoto = (event) => {

    form.foto_profil = event.files[0]

    let readImg = new FileReader()

    readImg.readAsDataURL(event.files[0])

    readImg.onloadend = () =>
    {
        previewFoto = readImg.result
    }
    
};

const uploadDataSiswa = () =>
{
    form.post(`/admin/siswa/`+pageProps.dataSiswa[0]['user_id']+`/update`, {
        onFinish : () => {
            form.reset()
            checkNotif()
        },
        onError : () =>
        {
            checkNotif()
        }
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
        hapusDataSiswa()
        // router.reload()
    },

});
};

const hapusDataSiswa = () =>
{
    form.post(`/admin/siswa/`+form.user_id+`/delete`, {
        onFinish : () => {
            form.reset()
        },
    })
}



</script>

<template>
    <Head title="Detail Siswa" />

    <AdminLayout :pageTitle="`Detail Siswa : `+pageProps.dataSiswa[0]['nama']">
        <template #pageContent>
            <Toast v-if="notif_show == true"/>
            <ConfirmDialog></ConfirmDialog>
            <div class="flex justify-between items-center">
                <!-- left -->
                <h1 class="text-2xl mb-8">Detail Data Siswa</h1>
                <!-- right -->
                <Button size="small" icon-pos="right" severity="danger" :loading="isReset" icon="pi pi-refresh" @click="resetForm" label="Reset form" />
            </div>
            <div v-if="form.errors">
                <Message closable v-for="error in form.errors" :key="error" severity="error" class="mt-4">{{ error }}</Message>
            </div>
            <form submit.prevent class="flex flex-col gap-8 py-8">
                <div class="flex items-center flex-col gap-4">
                    <div class="border-2 border-gray-300 size-[12rem] overflow-hidden rounded">
                        <!-- <img src="../../../../img/tes.png" alt="foto Siswa" class="size-fit"> -->
                        <Image :src="previewFoto" alt="Image" width="250" preview v-if="previewFoto" />
                        <div v-else class="flex items-center justify-center bg-gray-400 size-full text-xl text-gray-50">Tidak ada foto</div>
                    </div>
                    <h1>{{ form.nama }}</h1>
                    <div class="flex flex-col items-center gap-4">
                        <!-- <label for="foto_profil" class="font-semibold w-36">FOTO PROFIL</label> -->
                        <FileUpload :multiple="false" id="foto_profil" choose-label="Pilih File" name="demo[]" accept="image/*" :maxFileSize="1000000" customUpload @uploader="uploadFoto($event)" show-upload-button/>
                        <!-- <Button size="small" label="Upload Selesai!" disabled severity="info" v-else/> -->
                    </div>
                </div>
                <div class="flex items-center md:flex-wrap gap-x-[8rem] gap-y-6 px-4">
                    <div class="flex items-center gap-4">
                        <label for="induk" class="font-semibold w-36">NOMOR INDUK</label>
                        <InputText v-model="form.induk" id="induk" class="flex-auto" autocomplete="off" :value="form.induk" />
                    </div>
                    <div class="flex items-center gap-4">
                        <label for="nisn" class="font-semibold w-36">NISN</label>
                        <InputText v-model="form.nisn" id="nisn" class="flex-auto" autocomplete="off" :value="form.nisn" />
                    </div>
                    <div class="flex items-center gap-4">
                        <label for="tgl_lahir" class="font-semibold w-36">TANGGAL LAHIR</label>
                        <DatePicker v-model="form.tgl_lahir" class="flex-auto w-[15.6rem]"  id="tglLahir" showIcon fluid showButtonBar iconDisplay="input" dateFormat="yy-mm-dd" placeholder="Masukkan Tanggal lahir" :model-value="form.tgl_lahir" />
                    </div>
                    <div class="flex items-center gap-4">
                        <label for="nama" class="font-semibold w-36">NAMA</label>
                        <InputText v-model="form.nama" id="nama" class="flex-auto" autocomplete="off" :value="form.nama" />
                    </div>
                    <div class="flex items-center gap-4">
                        <label for="no_telp" class="font-semibold w-36">NO TELEPON</label>
                        <InputText v-model="form.no_telp" id="no_telp" class="flex-auto" autocomplete="off" :value="form.no_telp" />
                    </div>
                    <div class="flex items-center gap-4">
                        <label for="email" class="font-semibold w-36">EMAIL</label>
                        <InputText v-model="form.email" id="email" class="flex-auto" autocomplete="off" :value="form.email" />
                    </div>
                    <div class="flex items-center gap-4">
                        <label for="alamat" class="font-semibold w-36">ALAMAT</label>
                        <InputText v-model="form.alamat" id="alamat" class="flex-auto" autocomplete="off" :value="form.alamat" />
                    </div>
                    <div class="flex items-center gap-4">
                        <label for="jkel" class="font-semibold w-36">JENIS KELAMIN</label>
                        <Select id="jkel" v-model="form.jkel" :options="jkel" optionValue="jenis" optionLabel="jenis" placeholder="Laki-Laki/Perempuan" class="flex-auto w-[15.6rem]"/>
                    </div>
                    <div class="flex items-center gap-4">
                        <label for="ortu1" class="font-semibold w-36">Orangtua</label>
                        <Select id="ortu1" v-model="form.ortu1" :options="dataOrangTua" optionValue="user_id" optionLabel="nama" placeholder="Ayah/Ibu" class="flex-auto w-[15.6rem]"/>
                    </div>
                    <div class="flex items-center gap-4">
                        <label for="ortu2" class="font-semibold w-36">Orangtua</label>
                        <Select id="ortu2" v-model="form.ortu2" :options="dataOrangTua" optionValue="user_id" optionLabel="nama" placeholder="Ayah/Ibu" class="flex-auto w-[15.6rem]"/>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <Button label="Update Data" severity="info" @click="uploadDataSiswa" :disabled="form.processing"/>
                    <Button label="Hapus Data" severity="danger" @click="confirmHapus"/>
                </div>
            </form>
        </template>
    </AdminLayout>
</template>

<style scoped>
</style>