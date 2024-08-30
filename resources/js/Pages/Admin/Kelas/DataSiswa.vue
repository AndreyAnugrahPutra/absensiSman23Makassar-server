<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '../../../Layouts/Admin/AdminLayout.vue'
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

const pageProps = defineProps({dataKelas : Array, flash : Object})

onMounted(()=>
{
    dataKelasFix.value = pageProps.dataKelas?.map((p, i) => ({ index : i+1, ...p}))
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
    setTimeout(()=>
        {
            router.reload()
            refreshLoading.value = false
        }
    ,1000)
}


</script>

<template>
     <Head title="Kelas" />

    <AdminLayout pageTitle="Kelas">
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

            <DataTable v-model:filters="filters" ref="dt" paginator :rows="20" :value="dataKelasFix" removableSort scrollable  size="small" stripedRows tableStyle="min-width: 50rem" class="mt-4">
                <template #header>
                    <div class="flex mb-5">
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari Data" :disabled="dataKelasFix.length < 1"/>
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
                <Column header="Foto" class="min-w-[100px]">
                    <template #body="{data}">
                        <Image preview :alt="data.nama" :src="data.foto_path" class="w-[80px]"/>
                    </template>
                </Column>
                <Column field="induk" sortable header="Induk" class="min-w-[200px]"></Column>
                <Column field="nisn" sortable header="NISN" class="min-w-[200px]"></Column>
                <Column field="nama" sortable header="Nama"></Column>
                <Column field="jkel" sortable header="Jenis Kelamin"></Column>
            </DataTable>
            <!-- <Message severity="secondary" class="mt-8" v-else>Tidak Ada Data Siswa</Message>   -->
        </template>
    </AdminLayout> 
</template>

<style scoped>

</style>