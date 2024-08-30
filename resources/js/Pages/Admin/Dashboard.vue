<script setup>
import { onMounted, ref } from 'vue'
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'

import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

import Card from 'primevue/card'
import Message from 'primevue/message'

import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

const pageProps = defineProps({
    jmlhGuru : Number, jmlhSiswa : Number, tahunAjar : Object,
    jmlhKelas : Number, jmlhMapel : Number, absenTerakhir : Array,
    flash : Object
})

onMounted(()=>
{
    checkNotif()
    absenTerakhirFix.value = pageProps.absenTerakhir?.map((p, i) => ({ index : i+1, ...p}))
    // setInterval(()=>{
    // },5000)
})

const toast = useToast();

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)

const absenTerakhirFix = ref([])

const showToast = () => {

    if(notif_status.value == 'success') {
        toast.add({ severity: 'success', summary: 'Info', detail: notif_message, life: 4000, group : 'tc' });
    }
    else if(notif_status.value == 'failed') {
        toast.add({ severity: 'error', summary: 'Info', detail: notif_message, life: 4000 });
    }
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

</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout pageTitle="Dashboard">
        <template #pageContent>
            <Toast position="top-center" group="tc"/>
            <Card class="rounded-lg p-4 border overflow-hidden shadow" unstyled>
                <template #content>
                    <div class="flex items-center justify-between">
                        <span>
                            Semester : {{ pageProps.tahunAjar.semester }}
                        </span>
                        <span>
                            Tahun Ajaran : {{ pageProps.tahunAjar.mulai+'/'+pageProps.tahunAjar.selesai }}
                        </span>
                    </div>
                </template> 
            </Card>
            <div class="flex flex-col gap-y-8 p-8">
                <div class="flex flex-wrap items-center gap-y-8 gap-x-20 font-semibold">
                    <Link :href="route('admin.guru.index')">
                        <Card class="w-[18rem] p-6 rounded-md shadow-md bg-blue-400" unstyled>
                            <template #content>
                                <div class="flex justify-between items-center text-white">
                                    <i class="pi pi-users" style="font-size: 2rem;"></i>
                                    <div class="flex flex-col items-center">
                                        <h1>GURU</h1>
                                        <h1>{{ pageProps.jmlhGuru }}</h1>
                                    </div>
                                </div>
                            </template>
                        </Card>
                    </Link>
                    <Link :href="route('admin.siswa.index')">
                        <Card class="w-[18rem] p-6 rounded-md shadow-md bg-green-400" unstyled>
                            <template #content>
                                <div class="flex justify-between items-center text-white">
                                    <i class="pi pi-users" style="font-size: 2rem;"></i>
                                    <div class="flex flex-col items-center">
                                        <h1>SISWA</h1>
                                        <h1>{{ pageProps.jmlhSiswa }}</h1>
                                    </div>
                                </div>
                            </template>
                        </Card>
                    </Link>
                    <Link :href="route('admin.mapel.index')">
                        <Card class="w-[18rem] p-6 rounded-md shadow-md bg-yellow-400" unstyled>
                            <template #content>
                                <div class="flex justify-between items-center text-white">
                                    <i class="pi pi-book" style="font-size: 2rem;"></i>
                                    <div class="flex flex-col items-center">
                                        <h1>MATA PELAJARAN</h1>
                                        <h1>{{ pageProps.jmlhMapel }}</h1>
                                    </div>
                                </div>
                            </template>
                        </Card>
                    </Link>
                    <Link :href="route('admin.kelas.index')">
                        <Card class="w-[18rem] p-6 rounded-md shadow-md bg-emerald-400" unstyled>
                            <template #content>
                                <div class="flex justify-between items-center text-white">
                                    <i class="pi pi-home" style="font-size: 2rem;"></i>
                                    <div class="flex flex-col items-center">
                                        <h1>KELAS</h1>
                                        <h1>{{ pageProps.jmlhKelas }}</h1>
                                    </div>
                                </div>
                            </template>
                        </Card>
                    </Link>
                </div>
                <Card class="rounded-lg border overflow-hidden shadow p-4" unstyled>
                    <template #content>
                        <DataTable :value="absenTerakhirFix" removableSort scrollable  size="small" stripedRows tableStyle="min-width: 50rem;">
                            <template #header>
                                <div class="p-4">
                                    <h1 class="font-bold">DAFTAR ABSEN MASUK</h1>
                                </div>
                            </template>
                            <template #empty>
                                <Message severity="secondary" class="p-4">Belum ada absensi masuk</Message>
                            </template>
                            <Column sortable field="index" header="No" />
                            <Column sortable field="nama_siswa" header="Nama" />
                            <Column sortable field="status" header="Status" />
                            <Column sortable field="waktu_absen" header="Waktu Absen" />
                            <Column sortable field="kelas" header="Kelas" />
                            <Column sortable field="mapel" header="Mata Pelajaran" />
                        </DataTable>
                    </template>
                </Card>
            </div>
        </template>
    </AdminLayout>

</template>
