<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Admin Login — Portal Masjid" />

    <div class="min-h-screen flex bg-[#FAFAFA] font-sans text-gray-900 selection:bg-emerald-500 selection:text-white">
        
        <div class="hidden lg:flex lg:w-1/2 relative items-center justify-center overflow-hidden bg-emerald-950">
            <div class="absolute inset-0">
                <img src="/assets/img/background.png" alt="Masjid Background" class="w-full h-full object-cover opacity-40 mix-blend-overlay" />
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-950/90 via-emerald-900/80 to-emerald-800/90"></div>
            </div>

            <div class="relative z-10 w-full max-w-lg px-12 text-white">
                <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md flex items-center justify-center mb-8 shadow-lg">
                    <svg viewBox="0 0 36 36" fill="none" class="w-7 h-7">
                        <path d="M18 4C18 4 8 10 8 20V28H28V20C28 10 18 4 18 4Z" fill="white" fill-opacity="0.95"/>
                        <rect x="14" y="22" width="8" height="6" fill="white" fill-opacity="0.8"/>
                        <circle cx="18" cy="4" r="2" fill="white" />
                    </svg>
                </div>
                
                <h1 class="text-4xl font-bold tracking-tight mb-4 leading-tight">
                    Sistem Manajemen <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-teal-200">Portal Masjid.</span>
                </h1>
                
                <p class="text-emerald-50/70 text-lg font-light leading-relaxed mb-10">
                    Kelola data jama'ah, pendaftaran qurban, dan pelaporan ZISWAF dalam satu dasbor yang terintegrasi dan aman.
                </p>

                <div class="flex items-center gap-4 bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-800 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">Area Terbatas</p>
                        <p class="text-xs text-emerald-100/60">Hanya untuk pengurus dewan kemakmuran masjid.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-24 relative">
            
            <a href="/" class="absolute top-8 right-8 sm:top-12 sm:right-12 text-sm font-medium text-gray-500 hover:text-emerald-600 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Beranda
            </a>

            <div class="w-full max-w-md">
                
                <div class="lg:hidden w-12 h-12 rounded-xl bg-emerald-600 flex items-center justify-center mb-6 shadow-sm">
                    <svg viewBox="0 0 36 36" fill="none" class="w-6 h-6"><circle cx="18" cy="4" r="2" fill="white" /><path d="M18 4C18 4 8 10 8 20V28H28V20C28 10 18 4 18 4Z" fill="white"/><rect x="14" y="22" width="8" height="6" fill="white"/></svg>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 tracking-tight mb-2">Selamat Datang</h2>
                <p class="text-gray-500 text-sm mb-10">Silakan masuk dengan akun pengurus Anda.</p>

                <div v-if="status" class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-sm font-medium text-emerald-700 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="admin@masjid.id"
                            class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all shadow-[0_2px_10px_rgb(0,0,0,0.02)]"
                            :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500/10': form.errors.email }"
                        />
                        <p v-if="form.errors.email" class="mt-1.5 text-sm text-red-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm font-medium text-emerald-600 hover:text-emerald-500 transition-colors"
                            >
                                Lupa password?
                            </Link>
                        </div>
                        <input
                            id="password"
                            type="password"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all shadow-[0_2px_10px_rgb(0,0,0,0.02)]"
                            :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500/10': form.errors.password }"
                        />
                        <p v-if="form.errors.password" class="mt-1.5 text-sm text-red-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <div class="flex items-center pt-2">
                        <input
                            id="remember"
                            type="checkbox"
                            v-model="form.remember"
                            class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500/30 transition-colors cursor-pointer"
                        />
                        <label for="remember" class="ms-2 block text-sm text-gray-600 cursor-pointer select-none">
                            Ingat sesi saya
                        </label>
                    </div>

                    <div class="pt-4">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full flex justify-center items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl py-3.5 transition-all duration-300 shadow-[0_4px_15px_rgba(16,185,129,0.2)] hover:shadow-[0_6px_20px_rgba(16,185,129,0.3)] hover:-translate-y-0.5 disabled:opacity-70 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                        >
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            {{ form.processing ? 'Memproses...' : 'Masuk ke Dasbor' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>