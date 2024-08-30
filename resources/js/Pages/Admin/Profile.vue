<script setup>
import { onMounted, ref } from 'vue'
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue'
import {router, useForm } from '@inertiajs/vue3'

import DatePicker from 'primevue/datepicker'

import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select'
import Image from 'primevue/image'
import FileUpload from 'primevue/fileupload'
import Message from 'primevue/message'

import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

let pageProps = defineProps({dataProfil : Array, flash : Object})

onMounted(()=>
{
    checkNotif()
})

let dataProfilTemp = pageProps.dataProfil[0]

const foto_profil_temp = ref()

const toast = useToast();

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)

const jkel = [{ jenis : 'Laki-Laki' }, { jenis : 'Perempuan' }]

const form = useForm({
    user_id : dataProfilTemp.user_id,
    nip : dataProfilTemp.nip,
    nama : dataProfilTemp.nama,
    tgl_lahir : dataProfilTemp.tgl_lahir,
    jkel : dataProfilTemp.jkel,
    email : dataProfilTemp.email,
    password : null,
    no_telp : dataProfilTemp.no_telp,
    alamat : dataProfilTemp.alamat,
    foto_profil : dataProfilTemp.foto_profil,
    foto_path : dataProfilTemp.foto_path,
});

let previewFoto = form.foto_path

const resetForm = () => 
{
    isReset.value = true
    setTimeout(()=>
    {
        form.reset()
        isReset.value = false
        isUploaded.value = false
    } ,1000)
}

const uploadFoto = (event) => {

    // console.log(event)

    form.foto_profil = event.files[0]

    let readImg = new FileReader()

    readImg.readAsDataURL(event.files[0])

    readImg.onloadend = () =>
    {
        previewFoto = readImg.result
    }
};

const uploadDataAdmin = () =>
{
    form.post(`/admin/profile/`+pageProps.dataProfil[0]['user_id']+`/update`, {
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
        toast.add({ severity: 'success', summary: 'Info', detail: notif_message, life: 4000, group : 'tc' });
    }
    else if(notif_status.value == 'failed') {
        toast.add({ severity: 'error', summary: 'Info', detail: notif_message, life: 4000, group: 'tc' });
    }
}
</script>

<template>
    <Head title="Admin Profile" />

    <AdminLayout pageTitle="Profile">
        <template #pageContent>
            <Toast v-if="notif_show == true" position="top-center" group="tc"/>
            <div class="flex justify-between items-center">
                <h1 class="text-2xl">Profile</h1>
            </div>
            <!-- <span>{{ form.foto_profil }}</span> -->
            <div v-if="form.errors">
                <Message closable v-for="error in form.errors" :key="error" severity="error" class="mt-4">{{ error }}</Message>
            </div>
            <form submit.prevent class="flex flex-col gap-8 py-8">
                <div class="flex items-center flex-col gap-4">
                    <div class="border-2 border-gray-300 size-[12rem] overflow-hidden rounded">
                        <Image :src="previewFoto" alt="Image" width="250" preview v-if="form.foto_profil && previewFoto" />
                        <div v-else class="flex items-center justify-center bg-gray-400 size-full text-xl text-gray-50">Tidak ada foto</div>
                    </div>
                    <h1>{{ form.nama }}</h1>
                    <div class="flex flex-col items-center gap-4">
                        <FileUpload id="foto_profil" choose-label="Pilih File" name="demo[]" accept="image/*" :maxFileSize="1000000" :multiple="false" customUpload @uploader="uploadFoto($event)" show-upload-button/>
                    </div>
                </div>
                <div class="flex items-center md:flex-wrap gap-x-[8rem] gap-y-6 px-4">
                    <div class="flex items-center gap-4">
                        <label for="nip" class="font-semibold w-36">NIP</label>
                        <InputText v-model="form.nip" id="nip" class="flex-auto" autocomplete="off" :value="form.nip" disabled />
                    </div>
                    <div class="flex items-center gap-4">
                        <label for="tgl_lahir" class="font-semibold w-36">TANGGAL LAHIR</label>
                        <DatePicker v-model="form.tgl_lahir" class="flex-auto"  id="tglLahir" showIcon fluid showButtonBar iconDisplay="input" dateFormat="yy-mm-dd" placeholder="Masukkan Tanggal lahir" :model-value="form.tgl_lahir" />
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
                        <label for="password" class="font-semibold w-36">PASSWORD</label>
                        <InputText v-model="form.password" id="password" class="flex-auto" autocomplete="off" placeholder="silahkan isi untuk mengganti password" value=""/>
                    </div>
                    <div class="flex items-center gap-4">
                        <label for="alamat" class="font-semibold w-36">ALAMAT</label>
                        <InputText v-model="form.alamat" id="alamat" class="flex-auto" autocomplete="off" :value="form.alamat"/>
                    </div>
                    <div class="flex items-center gap-4 mr-auto">
                        <label for="jkel" class="font-semibold w-36">JENIS KELAMIN</label>
                        <Select id="jkel" v-model="form.jkel" :options="jkel" optionValue="jenis" optionLabel="jenis" placeholder="Laki-Laki/Perempuan" class="flex-auto"/>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <Button label="Update Profile" severity="info" @click="uploadDataAdmin" :disabled="form.processing"/>
                </div>
            </form>
        </template>
    </AdminLayout>

</template>
