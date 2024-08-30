<script setup>
import { ref, onMounted } from 'vue'
import {router} from '@inertiajs/vue3'

import NavLink from '@/Components/NavLink.vue'

import AdminLayout from '@/Layouts/Admin/AdminLayout.vue'

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
    dataAbsensiFix.value = pageProps?.dataAbsensi?.map((p,i) =>
    ({
        index : i+1, ...p
    }))
    namaMapel.value = dataAbsensiFix?.value[0]?.mapel
})

const pageProps = defineProps({ dataAbsensi : Array})

const dataAbsensiFix = ref([])
const namaMapel = ref()

const filters = ref({
    global : { value: null, matchMode: FilterMatchMode.CONTAINS },
});

</script>

<template>
<Head title="Absensi" />
    <AdminLayout pageTitle="Absensi">
        <template #pageContent>
            <h1 class="text-2xl">Daftar Absensi {{ namaMapel }}</h1>
            <!-- table data tahun ajar -->
            <DataTable v-model:filters="filters" ref="dt" paginator :rows="10" :value="dataAbsensiFix" removableSort scrollable
             size="small" stripedRows tableStyle="min-width: 50rem" class="mt-4">
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
                    <Message severity="secondary" class="p-2">Tidak ada data absensi</Message> 
                </template>
                <Column field="index" sortable header="No" class="min-w-[80px]" />
                <Column field="mapel" sortable header="Mata Pelajaran" class="min-w-[50px]" />
                <Column field="kelas" sortable header="Kelas" class="min-w-[50px]" />
                <Column field="created_at" sortable header="Tanggal Dibuat" class="min-w-[50px]" />
                <Column header="Action" class="max-w-fit">
                    <template #body="{data}">
                        <NavLink class="border-none p-0 m-0" :href="`view/`+data.id_form">
                            <Button label="Lihat Absensi" size="small" icon="pi pi-eye" iconPos="right" severity="info"/>
                        </NavLink>
                    </template>
                </Column>
            </DataTable>
        </template>
    </AdminLayout>
</template>

<style scoped>
</style>