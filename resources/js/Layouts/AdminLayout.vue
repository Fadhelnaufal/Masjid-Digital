<script setup>
import { ref } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
// Import PrimeVue Components
import Button from "primevue/button";
import Popover from "primevue/popover";
import Drawer from "primevue/drawer";

// Import Ikon Lucide
import {
    LayoutDashboard,
    Users,
    CalendarDays,
    HandHeart,
    Bell,
    Settings,
    Menu as MenuIcon,
    UserCircle,
    Search,
    ChevronRight,
    LogOut,
    User,
    ShieldCheck,
    Utensils,
    Coffee,
    FileText,
    HeartHandshake,
    Beef,
    MapPin,
    Megaphone,
    Landmark,
    MailPlus,
} from "lucide-vue-next";

const page = usePage();
const isMobileMenuOpen = ref(false);
const op = ref();

const toggleProfile = (event) => {
    op.value.toggle(event);
};

const isActive = (url) => {
    if (url === "#") return false;
    return page.url === url || page.url.startsWith(url);
};

const handleLogout = () => {
    // Gunakan router.post karena Laravel secara default memproteksi logout dengan CSRF
    router.post(
        route("logout"),
        {},
        {
            onSuccess: () => {
                // Setelah logout berhasil, Anda bisa melakukan tindakan tambahan jika diperlukan
                console.log("Logout berhasil");
            },
            onError: (errors) => {
                // Tangani error jika logout gagal
                console.error("Logout gagal:", errors);
            },
        },
    );
};
const navigation = [
    {
        group: "Utama",

        items: [
            { title: "Dashboard", icon: LayoutDashboard, url: "/dashboard" },
        ],
    },
    {
        group: "SDM Masjid",

        items: [
            { title: "Data Imam", icon: ShieldCheck, url: "/admin/data-imam" },

            { title: "Takmir Masjid", icon: Users, url: "#" },
        ],
    },
    {
        group: "Jumat Berkah",

        items: [
            {
                title: "Jumat Berkah",
                icon: HandHeart,
                url: "/admin/jumat-berkah",
            },

            { title: "Infaq ", icon: MailPlus, url: "/admin/infaq" },
        ],
    },

    {
        group: "Kegiatan Ramadhan",

        items: [
            {
                title: "Data Imam Tarawih",
                icon: ShieldCheck,
                url: "/admin/ramadhan/data-imam",
            },

            { title: "Jadwal Tarawih", icon: CalendarDays, url: "#" },

            { title: "Jadwal Pentakjil", icon: Utensils, url: "#" },

            { title: "Jadwal Jaburan", icon: Coffee, url: "#" },
        ],
    },

    {
        group: "Pengelolaan Zakat",

        items: [
            { title: "Laporan Zakat", icon: FileText, url: "#" },

            { title: "Penerima Zakat", icon: HeartHandshake, url: "#" },
        ],
    },

    {
        group: "Idul Adha & Qurban",

        items: [
            { title: "Data Qurban", icon: Beef, url: "#" },

            { title: "Distribusi", icon: MapPin, url: "#" },
        ],
    },

    {
        group: "Lainnya",

        items: [
            { title: "Pengumuman", icon: Megaphone, url: "#" },

            { title: "Kegiatan", icon: CalendarDays, url: "#" },

            { title: "Takmir Masjid", icon: Users, url: "#" },

            { title: "Rekening Bank", icon: Landmark, url: "#" },
        ],
    },
];
</script>

<template>
    <div class="min-h-screen flex w-full bg-[#FBFBFB] font-sans text-slate-900">
        <aside
            class="hidden lg:flex flex-col w-[280px] border-r border-slate-200 bg-white fixed inset-y-0 z-20"
        >
            <div class="h-[72px] flex items-center px-6 mb-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-200"
                    >
                        <HandHeart class="w-5 h-5 text-white" />
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-sm tracking-tight"
                            >Masjid Besar Sambit</span
                        >
                        <span
                            class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest text-left"
                            >Admin Panel</span
                        >
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-4 custom-scrollbar">
                <div
                    v-for="section in navigation"
                    :key="section.group"
                    class="mb-8 text-left"
                >
                    <h3
                        class="mb-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em]"
                    >
                        {{ section.group }}
                    </h3>
                    <div class="space-y-1">
                        <Link
                            v-for="item in section.items"
                            :key="item.title"
                            :href="item.url"
                        >
                            <Button
                                :text="!isActive(item.url)"
                                :severity="
                                    isActive(item.url) ? 'success' : 'secondary'
                                "
                                :class="[
                                    'w-full !justify-between !gap-3 !rounded-xl !h-11 !px-4 !border-none',
                                    isActive(item.url)
                                        ? '!bg-emerald-50 !text-emerald-700 hover:!bg-emerald-100'
                                        : '!text-slate-600 hover:!bg-slate-50 hover:!text-emerald-600',
                                ]"
                            >
                                <div class="flex items-center gap-3">
                                    <component
                                        :is="item.icon"
                                        :class="[
                                            'w-4 h-4',
                                            isActive(item.url)
                                                ? 'text-emerald-600'
                                                : 'text-slate-400',
                                        ]"
                                    />
                                    <span
                                        class="font-medium text-[13px] text-left"
                                        >{{ item.title }}</span
                                    >
                                </div>
                                <ChevronRight
                                    v-if="isActive(item.url)"
                                    class="w-3 h-3 text-emerald-400"
                                />
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex flex-col w-full lg:pl-[280px]">
            <header
                class="h-[72px] flex items-center justify-between px-6 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30"
            >
                <div class="flex items-center gap-4">
                    <MenuIcon
                        @click="isMobileMenuOpen = true"
                        class="w-6 h-6 text-slate-600 lg:hidden cursor-pointer hover:text-emerald-600 transition-colors"
                    />

                    <div
                        class="hidden md:flex items-center gap-3 text-slate-400 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-200 w-80 group focus-within:ring-2 focus-within:ring-emerald-500/20 focus-within:border-emerald-500 transition-all"
                    >
                        <Search class="w-4 h-4" />
                        <input
                            type="text"
                            placeholder="Cari data..."
                            class="bg-transparent border-none p-0 text-sm focus:ring-0 w-full placeholder:text-slate-400"
                        />
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <Button
                        icon="pi pi-bell"
                        text
                        severity="secondary"
                        class="!rounded-xl !text-slate-500 hover:!bg-slate-50"
                    />

                    <Button
                        text
                        severity="secondary"
                        @click="toggleProfile"
                        class="!gap-3 !px-2 !rounded-xl hover:!bg-slate-50"
                    >
                        <div
                            class="w-8 h-8 rounded-lg bg-slate-900 flex items-center justify-center text-white shadow-md shadow-slate-200"
                        >
                            <UserCircle class="w-5 h-5" />
                        </div>
                        <div
                            class="hidden sm:flex flex-col items-start text-left leading-none"
                        >
                            <span
                                class="text-[13px] font-bold text-slate-900 leading-none tracking-tight"
                                >Admin Fadhel</span
                            >
                            <span
                                class="text-[10px] text-slate-400 mt-1 font-medium italic"
                                >Administrator</span
                            >
                        </div>
                    </Button>

                    <Popover ref="op">
                        <div class="flex flex-col w-48 p-1">
                            <button
                                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-xl transition-all"
                            >
                                <User class="w-4 h-4 text-slate-400" /> Profil
                                Saya
                            </button>
                            <button
                                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-xl transition-all"
                            >
                                <Settings class="w-4 h-4 text-slate-400" />
                                Pengaturan
                            </button>
                            <hr class="my-2 border-slate-100" />
                            <button
                                @click="handleLogout"
                                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-rose-600 hover:bg-rose-50 rounded-xl transition-all"
                            >
                                <LogOut class="w-4 h-4" /> Keluar Sistem
                            </button>
                        </div>
                    </Popover>
                </div>
            </header>

            <Drawer
                v-model:visible="isMobileMenuOpen"
                class="!w-[280px] !p-0"
                :show-header="false"
            >
                <div class="flex flex-col h-full bg-white">
                    <div
                        class="h-[72px] flex items-center px-6 border-b border-slate-50 mb-4"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center shadow-lg"
                            >
                                <HandHeart class="w-4 h-4 text-white" />
                            </div>
                            <span class="font-bold text-gray-900 text-sm"
                                >Masjid Ar-Roudhoh</span
                            >
                        </div>
                    </div>
                    <div
                        class="flex-1 overflow-y-auto px-4 custom-scrollbar py-2"
                    >
                        <div
                            v-for="section in navigation"
                            :key="section.group"
                            class="mb-8 text-left"
                        >
                            <h3
                                class="mb-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest"
                            >
                                {{ section.group }}
                            </h3>
                            <div class="space-y-1">
                                <Link
                                    v-for="item in section.items"
                                    :key="item.title"
                                    :href="item.url"
                                    @click="isMobileMenuOpen = false"
                                >
                                    <Button
                                        :text="!isActive(item.url)"
                                        :severity="
                                            isActive(item.url)
                                                ? 'success'
                                                : 'secondary'
                                        "
                                        class="w-full !justify-start !gap-4 !rounded-xl !h-12 !px-4 !border-none"
                                        :class="
                                            isActive(item.url)
                                                ? '!bg-emerald-50 !text-emerald-700'
                                                : ''
                                        "
                                    >
                                        <component
                                            :is="item.icon"
                                            :class="[
                                                'w-5 h-5',
                                                isActive(item.url)
                                                    ? 'text-emerald-600'
                                                    : 'text-slate-400',
                                            ]"
                                        />
                                        <span class="text-[14px] font-medium">{{
                                            item.title
                                        }}</span>
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </Drawer>

            <main class="flex-1 p-6 md:p-10 relative bg-[#FBFBFB]">
                <div class="max-w-7xl mx-auto">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<style>
/* Scrollbar halus untuk sidebar */
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 20px;
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
