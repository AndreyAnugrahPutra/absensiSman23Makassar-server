<script setup>
import { ref, onMounted } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import NavLink from '@/Components/NavLink.vue';
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue'

import DatePicker from 'primevue/datepicker'

import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select'
import FileUpload from 'primevue/fileupload'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Message from 'primevue/message'
import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'
import Image from 'primevue/image'


import {FilterMatchMode} from '@primevue/core/api'



import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

const pageProps = defineProps({defaultLevel : Array, dataGuru : Array, flash : Object})

onMounted(()=>
{
    dataGuruFix.value = pageProps.dataGuru.map((p, i) => ({ index : i+1, ...p}))
    checkNotif()
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});


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
}


let dataGuruFix = ref([])

let showForm = ref(false)

const jkel = [{ jenis : 'Laki-Laki' }, { jenis : 'Perempuan' }]

const selectUserLevel = pageProps.defaultLevel

const form = useForm({
    nip : null,
    nama : null,
    tgl_lahir : null,
    jkel : null,
    email : null,
    password : null,
    no_telp : null,
    alamat : null,
    foto_profil : null,
    level : null,
});

const refreshLoading = ref(false)

const refreshPage = () =>
{
    showForm.value = false
    refreshLoading.value = true
    router.visit(route('admin.guru.index'))
    form.reset()
    checkNotif()
    setTimeout(() => refreshLoading.value = false, 800)
}

const checkNotif = () =>
{
    if(pageProps.flash.show)
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

let dt = ref()

const exportCSV = () =>
{
    dt.value.exportCSV()
}

const uploadFoto = (event) => {
    form.foto_profil = event.files[0]

    if(form.foto_profil)
    {
        toast.add({ severity: 'info', summary: 'Info', detail: 'foto terupload!', life: 2000, group : 'center' });
    }
};

const uploadDataGuru = () =>
{
    form.post(route('admin.guru.create'),
    {
        onSuccess : () => refreshPage()
    })
}

</script>

<template>
    <Head title="Guru" />

    <AdminLayout pageTitle="Guru">
        <template #pageContent>
            <div class="flex justify-between items-center">
                <Toast/>
                <Toast position="top-center" group="center"/>
                <!--left  -->
                <h1 class="text-2xl">Data Guru</h1>
                <!--right  -->
                <div class="flex items-center gap-x-4">
                    <Button :loading="refreshLoading" icon="pi pi-refresh" severity="secondary" outlined size="small" @click.prevent="refreshPage"/>
                    <Button label="Tambah Guru" size="small" icon="pi pi-plus" @click="showForm = true"/>
                    <Button label="Export CSV" size="small" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                </div>

                <!-- modal tambah guru -->
                <Dialog v-model:visible="showForm" modal header="Data Guru" :style="{ width: '40rem' }">
                    <form @submit.prevent="uploadDataGuru" class="flex flex-col gap-y-2">
                        <span class="text-surface-500 dark:text-surface-400 block mb-8">Tambahkan data Guru</span>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="nip" class="font-semibold w-36">NIP</label>
                            <InputText v-model="form.nip" id="nip" class="flex-auto" autocomplete="off" placeholder="Masukkan NIP" />
                        </div>
                        <span class="text-red-500" v-if="form.errors.nama">{{ form.errors.nama }}</span>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="nama" class="font-semibold w-36">Nama</label>
                            <InputText v-model="form.nama" id="nama" class="flex-auto" autocomplete="off" placeholder="Masukkan Nama Guru" />
                        </div>
                        <span class="text-red-500" v-if="form.errors.jkel">{{ form.errors.jkel }}</span>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="jkel" class="font-semibold w-36">Jenis Kelamin</label>
                            <Select id="jkel" v-model="form.jkel" :options="jkel" optionValue="jenis" optionLabel="jenis" placeholder="Laki-Laki/Perempuan" class="flex-auto" />
                        </div>
                        <span class="text-red-500" v-if="form.errors.tgl_lahir">{{ form.errors.tgl_lahir }}</span>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="tglLahir" class="font-semibold w-36">Tanggal Lahir</label>
                            <DatePicker v-model="form.tgl_lahir" class="flex-auto"  id="tglLahir" showIcon fluid showButtonBar iconDisplay="input" dateFormat="yy-mm-dd" placeholder="Masukkan Tanggal lahir" />
                        </div>
                        <div class="flex items-center gap-4 mb-8">
                            <label for="alamat" class="font-semibold w-36">Alamat</label>
                            <InputText v-model="form.alamat" id="alamat" class="flex-auto" autocomplete="off" placeholder="Masukkan Alamat" />
                        </div>
                        <span class="text-red-500" v-if="form.errors.email">{{ form.errors.email }}</span>
                        <div class="flex items-center gap-4 mb-8">
                            <label for="email" class="font-semibold w-36">Email</label>
                            <InputText v-model="form.email" id="email" class="flex-auto" autocomplete="off" placeholder="Masukkan Email" />
                        </div>
                        <span class="text-red-500" v-if="form.errors.password">{{ form.errors.password }}</span>
                        <div class="flex items-center gap-4 mb-8">
                            <label for="password" class="font-semibold w-36">Password</label>
                            <InputText v-model="form.password" id="password" class="flex-auto" autocomplete="off" placeholder="Masukkan Password" />
                        </div>
                        <span class="text-red-500" v-if="form.errors.no_telp">{{ form.errors.no_telp }}</span>
                        <div class="flex items-center gap-4 mb-8">
                            <label for="noTelp" class="font-semibold w-36">Nomor Telepon</label>
                            <InputText v-model="form.no_telp" id="noTelp" class="flex-auto" autocomplete="off" placeholder="Masukkan Nomor Telepon" />
                        </div>
                        <span class="text-red-500" v-if="form.errors.level">{{ form.errors.level }}</span>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="jkel" class="font-semibold w-36">Level</label>
                            <Select id="jkel" v-model="form.level" :options="selectUserLevel" optionValue="id_level" optionLabel="nama_level" placeholder="Level Akses" class="flex-auto" />
                        </div>
                        <div class="flex items-center gap-4 mb-8">
                            <label for="foto_profil" class="font-semibold w-36">Foto Profil</label>
                            <FileUpload id="foto_profil" :disabled="form.foto_profil"  choose-label="Pilih File" name="demo[]" accept="image/*" :maxFileSize="1000000" customUpload @uploader="uploadFoto($event)" show-upload-button/>
                        </div>
                        <div class="flex justify-end gap-2">
                            <Button type="button" label="Reset" severity="danger" @click="form.reset()"/>
                            <Button type="submit" label="Simpan Data" severity="info"/>
                        </div>
                    </form>
                </Dialog>

                
            </div>
            <!-- Table data guru -->
            <DataTable v-model:filters="filters" paginator :rows="10" ref="dt" :value="dataGuruFix" removableSort scrollable  size="small" stripedRows tableStyle="min-width: 50rem" class="mt-4" v-if="pageProps.dataGuru.length > 0">
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
                    <Message severity="secondary">Tidak ada data guru</Message> 
                </template>
                <Column field="index" sortable header="No" class="min-w-[80px]"></Column>
                <Column header="Foto" class="min-w-[120px]">
                    <template #body="{data}">
                        <Image preview :alt="data.nama" :src="data.foto_path" width="100px"v-if="data.foto_path"/>
                        <span class="text-gray-400 text-sm" v-else>
                            Tidak Ada Foto
                        </span>
                    </template>
                </Column>
                <Column field="nip" sortable header="Nip" class="min-w-[150px]"></Column>
                <Column field="nama" sortable header="Nama" class="min-w-[200px]"></Column>
                <Column field="jkel" sortable header="Jenis kelamin" class="min-w-[200px]"></Column>
                <Column field="tgl_lahir" sortable header="Tanggal Lahir" class="min-w-[180px]"></Column>
                <Column field="email" sortable header="Email" class="min-w-[200px]"></Column>
                <Column field="alamat" sortable header="Alamat" class="min-w-[200px]"></Column>
                <Column field="no_telp" sortable header="No Telepon" class="min-w-[200px]"></Column>
                <Column header="Action" class="min-w-[80px]" frozen alignFrozen="right">
                    <template #body="{data}">
                            <NavLink class="border-none p-0 m-0" :href="`guru/`+data.user_id" :active="route().current('admin.guru.view')">
                                <Button label="Edit" size="small" icon="pi pi-pen-to-square" iconPos="right" severity="info"/>
                            </NavLink>
                    </template>
                </Column>
            </DataTable>
            <Message severity="secondary" class="mt-8" v-else>Tidak Ada Data Guru</Message>    
        </template>

    </AdminLayout>
    
</template>

<style scoped>
</style>