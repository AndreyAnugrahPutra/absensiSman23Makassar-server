<script setup>
import { onMounted, ref } from 'vue';

import LogoSekolah from '@/Components/LogoSekolah.vue'
import Sidebar from '@/Components/Admin/Sidebar.vue'

defineProps({ pageTitle : String})

let timestamp = ref('')

let hariTanggal = () =>
{
    const today = new Date();
    const date = 'Hari/Tanggal : '+today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear()
    const time = 'Jam : '+today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    const dateTime = date +' '+ time;
    timestamp.value = dateTime;
}

onMounted(()=>
{
    setInterval(hariTanggal, 1000);
})

</script>

<template>
    <!-- <div> -->
        <div class=" bg-gray-200 flex p-2 gap-2 min-h-screen overflow-hidden">
            <!--sidebar-->
            <Sidebar />
            <!--sidebar end-->

            <!--page content-->
            <div class="flex flex-col gap-2 w-[1150px] ml-[4.5rem] md:ml-[12.5rem]">
                <!--content header  -->
                <div class="min-h-[4rem] h-[4rem] bg-white p-4 rounded-lg w-full flex justify-between items-center text-gray-700">
                    <div class="flex items-center gap-x-4">
                        <LogoSekolah class="max-w-[40px]" />
                        <h1 class="text-xl">{{ pageTitle }}</h1>
                    </div>
                    <span>{{ timestamp }}</span>
                </div>
                <!--content header end-->

                <!--content body-->
                <div class="w-full h-full bg-white rounded-lg p-4">
                    <slot name="pageContent"/>
                </div>
                <!--content body end-->
            </div>
            <!--page content-->
        </div>
    <!-- </div> -->

</template>

<style scoped>
</style>