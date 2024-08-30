<script setup>
import { ref, onMounted, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

import AdminLayout from '@/Layouts/Admin/AdminLayout.vue'

import "leaflet/dist/leaflet.css"
import * as L from 'leaflet'
// import { Draggable } from 'leaflet'

import Button from 'primevue/button'
import InputNumber from 'primevue/inputnumber'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'

import Toast from 'primevue/toast'
import { useToast } from "primevue/usetoast"

const pageProps = defineProps({dataLokasi : Object, flash : Object})

onMounted(()=> {

    checkNotif()

    initialMap.value = L.map('map', {zoomControl: true,zoom:1,zoomAnimation:false,fadeAnimation:true,markerZoomAnimation:true}).on('click', addMarker).setView([-5.1410421677943, 119.47451892178], 19);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19, 
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(initialMap.value);
    
    marker = L.marker([-5.1410421677943, 119.47451892178], {draggable : true}).addTo(initialMap.value).on('dragend', updateMarker).bindPopup(form.nama_lokasi).openPopup()
    radius = L.circle([-5.1410421677943,119.47451892178], {radius : form.radius, draggable : true}).addTo(initialMap.value)

}); 

const dataLokasiTemp = pageProps.dataLokasi

const initialMap = ref(null);

const lat = ref(dataLokasiTemp.latitude)
const long = ref(dataLokasiTemp.longitude)
const accuracy = ref(0)
const radiusWilayah = ref(dataLokasiTemp.radius)


let marker,radius,zoomed

const toast = useToast()

const notif_show = ref(false)
const notif_status = ref(null)
const notif_message = ref(null)

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

const form = useForm({
    nama_lokasi : null || dataLokasiTemp.nama_lokasi,
    alamat : null || dataLokasiTemp.alamat,
    latitude : lat.value || dataLokasiTemp.latitude,
    longitude :long.value || dataLokasiTemp.longitude,
    radius : radiusWilayah.value,
})

const getLocation = () =>
{
    if(navigator.geolocation)
    {
        navigator.geolocation.getCurrentPosition((position) =>
        {
            lat.value = position.coords.latitude
            long.value = position.coords.longitude
            accuracy.value = position.coords.accuracy

            if(marker)
            {
                initialMap.value.removeLayer(marker)
                initialMap.value.removeLayer(radius)
            }

            initialMap.value.setView([lat.value, long.value], 19)

            marker = L.marker([lat.value, long.value], {draggable : true}).addTo(initialMap.value).on('dragend', updateMarker).bindPopup(form.nama_lokasi).openPopup()
            radius = L.circle([lat.value,long.value], {radius : radiusWilayah.value, draggable : true}).addTo(initialMap.value)

            if(!zoomed)
            {
                zoomed = initialMap.value.fitBounds(radius.getBounds())
            }

        })
        
    }
}

watch(radiusWilayah, () =>
{
    updateRadius()
})

const addMarker = (event) =>
{
    console.log(event)
    lat.value = event.latlng.lat
    long.value = event.latlng.lng
    
    if(marker)
    {
        initialMap.value.removeLayer(marker)
    }

    initialMap.value.setView([lat.value, long.value], 19)
    marker = L.marker([lat.value, long.value], {draggable : true}).addTo(initialMap.value).on('dragend', updateMarker).bindPopup(form.nama_lokasi).openPopup()


    updateRadius()

    if(!zoomed)
    {
        zoomed = initialMap.value.fitBounds(radius.getBounds())
    }
}

const updateMarker =  (event) =>
{
    console.log(event)
    lat.value = event.target._latlng.lat
    long.value = event.target._latlng.lng

    if(marker)
    {
        initialMap.value.removeLayer(marker)
    }

    initialMap.value.setView([lat.value, long.value], 19)
    marker = L.marker([lat.value, long.value], {draggable : true}).addTo(initialMap.value).on('dragend', updateMarker).bindPopup(form.nama_lokasi).openPopup()

    updateRadius()


    if(!zoomed)
    {
        zoomed = initialMap.value.fitBounds(radius.getBounds())
    }
}

const updateRadius = () =>
{
    if(radius)
    {
        initialMap.value.removeLayer(radius)
    }

    form.radius = radiusWilayah.value
    radius = L.circle([lat.value,long.value], {radius : radiusWilayah.value, draggable : true}).addTo(initialMap.value)
}

const uploadLokasi = () =>
{
    form.post(route('admin.lokasi.create', {
        onSuccess : setTimeout(() => checkNotif(), 800)
    }))
}
</script>

<template>
<Head title="Lokasi"/>
    <AdminLayout pageTitle="Lokasi Absensi">
        <template #pageContent>
            <Toast/>
            <div class="flex flex-col gap-4">
                <h3>Sumber Map : <a href="https://www.openstreetmap.org/">Openstreetmap</a></h3>
                <div id="map" style="height:50vh;"></div>
                <form class="flex flex-col gap-4" @submit.prevent="uploadLokasi">
                    <div class="flex items-center gap-4">
                        <label for="namaLokasi" class="w-[24rem]">Nama Lokasi</label>
                        <InputText v-model="form.nama_lokasi" id="namaLokasi" fluid class="flex-auto"/>
                    </div>  
                    <div class="flex items-center gap-4">
                        <label for="alamat" class="w-[24rem]">Alamat</label>
                        <Textarea rows="3" v-model="form.alamat" id="alamat" resize="false" autoResize fluid class="flex-auto"/>
                    </div>  
                    <div class="flex items-center gap-4">
                        <label for="meter" class="w-[24rem]">Radius Wilayah (Meter)</label>
                        <InputNumber :min="0" showButtons v-model="radiusWilayah" id="meter" inputId="meter" placeholder="meter" fluid class="flex-auto" />
                    </div>
                    <div class="flex items-center gap-4">
                        <label for="meter" class="w-[24rem]">Latitude</label>
                        <InputText v-model="lat" id="meter" fluid class="flex-auto"/>
                    </div>
                    <div class="flex items-center gap-4">
                        <label for="meter" class="w-[24rem]">Longitude</label>
                        <InputText v-model="long" id="meter" fluid class="flex-auto"/>
                    </div>
                    <Button severity="info" label="Kalibrasi" @click="getLocation"/>
                    <Button @click="uploadLokasi" severity="success" label="Update Lokasi" :disabled="form.processing"/>
                </form>
                <!-- <span>Geolocation : {{ lat }}, {{ long }}</span>
                <span>Accuracy : {{ accuracy }}</span> -->
                <!-- <span>Google Maps : -5.109283, 119.535190 </span> -->
            </div>
        </template>
    </AdminLayout>
</template>

<style scoped>
</style>