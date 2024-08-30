<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import GuruLayout from '@/Layouts/Guru/GuruLayout.vue'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Message from 'primevue/message'
import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'
import Image from 'primevue/image'

import {FilterMatchMode} from '@primevue/core/api'

import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

const pageProps = defineProps({dataKelas : Array, dataOrangtua : Array, flash : Object})

onMounted(()=>
{
    dataKelasFix.value = pageProps.dataKelas?.map((p, i) => ({ 
        ortu1 : cekNamaOrtu(p.orangtua_1),
        ortu2 : cekNamaOrtu(p.orangtua_2),
        ortu1Status : cekStatusOrtu(p.orangtua_1),
        ortu2Status : cekStatusOrtu(p.orangtua_2),
        index : i+1, 
        ...p}))
    // dataKelasFix.value = pageProps.dataKelas?.map((p, i) => ({ index : i+1, ...p}))
    namaKelas.value = dataKelasFix?.value[0]?.nama_kelas
    checkNotif()
})

const dataKelasFix = ref([])

const namaKelas = ref()

const toast = useToast();

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const refreshLoading = ref(false)

let dt = ref()


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

const exportCSV = () =>
{
    dt.value.exportCSV()
}

const refreshPage = () =>
{
    refreshLoading.value = true
    router.visit(route('guru.kelas.index'))
    setTimeout(() => refreshLoading.value = false, 500)
}


</script>

<template>
     <Head title="Kelas" />

    <GuruLayout pageTitle="Kelas">
        <template #pageContent>
            <div class="flex justify-between items-center">
                <Toast v-if="notif_show == true"/>
                <!--left  -->
                <h1 class="text-2xl" v-if="dataKelasFix.length > 0">Data Siswa Kelas {{ namaKelas+' '+dataKelasFix[0]?.tahun_ajaran}}</h1>
                <h1 class="text-2xl" v-else>Data Siswa</h1>
                <!--right  -->
                <div class="flex items-center gap-x-4">
                    <Button :loading="refreshLoading" icon="pi pi-refresh" severity="secondary" outlined size="small" @click="refreshPage"/>
                    <Button :disabled="dataKelasFix.length < 1" label="Export CSV" size="small" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
                </div>
            </div>

            <DataTable v-model:filters="filters" ref="dt" paginator :rows="10" :value="dataKelasFix" removableSort scrollable  size="small" stripedRows tableStyle="min-width: 50rem" class="mt-4">
                <template #header>
                    <div class="flex mb-5">
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari siswa/i" :disabled="dataKelasFix.length < 1"/>
                        </IconField>
                    </div>
                    <div class="my-4">
                        <span> Jumlah siswa : {{ dataKelasFix.length }}</span>
                    </div>
                </template>
                <template #empty>
                    <Message severity="secondary" class="p-2">Tidak ada data siswa</Message> 
                </template>
                <Column field="index" sortable header="No" class="min-w-[80px]"></Column>
                <Column header="Foto" class="min-w-[140px]">
                    <template #body="{data}">
                        <Image preview :alt="data.nama" :src="data.foto_path" class="w-[80px]" v-if="data.foto_path"/>
                        <span class="text-gray-400" v-else>Tidak ada foto</span>  
                    </template>
                </Column>
                <Column field="induk" sortable header="Induk" class="min-w-[200px]"/>
                <Column field="nisn" sortable header="NISN" class="min-w-[200px]"/>
                <Column field="nama" sortable header="Nama" class="min-w-[280px]"/>
                <Column field="jkel" sortable header="Jenis Kelamin" class="min-w-[150px]"/>
                <Column field="tgl_lahir" sortable header="Tanggal lahir" class="min-w-[150px]"/>
                <Column sortable header="Alamat" class="min-w-[200px]">
                    <template #body="{data}">
                        <span class="text-black" v-if="data.alamat">{{ data.alamat }}</span>
                        <span class="text-gray-400" v-else>Alamat belum diinput</span>
                    </template>
                </Column>
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
            </DataTable>
            <!-- <Message severity="secondary" class="mt-8" v-else>Tidak Ada Data Siswa</Message>   -->
        </template>
    </GuruLayout> 
</template>

<style scoped>

</style>