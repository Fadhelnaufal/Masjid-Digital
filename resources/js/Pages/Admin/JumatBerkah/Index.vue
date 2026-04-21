<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { format } from "date-fns";
import { id } from "date-fns/locale";
import {
    Plus,
    Wallet,
    CheckCircle2,
    Clock,
    ArrowUpRight,
    Loader2,
    Trash2,
    CheckCircle,
    RotateCcw,
    Pencil,
} from "lucide-vue-next";

// --- PRIME VUE COMPONENTS ---
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputNumber from "primevue/inputnumber";
import DatePicker from "primevue/datepicker";
import Tag from "primevue/tag";
import ConfirmDialog from "primevue/confirmdialog";
import Toast from 'primevue/toast';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

const props = defineProps({
    schedules: Array,
});

const confirm = useConfirm();
const toast = useToast();

// --- KONFIGURASI ---
const HARGA_PER_PORSI = 10000;

// --- STATE MANAGEMENT ---
const isModalOpen = ref(false);
const isEditMode = ref(false);
const editingId = ref(null);

const form = useForm({
    tanggal: "",
    target_porsi: 100,
});

// --- LOGIC FUNCTIONS ---
const getTotalPorsi = (item) => {
    const fisik = parseInt(item.terumpul_porsi || 0);
    const dariUang = Math.floor((item.total_dana || 0) / HARGA_PER_PORSI);
    return fisik + dariUang;
};

const getPercentage = (item) => {
    const target = item.target_porsi || 1;
    const total = getTotalPorsi(item);
    const percent = (total / target) * 100;
    return percent > 100 ? 100 : Math.round(percent);
};

const openCreateModal = () => {
    isEditMode.value = false;
    editingId.value = null;
    form.reset();
    isModalOpen.value = true;
};

const openEditModal = (item) => {
    isEditMode.value = true;
    editingId.value = item.id;
    // Penting: Ubah string tanggal dari DB menjadi Object Date untuk DatePicker
    form.tanggal = item.tanggal ? new Date(item.tanggal) : "";
    form.target_porsi = item.target_porsi;
    form.clearErrors();
    isModalOpen.value = true;
};

const submit = () => {
    // Format tanggal ke YYYY-MM-DD sebelum kirim ke MySQL
    if (form.tanggal && form.tanggal instanceof Date) {
        form.tanggal = format(form.tanggal, "yyyy-MM-dd");
    }

    const action = isEditMode.value 
        ? route("jumat-berkah.update", editingId.value) 
        : route("jumat-berkah.store");
    
    const method = isEditMode.value ? 'patch' : 'post';

    form[method](action, {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
            toast.add({ 
                severity: 'success', 
                summary: 'Berhasil', 
                detail: isEditMode.value ? 'Data diperbarui' : 'Jadwal diinisiasi', 
                life: 3000 
            });
        },
        onError: () => {
            // Kembalikan ke Date Object jika gagal agar DatePicker tidak crash
            if (form.tanggal) form.tanggal = new Date(form.tanggal);
        }
    });
};

// --- CONFIRMATION HANDLERS ---

const confirmDelete = (id) => {
    confirm.require({
        message: 'Hapus jadwal ini? Semua data donasi di dalamnya akan hilang permanen.',
        header: 'Konfirmasi Hapus',
        icon: 'pi pi-exclamation-triangle',
        rejectLabel: 'Batal',
        acceptLabel: 'Ya, Hapus',
        rejectClass: 'p-button-secondary p-button-outlined !rounded-xl',
        acceptClass: 'p-button-danger !rounded-xl !bg-rose-600',
        accept: () => {
            form.delete(route("jumat-berkah.destroy", id), { 
                preserveScroll: true,
                onSuccess: () => toast.add({ severity: 'info', summary: 'Dihapus', detail: 'Jadwal berhasil dihapus', life: 3000 })
            });
        }
    });
};

const confirmClose = (id) => {
    confirm.require({
        message: 'Selesaikan program ini? Input donasi baru akan ditutup.',
        header: 'Tutup Program',
        icon: 'pi pi-check-circle',
        rejectLabel: 'Batal',
        acceptLabel: 'Ya, Tutup',
        acceptClass: 'p-button-success !rounded-xl !bg-emerald-600',
        accept: () => {
            form.post(route("jumat-berkah.close", id), { 
                preserveScroll: true,
                onSuccess: () => toast.add({ severity: 'success', summary: 'Selesai', detail: 'Program telah ditutup', life: 3000 })
            });
        }
    });
};

const confirmReopen = (id) => {
    confirm.require({
        message: 'Buka kembali program untuk menerima donatur?',
        header: 'Buka Program',
        icon: 'pi pi-refresh',
        rejectLabel: 'Batal',
        acceptLabel: 'Buka',
        acceptClass: 'p-button-info !rounded-xl !bg-blue-600',
        accept: () => {
            form.post(route("jumat-berkah.reopen", id), { 
                preserveScroll: true,
                onSuccess: () => toast.add({ severity: 'info', summary: 'Dibuka', detail: 'Program aktif kembali', life: 3000 })
            });
        }
    });
};

const formatRupiah = (number) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(number || 0);
};
</script>

<template>
    <Head title="Jumat Berkah" />

    <AdminLayout>
        <Toast />
        <ConfirmDialog class="custom-confirm" />

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div class="space-y-1 text-left">
                <div class="flex items-center gap-2 text-emerald-600 font-bold text-[10px] uppercase tracking-[0.2em] mb-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></div>
                    Manajemen Operasional
                </div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight italic uppercase">Program Jumat Berkah</h2>
                <p class="text-slate-500 text-sm font-medium italic">Pantau distribusi nasi bungkus dan alokasi dana secara real-time.</p>
            </div>

            <Button @click="openCreateModal" class="!bg-emerald-600 hover:!bg-emerald-500 !rounded-xl !shadow-lg !shadow-emerald-100 !h-12 !px-6 !border-none transition-all active:scale-95">
                <Plus class="w-5 h-5 mr-2" />
                <span class="font-bold uppercase tracking-wider text-[11px]">Buat Jadwal Baru</span>
            </Button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 text-left">
            <div v-for="item in schedules" :key="item.id" class="group bg-white rounded-[32px] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-emerald-100/40 transition-all duration-500 overflow-hidden flex flex-col">
                <div class="p-8 flex-1">
                    <div class="flex justify-between items-start mb-8">
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                                {{ format(new Date(item.tanggal), "EEEE", { locale: id }) }}
                            </p>
                            <h4 class="text-xl font-bold text-slate-900 tracking-tight group-hover:text-emerald-700 transition-colors italic uppercase">
                                {{ format(new Date(item.tanggal), "dd MMM yyyy", { locale: id }) }}
                            </h4>
                        </div>

                        <div class="flex items-center gap-2">
                            <Tag :severity="item.status === 'Open' ? 'info' : 'success'" class="!rounded-full !px-3 !py-1 !text-[9px] !uppercase !tracking-wider">
                                <div class="flex items-center">
                                    <component :is="item.status === 'Open' ? Clock : CheckCircle2" class="w-3 h-3 mr-1.5" />
                                    {{ item.status }}
                                </div>
                            </Tag>

                            <div class="flex items-center gap-1 ml-1">
                                <button v-if="item.status === 'Open'" @click.stop="confirmClose(item.id)" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 transition-all shadow-sm">
                                    <CheckCircle class="w-4 h-4" />
                                </button>
                                <button v-else @click.stop="confirmReopen(item.id)" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-all shadow-sm">
                                    <RotateCcw class="w-4 h-4" />
                                </button>
                                <button @click.stop="openEditModal(item)" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-amber-50 hover:text-amber-600 transition-all shadow-sm">
                                    <Pencil class="w-4 h-4" />
                                </button>
                                <button @click.stop="confirmDelete(item.id)" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-all shadow-sm">
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-end">
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block font-sans">Progres Terakumulasi</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-black text-slate-900 italic">{{ getTotalPorsi(item) }}</span>
                                    <span class="text-sm font-bold text-slate-400">/ {{ item.target_porsi }}</span>
                                </div>
                            </div>
                            <span class="text-lg font-black italic text-emerald-600">{{ getPercentage(item) }}%</span>
                        </div>
                        <div class="relative h-3 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="absolute h-full bg-emerald-500 rounded-full transition-all duration-1000 ease-out" :style="{ width: `${getPercentage(item)}%` }"></div>
                        </div>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter italic font-sans">
                            {{ item.terumpul_porsi || 0 }} Fisik + {{ Math.floor((item.total_dana || 0) / HARGA_PER_PORSI) }} Porsi (Uang)
                        </p>
                    </div>
                </div>

                <div class="px-8 pb-8">
                    <div class="bg-slate-50 rounded-[24px] p-5 flex items-center justify-between border border-slate-100 group-hover:bg-emerald-50/50 group-hover:border-emerald-100 transition-all duration-300">
                        <div class="flex items-center gap-3 text-left">
                            <div class="w-11 h-11 rounded-xl bg-white flex items-center justify-center shadow-sm text-emerald-600 italic font-black">
                                <Wallet class="w-6 h-6" />
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Dana Terkumpul</p>
                                <p class="text-base font-black text-slate-900 italic">{{ formatRupiah(item.total_dana) }}</p>
                            </div>
                        </div>
                        <Link :href="route('jumat-berkah.show', item.id)">
                            <Button class="!w-10 !h-10 !rounded-xl !bg-white hover:!bg-emerald-600 hover:!text-white !text-slate-600 !transition-all !shadow-sm !border !border-slate-100 !p-0">
                                <ArrowUpRight class="w-5 h-5" />
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <Dialog v-model:visible="isModalOpen" modal :style="{ width: '425px' }" class="custom-dialog">
            <template #header>
                <div class="text-left">
                    <h3 class="text-xl font-bold text-slate-900 italic uppercase">{{ isEditMode ? "Perbarui Program" : "Inisiasi Program" }}</h3>
                    <p class="text-xs text-slate-500 italic">Tentukan tanggal dan target distribusi makanan.</p>
                </div>
            </template>

            <form @submit.prevent="submit" class="space-y-6 pt-4 text-left">
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400 ml-1">Tanggal Pelaksanaan</label>
                    <DatePicker v-model="form.tanggal" dateFormat="yy-mm-dd" showIcon iconDisplay="input" fluid placeholder="Pilih Tanggal Jumat" :inputClass="'!rounded-xl !border-slate-200 !h-12 !w-full !font-sans'" />
                    <small v-if="form.errors.tanggal" class="text-rose-500 font-medium ml-1">{{ form.errors.tanggal }}</small>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400 ml-1">Target Distribusi (Porsi)</label>
                    <InputNumber v-model="form.target_porsi" class="w-full" inputClass="!rounded-xl !border-slate-200 !h-12 w-full" required />
                    <small v-if="form.errors.target_porsi" class="text-rose-500 ml-1">{{ form.errors.target_porsi }}</small>
                </div>

                <Button type="submit" :disabled="form.processing" class="!w-full !bg-emerald-600 hover:!bg-emerald-700 !rounded-xl !h-12 !font-bold !border-none !mt-4">
                    <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin text-white" />
                    <span class="uppercase tracking-widest text-[11px] text-white font-bold">{{ isEditMode ? "Perbarui Data" : "Simpan Jadwal" }}</span>
                </Button>
            </form>
        </Dialog>
    </AdminLayout>
</template>

<style scoped>
/* Memaksa Popup Dialog & Confirm Jadi Terang */
:deep(.p-dialog), :deep(.p-confirm-dialog) {
    background: #ffffff !important;
    border-radius: 32px !important;
    border: none !important;
    color: #1e293b !important;
}

:deep(.p-dialog-header), :deep(.p-dialog-content), :deep(.p-dialog-footer) {
    background: #ffffff !important;
    color: #1e293b !important;
}

/* Styling Toast Emerald */
:deep(.p-toast-message-success) {
    background: #ecfdf5 !important;
    border-left: 6px solid #10b981 !important;
    border-radius: 16px !important;
}

/* DatePicker Styling */
:deep(.p-datepicker) { border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }
:deep(.p-datepicker .p-highlight) { background: #10b981 !important; color: white !important; }

main { animation: fadeIn 0.6s ease-out; }
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>