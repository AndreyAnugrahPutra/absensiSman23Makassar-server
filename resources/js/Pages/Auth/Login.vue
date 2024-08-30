<script setup>
import { ref, onMounted } from 'vue'
// import modul
import {Head, useForm, usePage} from '@inertiajs/vue3'
// import Layout
import GuestLayout from '@/Layouts/GuestLayout.vue'
// import component
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Select from 'primevue/select'
import Checkbox from 'primevue/checkbox'
import Button from 'primevue/button'

import InputError from '../../Components/Form/InputError.vue'


onMounted(()=>
{
    levelUser.value = pageProps.levelUser
}) 

const pageProps  = defineProps({levelUser : Array, userAuth : Boolean, flash : Object});

const levelUser = ref([])

const form = useForm({
    email: null,
    password: null,
    level: null,
    remember : false,
});

const submitLogin = () => {
    form.post(route('user.login'))
};
</script>

<template>
    <Head title="LOGIN" />
    <GuestLayout :flash="flash">
        <template #form>
            <form @submit.prevent class="p-8 flex flex-col gap-4 border border-gray-400 min-w-[24rem] rounded-xl">
                <!-- Email  -->
                <div class="flex flex-col gap-2">
                    <label for="NIP">EMAIL</label>
                    <InputText required id="NIP" v-model="form.email"/>
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <!--Password  -->
                <div class="flex flex-col gap-2">
                    <label for="password">PASSWORD</label>
                    <Password required id="password" v-model="form.password" toggle-mask/>
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>
                <!--Level Akses -->
                <div class="flex flex-col gap-2">
                    <label for="level">LEVEL AKSES</label>
                    <Select id="level" v-model="form.level" :options="levelUser" option-value="id_level" option-label="nama_level" placeholder="Pilih Level Akses" :disabled="form.processing" />
                    <InputError class="mt-2" :message="form.errors.level" />
                </div>
                <!--Remember Me  -->
                <div class="flex items-center gap-2">
                    <label for="rememberMe">Remember Me</label>
                    <Checkbox v-model="form.remember" id="rememberMe" :binary="true"/>
                </div>
                <!--Submit Button -->
                <Button label="LOGIN" @click="submitLogin" :disabled="form.processing"/>
            </form>
        </template>
    </GuestLayout>
</template>

<style scoped>
</style>