<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { computed, ref, onMounted, onUnmounted } from "vue";

const props = defineProps({
    canLogin: Boolean,
    namaMasjid: { type: String, default: "Masjid Besar Sambit" },
    qurbanData: { type: Array, default: () => [] },
});
// State untuk mendeteksi posisi scroll
const isScrolled = ref(false);

// Fungsi untuk mengecek posisi scroll (berubah jika scroll > 20px)
const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

// Pasang event listener saat komponen dimuat
onMounted(() => {
    window.addEventListener("scroll", handleScroll);
});

// Bersihkan event listener saat komponen dihancurkan (best practice)
onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
});

// Computed Qurban stats from qurbanData
const qurbanStats = computed(() => {
    let totalPeserta = 0;
    let totalSapi = 0;
    let totalKambing = 0;
    let totalKelompok = props.qurbanData?.length || 0;

    props.qurbanData?.forEach((k) => {
        const jumlahPeserta = k.shohibul_qurbans?.length || 0;
        totalPeserta += jumlahPeserta;

        const hewan = k.animal?.nama_hewan?.toLowerCase() || "";
        if (
            hewan.includes("sapi") ||
            hewan.includes("lembu") ||
            hewan.includes("cow")
        ) {
            totalSapi += 1;
        } else if (
            hewan.includes("kambing") ||
            hewan.includes("domba") ||
            hewan.includes("goat")
        ) {
            totalKambing += 1;
        }
    });

    return { totalPeserta, totalSapi, totalKambing, totalKelompok };
});

const features = [
    {
        title: "Imam Hafidz Qur'an",
        desc: "Dipimpin oleh imam yang hafidz Qur'an dan menguasai berbagai ragam nagham tilawah.",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>`,
    },
    {
        title: "Masjid Dilengkapi AC",
        desc: "Fasilitas pendingin udara menjamin kenyamanan beribadah di segala cuaca.",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>`,
    },
    {
        title: "Terbuka 24 Jam",
        desc: "Masjid terbuka sepanjang waktu dengan sistem keamanan dan penerangan yang memadai.",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
    },
    {
        title: "Karpet Terbaik",
        desc: "Menggunakan karpet premium yang lembut dan nyaman untuk sujud dan beribadah.",
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>`,
    },
];

const activities = [
    {
        title: "Kajian Fiqih, Kajian Ibadah 4 Madzhab dan Belajar Ngaji",
        desc: "Di setiap harinya, masjid akan melaksanakan banyak kegiatan keislaman, terbuka untuk masyarakat sekitar dan untuk umum.",
        img: "https://images.unsplash.com/photo-1584551246679-0daf3d275d0f?w=400&q=80",
    },
    {
        title: "Sembako Gratis dan Santunan Terhadap Dhuafa",
        desc: "Tersedia paket sembako gratis dan juga rutin melaksanakan kegiatan santunan terhadap dhuafa.",
        img: "https://images.unsplash.com/photo-1593113598332-cd288d649433?w=400&q=80",
    },
    {
        title: "Pendidikan dan Pelatihan Kewirausahaan",
        desc: "Masjid rutin membina jama'ah di segala usia agar memiliki keterampilan dan minat belajar yang tinggi demi masa depan yang cerah.",
        img: "https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=400&q=80",
    },
];

const galleryImages = [
    {
        src: "https://images.unsplash.com/photo-1545167496-5e27b7b03b5c?w=600&q=80",
        tall: true,
    },
    {
        src: "https://images.unsplash.com/photo-1591604021695-0c69b7c05981?w=600&q=80",
        tall: false,
    },
    {
        src: "https://images.unsplash.com/photo-1519817650390-64a993f9e40d?w=600&q=80",
        tall: false,
    },
    {
        src: "https://images.unsplash.com/photo-1564769662533-4f00a87b4056?w=600&q=80",
        tall: false,
    },
    {
        src: "https://images.unsplash.com/photo-1568667256549-094345857637?w=600&q=80",
        tall: false,
    },
    {
        src: "https://images.unsplash.com/photo-1589829085413-56de8ae18c73?w=600&q=80",
        tall: false,
    },
    {
        src: "https://images.unsplash.com/photo-1586611292717-f828b167408c?w=600&q=80",
        tall: false,
    },
];

const navLinks = [
    { label: "Beranda", href: "#beranda" },
    { label: "Layanan", href: "#layanan" },
    { label: "Galeri", href: "#galeri" },
    { label: "Kontak", href: "#kontak" },
];

const footerLinks = [
    { label: "Beranda", href: "#" },
    { label: "Blog", href: "#" },
    { label: "Kegiatan", href: "#" },
    { label: "Tentang", href: "#" },
    { label: "Kontak", href: "#" },
];

const kegiatanLinks = [
    "Kajian Fiqih",
    "Kajian Ibadah 4 Madzhab",
    "Santunan Dhuafa",
    "Pelatihan Kewirausahaan",
    "Belajar Ngaji",
];
</script>

<template>
    <Head :title="namaMasjid + ' — Portal Digital Masjid'" />

    <div class="font-sans antialiased text-gray-800 bg-white">
        <!-- ======= NAVBAR ======= -->
        <nav
            :class="[
                'fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-6 lg:px-16 h-[72px] transition-all duration-500 ease-out',
                isScrolled
                    ? 'bg-[#052e16]/95 backdrop-blur-2xl shadow-lg border-b border-emerald-900/50' // Style SAAT DI-SCROLL
                    : 'bg-white/5 backdrop-blur-xl border-b border-white/10', // Style POSISI ATAS
            ]"
        >
            <a href="#beranda" class="flex items-center gap-3 group">
                <div
                    class="w-10 h-10 rounded-full bg-white/10 border border-white/20 flex items-center justify-center group-hover:bg-white/20 transition-all duration-300 shadow-[0_0_15px_rgba(255,255,255,0.05)]"
                >
                    <svg
                        viewBox="0 0 36 36"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                    >
                        <path
                            d="M18 4C18 4 8 10 8 20V28H28V20C28 10 18 4 18 4Z"
                            fill="white"
                            fill-opacity="0.95"
                        />
                        <rect
                            x="14"
                            y="22"
                            width="8"
                            height="6"
                            fill="white"
                            fill-opacity="0.8"
                        />
                        <path
                            d="M12 20H24"
                            stroke="white"
                            stroke-opacity="0.8"
                            stroke-width="1.5"
                        />
                        <circle cx="18" cy="4" r="2" fill="white" />
                    </svg>
                </div>
                <span
                    class="font-semibold text-white tracking-wide text-sm sm:text-base hidden sm:block drop-shadow-sm"
                >
                    {{ namaMasjid }}
                </span>
            </a>

            <ul class="hidden md:flex items-center gap-2">
                <li v-for="link in navLinks" :key="link.label">
                    <a
                        :href="link.href"
                        class="px-4 py-2 rounded-full text-white/80 text-sm font-medium hover:text-white hover:bg-white/10 transition-all duration-300 ease-out"
                    >
                        {{ link.label }}
                    </a>
                </li>
            </ul>

            <div class="hidden sm:flex items-center gap-4">
                <a
                    href="#qurban"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-emerald-950 bg-white rounded-full hover:bg-gray-100 hover:scale-105 hover:shadow-[0_0_20px_rgba(255,255,255,0.3)] transition-all duration-300 ease-out"
                >
                    Laporan Publik
                </a>
            </div>

            <button
                class="md:hidden p-2 rounded-full text-white/80 hover:text-white hover:bg-white/10 transition-all duration-300"
                aria-label="Menu"
            >
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>
            </button>
        </nav>

        <!-- ======= HERO SECTION ======= -->
        <section
            id="beranda"
            class="relative min-h-screen flex items-center overflow-hidden"
        >
            <!-- Background image -->
            <div class="absolute inset-0">
                <img
                    src="/assets/img/background.png"
                    alt="Masjid Interior"
                    class="w-full h-full object-cover"
                />
                <div
                    class="absolute inset-0"
                    style="
                        background: linear-gradient(
                            135deg,
                            rgba(5, 78, 38, 0.82) 0%,
                            rgba(5, 50, 25, 0.7) 60%,
                            rgba(0, 0, 0, 0.5) 100%
                        );
                    "
                ></div>
            </div>

            <div
                class="relative z-10 max-w-6xl mx-auto px-6 lg:px-16 py-40 lg:py-48"
            >
                <!-- Badge -->
                <div
                    class="inline-flex items-center gap-2 bg-white/10 border border-white/25 backdrop-blur px-4 py-1.5 rounded-full mb-8"
                >
                    <span
                        class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"
                    ></span>
                    <span
                        class="text-white/90 text-xs font-medium tracking-wide"
                        >Sistem Manajemen Digital Aktif</span
                    >
                </div>

                <!-- Headline -->
                <h1
                    class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight max-w-3xl mb-6"
                >
                    Temukan kedamaian dan layanan umat melalui
                    <span class="text-emerald-300">{{ namaMasjid }}.</span>
                </h1>

                <!-- Subheadline -->
                <p
                    class="text-white/75 text-base md:text-lg max-w-xl leading-relaxed mb-10"
                >
                    Portal digital terintegrasi untuk pendaftaran Qurban, Zakat,
                    dan Laporan Keuangan secara transparan.
                </p>

                <!-- Buttons -->
                <div class="flex flex-wrap gap-4">
                    <a
                        href="#qurban"
                        class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white font-semibold px-7 py-3.5 rounded-xl transition-all shadow-lg shadow-emerald-900/40 hover:shadow-emerald-700/40 hover:-translate-y-0.5"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        Daftar Qurban 2026
                    </a>
                    <a
                        href="#kontak"
                        class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-semibold px-7 py-3.5 rounded-xl backdrop-blur-sm transition-all"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"
                            />
                            <path
                                d="M11.999 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2.05 21.5l4.443-1.163A9.953 9.953 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 11.999 2z"
                            />
                        </svg>
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </section>

        <!-- ======= FEATURES SECTION ======= -->
        <section
            id="layanan"
            class="py-24 bg-gradient-to-b from-gray-50 to-white"
        >
            <div class="max-w-6xl mx-auto px-6 lg:px-16">
                <!-- Label -->
                <div class="text-center mb-14">
                    <span
                        class="text-emerald-600 text-xs font-bold tracking-widest uppercase mb-3 block"
                        >Fitur</span
                    >
                    <h2
                        class="text-3xl md:text-4xl font-bold text-gray-900 leading-snug"
                    >
                        Semua Kenyamanan<br />dalam Satu Masjid
                    </h2>
                </div>

                <!-- Cards grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div
                        v-for="f in features"
                        :key="f.title"
                        class="flex flex-col items-center text-center p-6 rounded-2xl bg-white shadow-sm hover:shadow-md transition-shadow border border-gray-100 group"
                    >
                        <div
                            class="w-16 h-16 rounded-full bg-emerald-700 flex items-center justify-center text-white mb-4 group-hover:bg-emerald-600 transition-colors"
                            v-html="f.icon"
                        ></div>
                        <h3
                            class="font-semibold text-gray-800 text-sm leading-snug mb-2"
                        >
                            {{ f.title }}
                        </h3>
                        <p class="text-gray-500 text-xs leading-relaxed">
                            {{ f.desc }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======= KEGIATAN SECTION ======= -->
        <section
            class="py-24"
            style="
                background: linear-gradient(135deg, #054e26 0%, #076b35 100%);
            "
        >
            <div class="max-w-6xl mx-auto px-6 lg:px-16">
                <!-- Label -->
                <div class="text-center mb-14">
                    <span
                        class="text-emerald-300 text-xs font-bold tracking-widest uppercase mb-3 block"
                        >Kegiatan</span
                    >
                    <h2
                        class="text-3xl md:text-4xl font-bold text-white leading-snug"
                    >
                        Berbagai Kegiatan Menarik<br />Yang Tersedia di Masjid
                    </h2>
                </div>

                <!-- Activity cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div
                        v-for="act in activities"
                        :key="act.title"
                        class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow hover:-translate-y-1 transform duration-200"
                    >
                        <div class="h-44 overflow-hidden">
                            <img
                                :src="act.img"
                                :alt="act.title"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                            />
                        </div>
                        <div class="p-5">
                            <h3
                                class="font-bold text-gray-900 text-sm leading-snug mb-2"
                            >
                                {{ act.title }}
                            </h3>
                            <p class="text-gray-500 text-xs leading-relaxed">
                                {{ act.desc }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======= QURBAN STATS SECTION ======= -->
        <section id="qurban" class="py-24 bg-white">
            <div class="max-w-6xl mx-auto px-6 lg:px-16">
                <div class="text-center mb-14">
                    <span
                        class="text-emerald-600 text-xs font-bold tracking-widest uppercase mb-3 block"
                        >Qurban 2026</span
                    >
                    <h2
                        class="text-3xl md:text-4xl font-bold text-gray-900 leading-snug"
                    >
                        Statistik Qurban Real-Time
                    </h2>
                    <p class="text-gray-500 mt-3 text-sm max-w-md mx-auto">
                        Data pendaftaran qurban yang diperbarui secara langsung
                        dari sistem manajemen masjid.
                    </p>
                </div>

                <!-- Stats dashboard card -->
                <div
                    class="rounded-3xl overflow-hidden shadow-xl border border-gray-100"
                    style="
                        background: linear-gradient(
                            135deg,
                            #f0fdf4 0%,
                            #dcfce7 50%,
                            #f0fdf4 100%
                        );
                    "
                >
                    <div class="p-8 md:p-12">
                        <!-- Top stats row -->
                        <div
                            class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10"
                        >
                            <div
                                class="bg-white rounded-2xl p-6 shadow-sm text-center"
                            >
                                <div
                                    class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center mx-auto mb-3"
                                >
                                    <svg
                                        class="w-6 h-6 text-emerald-700"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                </div>
                                <div
                                    class="text-3xl font-bold text-emerald-700"
                                >
                                    {{ qurbanStats.totalPeserta }}
                                </div>
                                <div
                                    class="text-gray-500 text-xs mt-1 font-medium"
                                >
                                    Total Peserta
                                </div>
                            </div>
                            <div
                                class="bg-white rounded-2xl p-6 shadow-sm text-center"
                            >
                                <div
                                    class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center mx-auto mb-3"
                                >
                                    <svg
                                        class="w-6 h-6 text-amber-700"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"
                                        />
                                    </svg>
                                </div>
                                <div class="text-3xl font-bold text-amber-700">
                                    {{ qurbanStats.totalSapi }}
                                </div>
                                <div
                                    class="text-gray-500 text-xs mt-1 font-medium"
                                >
                                    Kelompok Sapi
                                </div>
                            </div>
                            <div
                                class="bg-white rounded-2xl p-6 shadow-sm text-center"
                            >
                                <div
                                    class="w-12 h-12 rounded-xl bg-sky-100 flex items-center justify-center mx-auto mb-3"
                                >
                                    <svg
                                        class="w-6 h-6 text-sky-700"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                        />
                                    </svg>
                                </div>
                                <div class="text-3xl font-bold text-sky-700">
                                    {{ qurbanStats.totalKambing }}
                                </div>
                                <div
                                    class="text-gray-500 text-xs mt-1 font-medium"
                                >
                                    Kelompok Kambing
                                </div>
                            </div>
                            <div
                                class="bg-white rounded-2xl p-6 shadow-sm text-center"
                            >
                                <div
                                    class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center mx-auto mb-3"
                                >
                                    <svg
                                        class="w-6 h-6 text-purple-700"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                        />
                                    </svg>
                                </div>
                                <div class="text-3xl font-bold text-purple-700">
                                    {{ qurbanStats.totalKelompok }}
                                </div>
                                <div
                                    class="text-gray-500 text-xs mt-1 font-medium"
                                >
                                    Total Kelompok
                                </div>
                            </div>
                        </div>

                        <!-- Kelompok list -->
                        <div v-if="qurbanData && qurbanData.length > 0">
                            <h3
                                class="font-bold text-gray-700 text-sm mb-4 flex items-center gap-2"
                            >
                                <span
                                    class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"
                                ></span>
                                Daftar Kelompok Qurban
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div
                                    v-for="kelompok in qurbanData"
                                    :key="kelompok.id"
                                    class="bg-white rounded-xl p-4 shadow-sm flex items-center justify-between border border-emerald-50 hover:border-emerald-200 transition-colors"
                                >
                                    <div>
                                        <div
                                            class="font-semibold text-gray-800 text-sm"
                                        >
                                            {{ kelompok.nama_kelompok }}
                                        </div>
                                        <div
                                            class="text-gray-400 text-xs mt-0.5 flex items-center gap-1"
                                        >
                                            <span class="capitalize">{{
                                                kelompok.animal?.nama_hewan ||
                                                "Hewan"
                                            }}</span>
                                            <span class="text-gray-300">·</span>
                                            <span
                                                >Batas
                                                {{
                                                    kelompok.animal
                                                        ?.batas_peserta || "—"
                                                }}
                                                peserta</span
                                            >
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div
                                            class="text-emerald-700 font-bold text-lg"
                                        >
                                            {{
                                                kelompok.shohibul_qurbans
                                                    ?.length || 0
                                            }}
                                        </div>
                                        <div class="text-gray-400 text-xs">
                                            peserta
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty state -->
                        <div v-else class="text-center py-10">
                            <div
                                class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4"
                            >
                                <svg
                                    class="w-7 h-7 text-emerald-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                    />
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">
                                Pendaftaran Qurban 2026 Segera Dibuka
                            </p>
                            <p class="text-gray-400 text-xs mt-1">
                                Pantau terus portal ini untuk info terbaru.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======= GALLERY SECTION ======= -->
        <section id="galeri" class="py-24 bg-gray-50">
            <div class="max-w-6xl mx-auto px-6 lg:px-16">
                <!-- Label -->
                <div class="text-center mb-14">
                    <span
                        class="text-emerald-600 text-xs font-bold tracking-widest uppercase mb-3 block"
                        >Foto</span
                    >
                    <h2
                        class="text-3xl md:text-4xl font-bold text-gray-900 leading-snug"
                    >
                        Menjelajahi Keindahan<br />Masjid Dari Berbagai Sisi
                    </h2>
                </div>

                <!-- Gallery grid — mirrors image layout -->
                <div
                    class="grid grid-cols-4 grid-rows-2 gap-3 h-96 md:h-[480px]"
                >
                    <!-- Large left image -->
                    <div
                        class="col-span-2 row-span-2 rounded-2xl overflow-hidden"
                    >
                        <img
                            :src="galleryImages[0].src"
                            alt="Masjid"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                        />
                    </div>
                    <!-- Top-right 2 columns -->
                    <div class="col-span-1 rounded-2xl overflow-hidden">
                        <img
                            :src="galleryImages[1].src"
                            alt="Masjid"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                        />
                    </div>
                    <div class="col-span-1 rounded-2xl overflow-hidden">
                        <img
                            :src="galleryImages[2].src"
                            alt="Masjid"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                        />
                    </div>
                    <!-- Bottom-right 3 images -->
                    <div class="col-span-1 rounded-2xl overflow-hidden">
                        <img
                            :src="galleryImages[3].src"
                            alt="Masjid"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                        />
                    </div>
                    <div class="col-span-1 rounded-2xl overflow-hidden">
                        <img
                            :src="galleryImages[4].src"
                            alt="Masjid"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                        />
                    </div>
                </div>
                <!-- Second row of gallery -->
                <div class="grid grid-cols-3 gap-3 mt-3 h-44 md:h-56">
                    <div class="rounded-2xl overflow-hidden">
                        <img
                            :src="galleryImages[5].src"
                            alt="Masjid"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                        />
                    </div>
                    <div class="col-span-2 rounded-2xl overflow-hidden">
                        <img
                            :src="galleryImages[6].src"
                            alt="Masjid"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- ======= MAP SECTION ======= -->
        <section id="kontak" class="py-24 bg-white">
            <div class="max-w-6xl mx-auto px-6 lg:px-16">
                <div class="text-center mb-14">
                    <span
                        class="text-emerald-600 text-xs font-bold tracking-widest uppercase mb-3 block"
                        >Lokasi</span
                    >
                    <h2
                        class="text-3xl md:text-4xl font-bold text-gray-900 leading-snug"
                    >
                        Lokasi Masjid Mudah Diakses
                    </h2>
                </div>

                <!-- Map placeholder -->
                <div
                    class="rounded-3xl overflow-hidden shadow-lg border border-gray-100 h-80 md:h-[420px] bg-gray-200 relative"
                >
                    <!-- Embed a real OpenStreetMap iframe as placeholder -->
                    <iframe
                        src="https://maps.google.com/maps?q=Masjid%20Besar%20Kecamatan%20Sambit,%20Ponorogo&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        class="w-full h-full"
                        style="border: 0"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi Masjid Besar Kecamatan Sambit"
                    ></iframe>
                    <!-- Overlay badge -->
                    <div
                        class="absolute bottom-5 left-5 bg-white rounded-xl shadow-lg px-4 py-3 flex items-center gap-3"
                    >
                        <div
                            class="w-10 h-10 rounded-full bg-emerald-700 flex items-center justify-center"
                        >
                            <svg
                                class="w-5 h-5 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 text-sm">
                                {{ namaMasjid }}
                            </div>
                            <div class="text-gray-500 text-xs">
                                Sambit, Ponorogo, Jawa Timur
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======= FOOTER ======= -->
        <footer
            class="pt-16 pb-8"
            style="
                background: linear-gradient(135deg, #0a2e1a 0%, #0d3b22 100%);
            "
        >
            <div class="max-w-6xl mx-auto px-6 lg:px-16">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                    <!-- Brand column -->
                    <div class="md:col-span-1">
                        <div class="flex items-center gap-2.5 mb-4">
                            <div
                                class="w-10 h-10 rounded-full bg-white/10 border border-white/20 flex items-center justify-center"
                            >
                                <svg
                                    viewBox="0 0 36 36"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6"
                                >
                                    <path
                                        d="M18 4C18 4 8 10 8 20V28H28V20C28 10 18 4 18 4Z"
                                        fill="white"
                                        fill-opacity="0.9"
                                    />
                                    <rect
                                        x="14"
                                        y="22"
                                        width="8"
                                        height="6"
                                        fill="white"
                                        fill-opacity="0.7"
                                    />
                                    <circle cx="18" cy="4" r="2" fill="white" />
                                </svg>
                            </div>
                            <span class="font-bold text-white text-sm">{{
                                namaMasjid
                            }}</span>
                        </div>
                        <p class="text-gray-400 text-xs leading-relaxed mb-6">
                            Masjid ini memiliki desain arsitektur indah dan
                            fasilitas lengkap, menyediakan lingkungan yang
                            nyaman untuk berbagai kegiatan keislaman komunitas.
                        </p>
                        <!-- Social icons -->
                        <div class="flex items-center gap-3">
                            <a
                                href="#"
                                class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors"
                                aria-label="Instagram"
                            >
                                <svg
                                    class="w-4 h-4 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"
                                    />
                                </svg>
                            </a>
                            <a
                                href="#"
                                class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors"
                                aria-label="YouTube"
                            >
                                <svg
                                    class="w-4 h-4 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"
                                    />
                                </svg>
                            </a>
                            <a
                                href="#"
                                class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors"
                                aria-label="Facebook"
                            >
                                <svg
                                    class="w-4 h-4 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                                    />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Halaman links -->
                    <div>
                        <h4 class="text-white font-semibold text-sm mb-5">
                            Halaman
                        </h4>
                        <ul class="space-y-3">
                            <li v-for="link in footerLinks" :key="link.label">
                                <a
                                    :href="link.href"
                                    class="text-gray-400 text-xs hover:text-emerald-300 transition-colors"
                                    >{{ link.label }}</a
                                >
                            </li>
                        </ul>
                    </div>

                    <!-- Kegiatan links -->
                    <div>
                        <h4 class="text-white font-semibold text-sm mb-5">
                            Kegiatan
                        </h4>
                        <ul class="space-y-3">
                            <li v-for="k in kegiatanLinks" :key="k">
                                <a
                                    href="#"
                                    class="text-gray-400 text-xs hover:text-emerald-300 transition-colors"
                                    >{{ k }}</a
                                >
                            </li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h4 class="text-white font-semibold text-sm mb-5">
                            Kontak
                        </h4>
                        <ul class="space-y-3 text-xs text-gray-400">
                            <li class="flex items-start gap-2">
                                <svg
                                    class="w-4 h-4 text-emerald-400 mt-0.5 shrink-0"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"
                                    />
                                    <path
                                        d="M11.999 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2.05 21.5l4.443-1.163A9.953 9.953 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 11.999 2z"
                                    />
                                </svg>
                                <span
                                    >0812-3456-7890
                                    <strong class="text-white"
                                        >(WA)</strong
                                    ></span
                                >
                            </li>
                            <li class="flex items-start gap-2">
                                <svg
                                    class="w-4 h-4 text-emerald-400 mt-0.5 shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    />
                                </svg>
                                <span>info@masjid-arroudhoh.id</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg
                                    class="w-4 h-4 text-emerald-400 mt-0.5 shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                                <span
                                    >Jl. Roudhoh No. 1, Malang, Jawa Timur</span
                                >
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Footer bottom -->
                <div class="border-t border-white/10 pt-6 text-center">
                    <p class="text-gray-500 text-xs">
                        © {{ new Date().getFullYear() }} {{ namaMasjid }} All
                        Rights Reserved
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
html {
    scroll-behavior: smooth;
}
</style>
