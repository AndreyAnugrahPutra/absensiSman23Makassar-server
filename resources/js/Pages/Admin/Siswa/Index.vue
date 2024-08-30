<script setup>
import { ref, onMounted } from 'vue'
import { useForm, router, usePage } from '@inertiajs/vue3'
import NavLink from '@/Components/NavLink.vue';
import AdminLayout from '../../../Layouts/Admin/AdminLayout.vue'
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select'
import FileUpload from 'primevue/fileupload'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Message from 'primevue/message'
import DatePicker from 'primevue/datepicker'
import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'
import Image from 'primevue/image';

import {FilterMatchMode} from '@primevue/core/api'

import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

const pageProps = defineProps({
    defaultLevel : Array, 
    dataSiswa : Array, 
    dataKelas : Array, 
    dataOrangtua : Array, 
    flash : Object})

onMounted(()=>
{
    // cekDataOrtu()
    // dataSiswaFix.value = cekDataOrtu()
    dataSiswaFix.value = pageProps.dataSiswa?.map((p, i) => ({ 
        ortu1 : cekNamaOrtu(p.orangtua_1),
        ortu2 : cekNamaOrtu(p.orangtua_2),
        ortu1Status : cekStatusOrtu(p.orangtua_1),
        ortu2Status : cekStatusOrtu(p.orangtua_2),
        index : i+1, 
        ...p}))
    checkNotif()
})

const toast = useToast();

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const showToast = () => {

    if(notif_status.value == 'success') {
        toast.add({ severity: 'success', summary: 'Info', detail: notif_message, life: 4000 });
    }
    else if(notif_status.value == 'failed') {
        toast.add({ severity: 'error', summary: 'Info', detail: notif_message, life: 4000 });
    }
}


let dataSiswaFix = ref([])

let showForm = ref(false)

const jkel = [{ jenis : 'Laki-Laki' }, { jenis : 'Perempuan' }]

const selectUserLevel = pageProps?.defaultLevel[0]['id_level']

const form = useForm({
    induk : null,
    nisn : null,
    nama : null,
    id_kelas : null,
    tgl_lahir : null,
    jkel : null,
    email : null,
    password : null,
    no_telp : null,
    alamat : null,
    foto_profil : null,
    level : selectUserLevel,
});

const refreshLoading = ref(false)

const cekNamaOrtu = (ortuId) => 
{
    const findOrtu = pageProps.dataOrangtua.find(orangtua => orangtua.user_id === ortuId)

    return findOrtu ? findOrtu.nama : 'Orangtua Belum Terdaftar'
}

const cekStatusOrtu = (ortuId) => 
{
    const findOrtu = pageProps.dataOrangtua.find(orangtua => orangtua.user_id === ortuId)

    return findOrtu ? `(${findOrtu.status})` : ' '
}


const refreshPage = () =>
{
    showForm.value = false
    refreshLoading.value = true
    router.visit(route('admin.siswa.index'))
    form.reset()
    checkNotif()
    setTimeout(() => refreshLoading.value = false, 500)
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

const uploadDatasiswa = () =>
{
    form.post(route('admin.siswa.create'), 
    {
        onSuccess : () => refreshPage()
    })
}

</script>

<template>
    <Head title="Siswa" />

    <AdminLayout pageTitle="Siswa">
        <template #pageContent>
            <div class="flex justify-between items-center">
                <Toast/>
                <Toast position="top-center" group="center" />
                <!--left  -->
                <h1 class="text-2xl">Data siswa/i</h1>
                <!--right  -->
                <div class="flex items-center gap-x-4">
                    <Button :loading="refreshLoading" icon="pi pi-refresh" severity="secondary" outlined size="small" @click="refreshPage"/>
                    <Button label="Tambah siswa/i" size="small" icon="pi pi-plus" @click="showForm = true"/>
                    <Button label="Export CSV" size="small" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                </div>

                <!-- modal tambah siswa -->
                <Dialog v-model:visible="showForm" modal header="Data siswa" :style="{ width: '36rem' }">
                    <form @submit.prevent="uploadDatasiswa" class="flex flex-col gap-y-2">
                        <span class="text-surface-500 dark:text-surface-400 block mb-8">Tambahkan data siswa/i</span>
                        <span class="text-red-400 my-4" v-if="form.hasErrors">{{ form.errors.induk }}</span>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="induk" class="font-semibold w-36">Nomor induk</label>
                            <InputText v-model="form.induk" id="induk" class="flex-auto" autocomplete="off" placeholder="Masukkan nomor induk" />
                        </div>
                        <span class="text-red-400 my-4" v-if="form.hasErrors">{{ form.errors.nisn }}</span>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="nisn" class="font-semibold w-36">NISN</label>
                            <InputText v-model="form.nisn" id="nisn" class="flex-auto" autocomplete="off" placeholder="Masukkan NISN" />
                        </div>
                        <span class="text-red-400 my-4" v-if="form.hasErrors">{{ form.errors.nama }}</span>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="nama" class="font-semibold w-36">Nama</label>
                            <InputText v-model="form.nama" id="nama" class="flex-auto" autocomplete="off" placeholder="Masukkan Nama siswa" />
                        </div>
                        <span class="text-red-400 my-4" v-if="form.hasErrors">{{ form.errors.id_kelas }}</span>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="id_kelas" class="font-semibold w-36">Kelas</label>
                            <Select filter id="id_kelas" v-model="form.id_kelas" :options="pageProps.dataKelas"
                            optionValue="id_kelas" optionLabel="nama_kelas" placeholder="Masukkan Kelas" class="flex-auto"/>
                        </div>
                        <span class="text-red-400 my-4" v-if="form.hasErrors">{{ form.errors.jkel }}</span>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="jkel" class="font-semibold w-36">Jenis Kelamin</label>
                            <Select id="jkel" v-model="form.jkel" :options="jkel" optionValue="jenis" optionLabel="jenis" placeholder="Laki-Laki/Perempuan" class="flex-auto" />
                        </div>
                        <span class="text-red-400 my-4" v-if="form.hasErrors">{{ form.errors.tgl_lahir }}</span>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="tglLahir" class="font-semibold w-36">Tanggal Lahir</label>
                            <DatePicker v-model="form.tgl_lahir" class="flex-auto"  id="tglLahir" showIcon fluid showButtonBar iconDisplay="input" dateFormat="yy-mm-dd" placeholder="Masukkan Tanggal lahir" />
                        </div>
                        <span class="text-red-400 my-4" v-if="form.hasErrors">{{ form.errors.alamat }}</span>
                        <div class="flex items-center gap-4 mb-8">
                            <label for="alamat" class="font-semibold w-36">Alamat</label>
                            <InputText v-model="form.alamat" id="alamat" class="flex-auto" autocomplete="off" placeholder="Masukkan Alamat" />
                        </div>
                        <span class="text-red-400 my-4" v-if="form.hasErrors">{{ form.errors.email }}</span>
                        <div class="flex items-center gap-4 mb-8">
                            <label for="email" class="font-semibold w-36">Email</label>
                            <InputText v-model="form.email" id="email" class="flex-auto" autocomplete="off" placeholder="Masukkan Email" />
                        </div>
                        <span class="text-red-400 my-4" v-if="form.hasErrors">{{ form.errors.password }}</span>
                        <div class="flex items-center gap-4 mb-8">
                            <label for="password" class="font-semibold w-36">Password</label>
                            <InputText v-model="form.password" id="password" class="flex-auto" autocomplete="off" placeholder="Masukkan Password" />
                        </div>
                        <span class="text-red-400 my-4" v-if="form.hasErrors">{{ form.errors.no_telp }}</span>
                        <div class="flex items-center gap-4 mb-8">
                            <label for="noTelp" class="font-semibold w-36">Nomor Telepon</label>
                            <InputText v-model="form.no_telp" id="noTelp" class="flex-auto" autocomplete="off" placeholder="Masukkan Nomor Telepon" />
                        </div>
                        <span class="text-red-400 my-4" v-if="form.hasErrors">{{ form.errors.foto_profil }}</span>
                        <div class="flex items-center gap-4 mb-8">
                            <label for="foto_profil" class="font-semibold w-36">Foto Profil</label>
                            <FileUpload id="foto_profil" name="demo[]" accept="image/*" :maxFileSize="1000000" customUpload @uploader="uploadFoto($event)" :disabled="form.foto_profil" show-upload-button/>
                        </div>
                        <div class="flex justify-end gap-2">
                            <slot name="closeModal"/>
                            <Button type="button" label="Reset" severity="danger" @click="form.reset()"/>
                            <Button type="submit" label="Simpan Data" severity="info"/>
                        </div>
                    </form>
                </Dialog>
            </div>
            <!-- Table data siswa -->
            <DataTable v-model:filters="filters" ref="dt" paginator :rows="10" :value="dataSiswaFix" removableSort scrollable  size="small" stripedRows tableStyle="min-width: 50rem" class="mt-4">
                <template #header>
                    <div class="flex mb-5">
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari siswa/i" />
                        </IconField>
                    </div>
                </template>
                <template #empty>
                    <Message severity="secondary" class="p-2">Tidak ada data siswa/i </Message> 
                </template>
                <Column field="index" sortable header="No" class="min-w-[80px]"/>
                <Column header="Foto" class="min-w-[120px]">
                    <template #body="{data}">
                        <Image preview :alt="data.nama" :src="data.foto_path" class="max-w-[80px]"v-if="data.foto_path"/>
                        <span class="text-gray-400 text-sm" v-else>
                            Tidak Ada Foto
                        </span>
                    </template>
                </Column>
                <Column field="induk" sortable header="Induk" class="min-w-[150px]"/>
                <Column field="nisn" sortable header="NISN" class="min-w-[150px]"/>
                <Column field="nama" sortable header="Nama" class="min-w-[200px]"/>
                <Column field="jkel" sortable header="Jenis kelamin" class="min-w-[200px]"/>
                <Column field="nama_kelas" sortable header="Kelas" class="min-w-[200px]"/>
                <Column sortable header="Orangtua" class="min-w-[200px]">
                    <template #body={data}>
                        <span>{{`${data.ortu1} ${data.ortu1Status}`}}</span>
                    </template>
                </Column>
                <Column sortable header="Orangtua" class="min-w-[200px]">
                    <template #body={data}>
                        <span>{{`${data.ortu2} ${data.ortu2Status}`}}</span>
                    </template>
                </Column>
                <Column header="Action" class="min-w-[80px]" frozen alignFrozen="right">
                    <template #body="{data}">
                            <NavLink class="border-none p-0 m-0" :href="`siswa/`+data.user_id" :active="route().current('admin.siswa.view')">
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