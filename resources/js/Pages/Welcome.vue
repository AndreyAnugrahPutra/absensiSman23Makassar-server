<script setup>
import { onMounted, ref} from 'vue';
import LogoSekolah from '@/Components/LogoSekolah.vue'
import Button from 'primevue/button';
</script>

<template>
   <Head title="SMAN 23 MAKASSAR"/>
   <div>
      <nav class="min-h-[5rem] bg-green-900 w-full flex justify-between items-center p-4">
         <div class="flex items-center gap-x-4">
            <LogoSekolah class="size-[4rem]"/>
            <span class="text-white">SMAN 23 MAKASSAR</span>
         </div>
         <div class="flex items-center gap-x-8">
            <a href='#visiMisi' class="text-white text-decoration-none">VISI MISI</a>
            <a href='#lokasi' class="text-white text-decoration-none">LOKASI</a>
            <Button as="a" href="/login" label="LOGIN" unstyled class="bg-yellow-400 py-2 px-4 rounded-md" />
         </div>
      </nav>
      <section id="hero" class="bg-slate-100 w-100 h-100 flex flex-col items-center">
         <div class="top p-4 gap-y-8 flex flex-col justify-center items-center">
            <h1 class="text-3xl font-semibold">SMAN 23 MAKASSAR</h1>
            <LogoSekolah class="size-[10rem]"/>
            <h2 class="text-xl font-medium">"TIADA HARI TANPA PERUBAHAN"</h2>
         </div>
         <div class="mid">
            <img src="../../img/profil-img.jpg">
         </div>
      </section>
      <section id="visiMisi" class="bg-slate-50 w-100 h-100 flex flex-col gap-y-8 p-8 px-14">
         <span class="text-3xl text-center">VISI & MISI SMAN 23 MAKASSAR</span>
         <div class="visi flex flex-col gap-y-4">
            <div class="flex items-center gap-x-4">
               <i class="pi pi-bolt" style="font-size: 1.5rem;"></i>
               <span class="text-2xl"> VISI</span>
            </div>
            <p>Mewujudkan Sekolah yang Berakhlak Mulia, Smart, Berwawasan Global, dan Berkearifan Lokal</p>
         </div>
         <div class="misi flex flex-col gap-y-4">
            <div class="flex items-center gap-x-4">
               <i class="pi pi-bullseye" style="font-size: 1.5rem;"></i>
               <span class="text-2xl">MISI</span>
            </div>
            <p>1. Memberdayakan tenaga pendidik dan kependidikan yang memenuhi standar Nasional Pendidikan yang ditetapkan.<br/>
               2. Menanamkan kedisiplinan melalui budaya bersih, budaya tertib, dan budaya kerja.<br/>
               3. Menumbuhkan penghayatan terhadap budaya dan seni daerah sehingga menjadi salah satu sumber kearifan berperilaku dan bermasyarakat<br/>
               4. Menumbuhkan inovasi dalam kehidupan sehari hari yang dapat menunjang pengembangan profesionalisme di era globalisme<br/>
               5. Memberdayakan seluruh komponen sekolah dan mengoptimalkan sumber daya sekolah dalam mengembangkan potensi peserta didik secara optimal.<br/>
            </p>
         </div>
      </section>
      <section id="lokasi" class="flex flex-col gap-y-8 items-center pt-8">
         <span class="text-3xl">LOKASI SMAN 23 MAKASSAR</span>
         <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3973.7675980599943!2d119.47195247408186!3d-5.141076451987815!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbee3856d0f1521%3A0x1865e321e1f6e23c!2sSMA%20NEGERI%2023%20MAKASSAR!5e0!3m2!1sid!2sid!4v1725641250857!5m2!1sid!2sid" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </section>
      <footer class="min-h-[8rem] bg-slate-900 text-white flex flex-col justify-center items-center">
         <h1>©2024 absensi-sman23makassar.online</h1>
      </footer>
   </div>
</template>

<style scoped>
</style>