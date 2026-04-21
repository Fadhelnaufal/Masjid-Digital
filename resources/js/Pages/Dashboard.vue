<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head } from "@inertiajs/vue3";
import { computed, ref, onMounted, onUnmounted } from "vue";
import { format } from "date-fns";
import {
    Users,
    HandHeart,
    Wallet,
    TrendingUp,
    ArrowUpRight,
    Clock,
    CalendarCheck,
    ChevronRight,
    MapPin,
    MoonStar,
} from "lucide-vue-next";

// --- IMPORT UNTUK GRAFIK ---
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";
import { Bar } from "vue-chartjs";

// Registrasi komponen Chart.js
ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
);

// PrimeVue
import Button from "primevue/button";

// --- MAPPING IKON ---
const iconMap = {
    Users,
    HandHeart,
    Wallet,
    TrendingUp,
    Clock,
    MapPin,
    MoonStar,
};

// 1. TERIMA DATA DARI LARAVEL CONTROLLER
const props = defineProps({
    prayerTimes: Object,
    stats: Array,
    recentActivities: Array,
    chartData: Object, // Data Grafik dari Backend
});

// --- KONFIGURASI GRAFIK ---
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: "#0f172a",
            padding: 12,
            titleFont: { size: 14, weight: "bold" },
            callbacks: {
                label: (context) => `Rp ${context.raw.toLocaleString("id-ID")}`,
            },
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: { display: true, color: "#f1f5f9" },
            ticks: {
                callback: (value) => `Rp ${value / 1000}rb`,
                font: { size: 10 },
            },
        },
        x: {
            grid: { display: false },
            ticks: { font: { size: 11, weight: "bold" } },
        },
    },
};


// --- LOGIKA WAKTU & JADWAL ---
const nowTime = ref(new Date());

const Time = computed(() => {
    return format(nowTime.value, "HH:mm");
});

let timer;
onMounted(() => {
    timer = setInterval(() => {
        nowTime.value = new Date();
    }, 100);
});

onUnmounted(() => {
    clearInterval(timer);
});

const prayerList = computed(() => {
    if (!props.prayerTimes) return [];
    const t = props.prayerTimes;
    const list = [
        { name: "Subuh", time: t.Fajr },
        { name: "Dzuhur", time: t.Dhuhr },
        { name: "Ashar", time: t.Asr },
        { name: "Maghrib", time: t.Maghrib },
        { name: "Isya", time: t.Isha },
    ];
    const currentTimeStr = format(nowTime.value, "HH:mm");
    let foundNext = list.find((p) => p.time > currentTimeStr) || list[0];

    return list.map((p) => ({
        ...p,
        status:
            p.name === foundNext.name
                ? "next"
                : p.time < currentTimeStr
                  ? "passed"
                  : "upcoming",
    }));
});

const nextPrayer = computed(() => {
    return (
        prayerList.value.find((p) => p.status === "next") || {
            name: "-",
            time: "-",
        }
    );
});
</script>

<template>
    <Head title="Dashboard Admin" />

    <AdminLayout>
        <div
            class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10 px-1"
        >
            <div class="space-y-1">
                <h2
                    class="text-3xl font-bold text-slate-900 tracking-tight  uppercase"
                >
                    Assalamu'alaikum, Fadhel
                </h2>
                <p class="text-slate-500 text-sm font-medium ">
                    Panel kendali operasional Masjid Ar-Roudhoh.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <Button
                    variant="outlined"
                    severity="secondary"
                    class="!rounded-xl !border-slate-200 !px-5 !h-11 !bg-white"
                >
                    <CalendarCheck class="w-4 h-4 mr-2 text-slate-400" />
                    <span
                        class="font-semibold text-slate-600 uppercase text-[11px] tracking-wider"
                        >Laporan Kas</span
                    >
                </Button>
                <Button
                    severity="success"
                    class="!bg-emerald-600 !hover:bg-emerald-500 !rounded-xl !shadow-lg !shadow-emerald-100 !h-11 !px-6 !border-none transition-all active:scale-95"
                >
                    <span
                        class="font-bold text-white uppercase text-[11px] tracking-wider"
                        >Update Data</span
                    >
                </Button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div
                v-for="stat in props.stats"
                :key="stat.label"
                class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm transition-all hover:border-emerald-200 hover:shadow-md group relative overflow-hidden"
            >
                <div
                    class="flex items-center justify-between mb-5 relative z-10"
                >
                    <div
                        :class="[
                            'w-12 h-12 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110 duration-300',
                            stat.bg,
                        ]"
                    >
                        <component
                            v-if="stat.icon && iconMap[stat.icon]"
                            :is="iconMap[stat.icon]"
                            :class="['w-6 h-6', stat.color]"
                        />
                        <Wallet v-else :class="['w-6 h-6', stat.color]" />
                    </div>
                    <div
                        class="w-8 h-8 rounded-full flex items-center justify-center bg-slate-50 group-hover:bg-emerald-50 transition-colors"
                    >
                        <ArrowUpRight
                            class="w-4 h-4 text-slate-300 group-hover:text-emerald-500"
                        />
                    </div>
                </div>
                <div class="relative z-10">
                    <p
                        class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.15em] mb-1"
                    >
                        {{ stat.label }}
                    </p>
                    <h3
                        class="text-2xl font-black text-slate-900 tracking-tight "
                    >
                        {{ stat.value }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 space-y-8">
                <div
                    class="bg-white rounded-[32px] p-8 border border-slate-100 shadow-sm"
                >
                    <div class="flex items-center gap-3 mb-8">
                        <div
                            class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600"
                        >
                            <TrendingUp class="w-5 h-5" />
                        </div>
                        <h3
                            class="text-lg font-bold text-slate-900 tracking-tight  uppercase"
                        >
                            Arus Kas Masjid (Infaq)
                        </h3>
                    </div>

                    <div class="w-full h-[320px] px-2">
                        <Bar
                            v-if="props.chartData"
                            :data="props.chartData"
                            :options="chartOptions"
                        />
                        <div
                            v-else
                            class="flex flex-col items-center justify-center h-full text-slate-400 "
                        >
                            <TrendingUp class="w-10 h-10 opacity-20 mb-3" />
                            <p
                                class="text-[10px] font-bold uppercase tracking-[0.2em]"
                            >
                                Memuat Grafik...
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-[32px] p-8 border border-slate-100 shadow-sm"
                >
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600"
                            >
                                <Clock class="w-5 h-5" />
                            </div>
                            <h3
                                class="text-lg font-bold text-slate-900 tracking-tight  uppercase"
                            >
                                Aktivitas Terkini
                            </h3>
                        </div>
                        <Button
                            variant="text"
                            severity="success"
                            class="!text-[10px] !font-bold !text-emerald-600 !hover:bg-emerald-50 !px-4"
                        >
                            SEMUA LOG <ChevronRight class="w-3 h-3 ml-1" />
                        </Button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div
                            v-for="item in props.recentActivities"
                            :key="item.id"
                            class="p-5 rounded-[24px] bg-slate-50 border border-slate-100 hover:bg-white hover:shadow-md transition-all group"
                        >
                            <div class="flex justify-between items-start mb-4">
                                <span
                                    class="text-[9px] font-bold text-slate-400 uppercase tracking-wider"
                                    >{{ item.time }}</span
                                >
                                <span
                                    class="text-[10px] font-bold text-emerald-600 bg-white px-2 py-0.5 rounded-lg shadow-sm"
                                    >{{ item.amount }}</span
                                >
                            </div>
                            <p
                                class="text-sm font-bold text-slate-900 group-hover:text-emerald-600 transition-colors"
                            >
                                {{ item.user }}
                            </p>
                            <p
                                class="text-[11px] text-slate-500 font-medium mt-1  leading-tight"
                            >
                                {{ item.action }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-8">
                <div
                    class="bg-slate-900 rounded-[32px] p-8 text-white shadow-2xl shadow-emerald-900/20 relative overflow-hidden group"
                >
                    <div
                        v-if="!props.prayerTimes"
                        class="flex flex-col items-center justify-center py-20"
                    >
                        <MoonStar class="w-10 h-10 text-slate-700 mb-4" />
                        <p
                            class="text-xs font-bold uppercase tracking-widest text-slate-500"
                        >
                            Jadwal Tidak Tersedia
                        </p>
                    </div>

                    <div v-else class="relative z-10">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-2">
                                <MapPin class="w-4 h-4 text-emerald-400" />
                                <span
                                    class="text-[10px] font-bold uppercase tracking-widest text-slate-400 "
                                    >Ponorogo, Indonesia</span
                                >
                            </div>
                            <div
                                class="text-[10px] font-bold uppercase tracking-widest text-emerald-400 animate-pulse"
                            >
                                Next: {{ nextPrayer.name }}
                            </div>
                        </div>

                        <div class="text-center mb-10">
                            <h4
                                class="text-5xl font-black  tracking-tighter mb-2"
                            >
                                {{ Time }}
                            </h4>
                            <p
                                class="text-emerald-400 font-bold uppercase tracking-[0.3em] text-[11px]"
                            >
                                Menuju {{ nextPrayer.name }}
                            </p>
                        </div>

                        <div class="space-y-3">
                            <div
                                v-for="prayer in prayerList"
                                :key="prayer.name"
                                :class="[
                                    'flex items-center justify-between p-4 rounded-2xl border transition-all duration-500',
                                    prayer.status === 'next'
                                        ? 'bg-emerald-600 border-emerald-500 scale-105 shadow-lg'
                                        : 'bg-white/5 border-white/10 opacity-60',
                                ]"
                            >
                                <span
                                    class="font-bold text-sm uppercase  tracking-wide"
                                    >{{ prayer.name }}</span
                                >
                                <span class="font-black text-sm">{{
                                    prayer.time
                                }}</span>
                            </div>
                        </div>
                    </div>
                    <MoonStar
                        class="absolute -right-10 -top-10 w-40 h-40 text-emerald-500/10 group-hover:rotate-12 transition-transform duration-1000"
                    />
                </div>

                <div
                    class="bg-white rounded-[32px] p-8 border border-slate-100 shadow-sm"
                >
                    <h4
                        class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-6 px-1"
                    >
                        Agenda Mendatang
                    </h4>
                    <div class="space-y-4">
                        <div
                            class="p-5 bg-emerald-600 rounded-[24px] text-white shadow-lg shadow-emerald-100 relative overflow-hidden group cursor-pointer transition-all hover:scale-[1.02]"
                        >
                            <div class="relative z-10">
                                <p
                                    class="text-[9px] font-bold opacity-70 uppercase tracking-tighter mb-1"
                                >
                                    Hari Ini • 16:00
                                </p>
                                <p
                                    class="text-sm font-bold leading-tight uppercase "
                                >
                                    Persiapan Jumat Berkah
                                </p>
                            </div>
                            <HandHeart
                                class="absolute -right-4 -bottom-4 w-20 h-20 opacity-10 group-hover:scale-110 transition-transform duration-500"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
:deep(.p-button) {
    font-family: inherit;
}

main {
    animation: slideUp 0.4s ease-out;
}
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
