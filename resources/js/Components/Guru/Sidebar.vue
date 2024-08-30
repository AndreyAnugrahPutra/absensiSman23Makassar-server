<script setup>
import AppLogo from '@/Components/LogoSekolah.vue'
import NavLink from '@/Components/NavLink.vue'
</script>

<template>
    <div class="sidebar_wrapper min-h-screen min-w-[4rem] w-[12rem] max-w-[12rem] fixed">
        <aside class="bg-white border-r border-gray-100 shadow p-2 w-full rounded-lg min-h-[98vh] flex flex-col justify-between">
            <!--sidebar  header-->
            <!-- <span class="text-xl uppercase text-black"></span> -->
            <!--sidebar  header end-->

            <!--sidebar link-->
            <div class="flex flex-col gap-4 mt-4">
                <!--Links-->
                <NavLink :href="route('guru.dashboard')" :active="route().current('guru.dashboard')">
                    <i class="pi pi-home"></i> <span class="md:block hidden">Dashboard</span>
                </NavLink>

                <!-- <NavLink :href="route('guru.absensi.index')" :active="route().current('guru.absensi.index')">
                    <i class="pi pi-file-edit"></i>  <span class="md:block hidden">Absensi</span>
                </NavLink> -->

                <NavLink :href="route('guru.kelas.index')" 
                :active="
                route().current('guru.kelas.index') || 
                route().current('guru.kelas.view')">
                    <i class="pi pi-home"></i> <span class="md:block hidden">Kelas</span>
                </NavLink>

                <NavLink :href="route('guru.jadwal.index')" :active="
                route().current('guru.jadwal.index') ||
                route().current('guru.jadwal.view')  ||
                route().current('guru.absensi.daftar') ||
                route().current('guru.absensi.view') 
                ">
                    <i class="pi pi-calendar"></i> <span class="md:block hidden">Jadwal Pelajaran</span>
                </NavLink>

                <NavLink :href="route('guru.mapel.index')" 
                :active="
                route().current('guru.mapel.index') || 
                route().current('guru.mapel.view')">
                    <i class="pi pi-book"></i> <span class="md:block hidden">Mapel</span>
                </NavLink>
                <!-- <NavLink :href="route('guru.siswa.index')" :active="
                route().current('guru.siswa.index') ||
                route().current('guru.siswa.view') ">
                    <i class="pi pi-users"></i> <span class="md:block hidden">Siswa</span>
                
                </NavLink> -->

                <!-- <NavLink :href="route('guru.orangtua.index')" :active="route().current('guru.orangtua.index') || route().current('guru.orangtua.view')">
                    <i class="pi pi-users"></i> <span class="md:block hidden">Orang Tua</span>
                </NavLink> -->
                
            </div>
            <!--sidebar link end-->

            <!--sidebar footer -->
            <div class="flex flex-col gap-2">
                <a :href="route('guru.profile')" class="inline-flex gap-2 items-center p-2 border-2 border-sky-500 bg-sky-500 text-sm font-medium leading-5 text-white focus:outline-none focus:border-sky-700 transition duration-150 ease-in-out rounded-md">
                    <i class="pi pi-cog"></i> Profile
                </a>
                <a :href="route('user.logout')" class="inline-flex gap-2 items-center p-2 border-2 border-red-500 bg-red-500 text-sm font-medium leading-5 text-white focus:outline-none focus:border-red-700 transition duration-150 ease-in-out rounded-md">
                    <i class="pi pi-power-off"></i> Logout
                </a>
            </div>
            <!--sidebar footer end-->
        </aside>
    </div>
</template>

<style scoped>
</style>