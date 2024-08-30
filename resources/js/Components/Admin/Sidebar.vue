<script setup>
import AppLogo from '@/Components/AppLogo.vue'
import LogoSekolah from '@/Components/LogoSekolah.vue'
import NavLink from '@/Components/NavLink.vue'
</script>

<template>
    <div class="min-h-screen min-w-[4rem] md:w-[12rem] max-w-[12rem] fixed">
        <aside class="bg-white border-r border-gray-100 shadow p-2 md:w-full rounded-lg min-h-[98vh] flex flex-col justify-between gap-y-4">
            <!--sidebar  header-->
            <!-- <AppLogo class="block h-8 w-auto fill-current text-gray-800 self-center"/> -->
             <!-- <LogoSekolah class="max-w-[40px] self-center" /> -->
            <!--sidebar  header end-->

            <!--sidebar link-->
            <div class="flex flex-col gap-2 items-center md:items-start mt-4">
                <!--Links-->
                <NavLink :href="route('admin.dashboard')" :active="route().current('admin.dashboard')">
                    <i class="pi pi-home"></i> <span class="md:block hidden">Dashboard</span>
                </NavLink>

                <!-- <NavLink :href="route('admin.absensi.index')" :active="
                route().current('admin.absensi.index') ||
                route().current('admin.absensi.view') 
                ">
                    <i class="pi pi-file-edit"></i>  <span class="md:block hidden">Absensi</span>
                </NavLink> -->

                <NavLink :href="route('admin.lokasi.index')" :active="route().current('admin.lokasi.index')">
                    <i class="pi pi-map-marker"></i>  <span class="md:block hidden">Lokasi</span>
                </NavLink>

                <NavLink :href="route('admin.kelas.index')" 
                :active="
                route().current('admin.kelas.index') || 
                route().current('admin.kelas.view')">
                    <i class="pi pi-home"></i> <span class="md:block hidden">Kelas</span>
                </NavLink>

                <NavLink :href="route('admin.mapel.index')" 
                :active="
                route().current('admin.mapel.index') || 
                route().current('admin.mapel.view')">
                    <i class="pi pi-book"></i> <span class="md:block hidden">Mapel</span>
                </NavLink>

                <NavLink :href="route('admin.jadwal.index')" :active="
                route().current('admin.jadwal.index') ||
                route().current('admin.jadwal.view')  ||
                route().current('admin.absensi.daftar') ||
                route().current('admin.absensi.view') 
                ">
                    <i class="pi pi-calendar"></i> <span class="md:block hidden">Jadwal Pelajaran</span>
                </NavLink>

                <NavLink :href="route('admin.guru.index')" 
                :active="
                route().current('admin.guru.index') || 
                route().current('admin.guru.view')">
                    <i class="pi pi-user"></i><span class="md:block hidden">Guru</span>
                </NavLink>

                <NavLink :href="route('admin.siswa.index')" :active="
                route().current('admin.siswa.index') ||
                route().current('admin.siswa.view') ">
                    <i class="pi pi-users"></i> <span class="md:block hidden">Siswa</span>
                
                </NavLink>

                <NavLink :href="route('admin.tahun_ajar.index')" :active="
                route().current('admin.tahun_ajar.index') ||
                route().current('admin.tahun_ajar.view') ">
                    <i class="pi pi-calendar-clock"></i> <span class="md:block hidden">Tahun Ajaran</span>
                </NavLink>

                <NavLink :href="route('admin.orangtua.index')" :active="route().current('admin.orangtua.index') || route().current('admin.orangtua.view')">
                    <i class="pi pi-users"></i> <span class="md:block hidden">Orang Tua</span>
                </NavLink>
                
            </div>
            <!--sidebar link end-->

            <!--sidebar footer -->
            <div class="flex flex-col gap-2">
                <a :href="route('admin.profile')" class="inline-flex gap-2 justify-center md:justify-start items-center p-2 border-2 border-sky-500 bg-sky-500 text-sm font-medium leading-5 text-white focus:outline-none focus:border-sky-700 transition duration-150 ease-in-out rounded-md">
                    <i class="pi pi-cog"></i> <span class="md:block hidden">Profile</span>
                </a>
                <a :href="route('user.logout')" class="inline-flex gap-2  justify-center md:justify-start items-center p-2 border-2 border-red-500 bg-red-500 text-sm font-medium leading-5 text-white focus:outline-none focus:border-red-700 transition duration-150 ease-in-out rounded-md">
                    <i class="pi pi-power-off"></i> <span class="md:block hidden">Logout</span>
                </a>
            </div>
            <!--sidebar footer end-->
        </aside>
    </div>
</template>

<style scoped>
</style>