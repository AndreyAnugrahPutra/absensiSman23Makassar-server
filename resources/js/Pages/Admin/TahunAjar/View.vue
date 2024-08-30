<script setup>
import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'

import AdminLayout from '@/Layouts/Admin/AdminLayout.vue'

import Button from 'primevue/button'
import Select from 'primevue/select'
import InputText from 'primevue/inputtext'

import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"

import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'

const pageProps = defineProps({dataTahunAjar : Array, flash : Object})

onMounted(() => 
{
    checkNotif()
})



const isReset = ref(false)

const semester = [{ nama : "Ganjil"} ,{nama : "Genap"}]

const confirm = useConfirm()

const toast = useToast()

const form = useForm({
    semester : pageProps.dataTahunAjar[0]['semester'],
    mulai : pageProps.dataTahunAjar[0]['mulai'],
    selesai : pageProps.dataTahunAjar[0]['selesai'],
})

const updateDataTahunAjar = () =>
{
    form.post(`/admin/tahun_ajar/${pageProps.dataTahunAjar[0]['id_tahun_ajar']}/update`)
}

const hapusDataTahunAjar = () =>
{
    form.post(`/admin/tahun_ajar/${pageProps.dataTahunAjar[0]['id_tahun_ajar']}/delete`)
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
            hapusDataTahunAjar()
        },

    });
};

</script>

<template>
   <Head title="Edit Tahun Ajaran" />

   <AdminLayout pageTitle="Tahun Ajaran">
    <template #pageContent>
        <ConfirmDialog />
        <Toast/>
        <div class="flex justify-between items-center">
            <!--left  -->
            <h1 class="text-2xl">Edit Data</h1>
            <!-- right -->
            <Button size="small" icon-pos="right" severity="danger" :loading="isReset" icon="pi pi-refresh" @click="form.reset()" label="Reset form" />
        </div>
        <form submit.prevent class="flex flex-col gap-8 py-8">
            <div class="flex items-center gap-4">
                <label for="semester" class="font-semibold w-36">Semester</label>
                <Select id="semester" v-model="form.semester" :options="semester" optionValue="nama" optionLabel="nama" fluid class="flex-auto max-w-[12rem]" />
            </div>
            <div class="flex items-center gap-4">
                <label for="mulai" class="font-semibold w-36">Tahun mulai</label>
                <InputText v-model="form.mulai" id="mulai" class="flex-auto max-w-[12rem]" autocomplete="off"/>
            </div>
            <div class="flex items-center gap-4">
                <label for="selesai" class="font-semibold w-36">Tahun selesai</label>
                <InputText v-model="form.selesai" id="selesai" class="flex-auto max-w-[12rem]" autocomplete="off"/>
            </div>
            <div class="flex flex-col gap-2">
                <Button severity="info" type="submit" label="Update Data" @click="updateDataTahunAjar" :disabled="form.processing"/>
                <Button label="Hapus Data" severity="danger" @click="confirmHapus"/>
            </div>

        </form>
    </template>

   </AdminLayout>
</template>

<style scoped>
</style>