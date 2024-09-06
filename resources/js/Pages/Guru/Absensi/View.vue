<script setup>
import { ref, onMounted } from 'vue'
import {router} from '@inertiajs/vue3'

import NavLink from '@/Components/NavLink.vue'

import GuruLayout from '@/Layouts/Guru/GuruLayout.vue'

import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select'

import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Message from 'primevue/message'

import IconField from 'primevue/iconfield'
import InputIcon from 'primevue/inputicon'
import {FilterMatchMode} from '@primevue/core/api'


onMounted(()=>
{
    dataAbsenFix.value = pageProps?.dataAbsen.map((p,i) =>
    ({
        index : i+1, ...p
    }))

    jumlahStatusFix.value = pageProps.jumlahStatus
})

const pageProps = defineProps({ dataAbsen : Array, jumlahStatus : Array})

const dataAbsenFix = ref([])
const jumlahStatusFix = ref([])

const refreshLoading = ref(false)

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

const dt = ref()
const filters = ref({
    global : { value: null, matchMode: FilterMatchMode.CONTAINS },
})

const exportCSV = () =>
{
    dt.value.exportCSV()
}

</script>

<template>
<Head title="Absensi" />
    <GuruLayout pageTitle="Absensi">
        <template #pageContent>
            <h1 class="text-2xl">Data Absensi</h1>
            <!-- table data tahun ajar -->
            <DataTable v-model:filters="filters" ref="dt" paginator :rows="10" :value="dataAbsenFix" removableSort scrollable
             size="small" stripedRows tableStyle="min-width: 50rem" class="mt-4">
                <template #header>
                    <div class="flex justify-between items-center mb-5">
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari Data" :disabled="!dataAbsenFix.value" />
                        </IconField>

                        <div class="flex items-center gap-x-4">
                            <Button :loading="refreshLoading" icon="pi pi-refresh" severity="secondary" outlined size="small" @click="refreshPage"/>
                            <Button label="Export CSV" size="small" icon="pi pi-upload" severity="help" @click="exportCSV($event)" :disabled="!dataAbsenFix.value" />
                        </div>
                    </div>

                    <!-- jumlah kehadiran -->
                    <div class="flex flex-col py-4" v-for="statusAbsen in jumlahStatusFix" :key="statusAbsen.index">
                        <span>{{ statusAbsen.status+' : '+statusAbsen.jumlah }}</span>
                    </div>
                </template>
                <template #empty>
                    <Message severity="secondary" class="p-2">Tidak ada data absensi</Message> 
                </template>
                <Column field="index" sortable header="No" class="min-w-[80px]" />
                <Column field="induk" sortable header="Induk" class="min-w-[50px]" />
                <Column field="nisn" sortable header="NISN" class="min-w-[50px]" />
                <Column field="nama_siswa" sortable header="Nama" class="min-w-[240px]" />
                <Column field="status" sortable header="Status" class="min-w-[100px]" />
                <Column sortable header="Deskripsi" class="min-w-[100px]">
                    <template #body="{data}">
                        <span v-if="data.deskripsi == null" class="italic">Tidak ada deskripsi</span>
                        <span v-else>{{ data.deskripsi }}</span>
                    </template>
                </Column>
                <Column sortable header="Lampiran" class="min-w-[200px]">
                    <template #body="{data}">
                        <Button label="Lihat Lampiran" severity="help" size="small" icon="pi pi-eye" iconPos="right" target="_blank" v-if="data.lampiran_path" as="a" :href="data.lampiran_path"/>
                        <span class="italic" v-else>Tidak ada lampiran</span>
                    </template>
                </Column>
                <Column field="waktu_absen" sortable header="Waktu Absen" class="min-w-[100px]" />
            </DataTable>
        </template>
    </GuruLayout>
</template>

<style scoped>
</style>