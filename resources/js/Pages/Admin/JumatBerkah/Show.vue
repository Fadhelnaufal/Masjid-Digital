<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { format } from "date-fns";
import { id } from "date-fns/locale";
import {
    ChevronLeft,
    Plus,
    Wallet,
    Utensils,
    User,
    Loader2,
    Trash2,
    CheckCircle2,
} from "lucide-vue-next";

// --- PRIME VUE COMPONENTS ---
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import Select from "primevue/select";
import Tag from "primevue/tag";
import ConfirmDialog from "primevue/confirmdialog";
import Toast from "primevue/toast";
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
const props = defineProps({
    schedule: Object,
});

const confirm = useConfirm();
const toast = useToast();

// State
const isModalOpen = ref(false);
const donations = computed(() => props.schedule.donations || []);

// Form
const form = useForm({
    nama_donatur: "",
    jenis_donasi: "Uang",
    jumlah_donasi: 0,
    jumlah_porsi: 0,
});

const jenisOptions = [
    { label: "Uang Tunai", value: "Uang" },
    { label: "Nasi Bungkus", value: "Makanan" },
];

// Fungsi Simpan Donasi
const submitDonation = () => {
    form.post(route("jumat-berkah.donations.store", props.schedule.id), {
        preserveScroll: true,
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset("nama_donatur", "jumlah_donasi", "jumlah_porsi");
            toast.add({
                severity: "success",
                summary: "Berhasil",
                detail: "Donasi berhasil dicatat",
                life: 3000,
            });
        },
        onError: (errors) => {
            // Jika ada error proteksi status 'Selesai' dari Service
            if (errors.message) {
                toast.add({
                    severity: "error",
                    summary: "Gagal",
                    detail: errors.message,
                    life: 4000,
                });
            }
        },
    });
};

const togglePenerimaan = (donationId) => {
    form.post(route("jumat-berkah.donations.toggle", donationId), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: "info",
                summary: "Updated",
                detail: "Status penerimaan diperbarui",
                life: 2000,
            });
        },
    });
};

const confirmDeleteDonation = (id) => {
    confirm.require({
        message: "Hapus catatan donasi ini secara permanen?",
        header: "Hapus Donasi",
        icon: "pi pi-trash",
        rejectLabel: "Batal",
        acceptLabel: "Hapus",
        acceptClass: "p-button-danger !bg-rose-600 !border-none",
        accept: () => {
            form.delete(route("jumat-berkah.donations.destroy", id), {
                preserveScroll: true,
                onSuccess: () =>
                    toast.add({
                        severity: "info",
                        summary: "Terhapus",
                        detail: "Catatan donasi dibuang",
                        life: 3000,
                    }),
            });
        },
    });
};

const formatRupiah = (number) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(number || 0);
};
// --- FILTERS UNTUK DATATABLE (JIKA DIBUTUHKAN) ---
const filters = ref({
    global: { value: null, matchMode: "contains" },
});
</script>

<template>
    <Head
        :title="'Detail Jumat ' + format(new Date(schedule.tanggal), 'dd MMM')"
    />

    <AdminLayout>
        <ConfirmDialog />
        <Toast />

        <div class="mb-10 text-left">
            <Link
                :href="route('jumat-berkah.index')"
                class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-emerald-600 transition-all group mb-4"
            >
                <ChevronLeft
                    class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform"
                />
                Kembali ke Ringkasan
            </Link>

            <div
                class="flex flex-col md:flex-row md:items-center justify-between gap-6"
            >
                <div class="space-y-1">
                    <h2
                        class="text-3xl font-black text-slate-900 tracking-tight italic uppercase"
                    >
                        Jumat,
                        {{
                            format(new Date(schedule.tanggal), "dd MMMM yyyy", {
                                locale: id,
                            })
                        }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium italic">
                        Validasi penerimaan donasi dan monitoring porsi.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <Tag
                        severity="secondary"
                        class="!hidden md:!flex !rounded-xl !px-4 !py-2.5 !bg-white !border !border-slate-200 !text-slate-500 !font-bold !shadow-sm"
                    >
                        Target: {{ schedule.target_porsi }} Porsi
                    </Tag>

                    <Button
                        v-if="schedule.status === 'Open'"
                        @click="isModalOpen = true"
                        class="!bg-emerald-600 hover:!bg-emerald-500 !rounded-xl !shadow-lg !shadow-emerald-100 !h-11 !px-6 !border-none !transition-all active:scale-95"
                    >
                        <Plus class="w-5 h-5 mr-2" />
                        <span
                            class="font-bold uppercase tracking-wider text-[11px]"
                            >Catat Donasi</span
                        >
                    </Button>
                    <Tag
                        v-else
                        severity="success"
                        class="!rounded-xl !px-6 !py-3 !font-black !uppercase !tracking-widest"
                        >Program Selesai</Tag
                    >
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 text-left">
            <div
                class="bg-white p-7 rounded-[32px] border border-slate-100 shadow-sm flex items-center gap-6 group hover:border-emerald-200 transition-all duration-300"
            >
                <div
                    class="w-16 h-16 rounded-[24px] bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-sm"
                >
                    <Wallet class="w-8 h-8" />
                </div>
                <div>
                    <p
                        class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1"
                    >
                        Total Dana Diterima
                    </p>
                    <h3
                        class="text-2xl font-black text-slate-900 tracking-tight italic"
                    >
                        {{ formatRupiah(schedule.total_dana) }}
                    </h3>
                </div>
            </div>

            <div
                class="bg-white p-7 rounded-[32px] border border-slate-100 shadow-sm flex items-center gap-6 group hover:border-blue-200 transition-all duration-300"
            >
                <div
                    class="w-16 h-16 rounded-[24px] bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500 shadow-sm"
                >
                    <Utensils class="w-8 h-8" />
                </div>
                <div>
                    <p
                        class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1"
                    >
                        Total Porsi Terkumpul
                    </p>
                    <h3
                        class="text-2xl font-black text-slate-900 tracking-tight italic"
                    >
                        {{ schedule.total_akumulasi }}
                        <span
                            class="text-slate-300 font-bold text-lg not-italic"
                            >/ {{ schedule.target_porsi }}</span
                        >
                    </h3>
                    <p
                        class="text-[9px] text-slate-400 font-bold mt-1 uppercase tracking-tighter italic font-sans"
                    >
                        {{ schedule.terumpul_porsi || 0 }} Fisik +
                        {{ schedule.porsi_uang }} Konversi Uang
                    </p>
                </div>
            </div>
        </div>

            <div
                class="bg-white rounded-sm border border-slate-100 shadow-sm overflow-hidden mb-12"
            >
                <div
                    class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white"
                >
                    <div class="flex items-center gap-3">
                        <h3
                            class="text-lg font-bold text-slate-900 tracking-tight italic uppercase"
                        >
                            Konfirmasi Serah Terima
                        </h3>
                        <Tag
                            severity="info"
                            class="!rounded-full !bg-slate-50 !text-slate-400 !border-none !font-bold !text-[10px] !px-3 !uppercase"
                        >
                            {{ donations.length }} Partisipan
                        </Tag>
                    </div>

                    <IconField iconPosition="left">
                        <InputIcon class="pi pi-search" />
                        <InputText
                            v-model="filters['global'].value"
                            placeholder="Cari nama donatur..."
                            class="!rounded-xl !text-sm !h-10 !border-slate-100"
                        />
                    </IconField>
                </div>

                <DataTable
                    :value="donations"
                    v-model:filters="filters"
                    dataKey="id"
                    paginator
                    :rows="10"
                    filterDisplay="menu"
                    :globalFilterFields="['nama_donatur']"
                    responsiveLayout="stack"
                    breakpoint="960px"
                    class="p-datatable-custom"
                >
                    <Column
                        field="nama_donatur"
                        header="Donatur"
                        sortable
                        class="px-8 py-5"
                    >
                        <template #body="slotProps">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400"
                                >
                                    <User class="w-5 h-5" />
                                </div>
                                <span class="font-bold text-slate-900">{{
                                    slotProps.data.nama_donatur
                                }}</span>
                            </div>
                        </template>
                    </Column>

                    <Column
                        field="jenis_donasi"
                        header="Jenis"
                        class="px-8 py-5"
                    >
                        <template #body="slotProps">
                            <Tag
                                :severity="
                                    slotProps.data.jenis_donasi === 'Uang'
                                        ? 'success'
                                        : 'info'
                                "
                                class="!rounded-lg !font-black !text-[9px] !uppercase !tracking-widest !px-2.5"
                            >
                                {{ slotProps.data.jenis_donasi }}
                            </Tag>
                        </template>
                    </Column>

                    <Column header="Kuantitas" class="px-8 py-5">
                        <template #body="slotProps">
                            <span class="font-black text-slate-700 italic">
                                {{
                                    slotProps.data.jenis_donasi === "Uang"
                                        ? formatRupiah(
                                              slotProps.data.jumlah_donasi,
                                          )
                                        : slotProps.data.jumlah_porsi + " Porsi"
                                }}
                            </span>
                        </template>
                    </Column>

                    <Column
                        field="status_distribusi"
                        header="Status Penerimaan"
                        sortable
                        class="px-8 py-5"
                    >
                        <template #body="slotProps">
                            <div class="flex items-center gap-2">
                                <div
                                    :class="
                                        slotProps.data.status_distribusi
                                            ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]'
                                            : 'bg-slate-200'
                                    "
                                    class="w-2 h-2 rounded-full transition-all"
                                ></div>
                                <span
                                    class="text-[10px] font-bold uppercase tracking-tighter"
                                    :class="
                                        slotProps.data.status_distribusi
                                            ? 'text-emerald-600'
                                            : 'text-slate-400'
                                    "
                                >
                                    {{
                                        slotProps.data.status_distribusi
                                            ? "Diterima"
                                            : "Menunggu"
                                    }}
                                </span>
                            </div>
                        </template>
                    </Column>

                    <Column
                        header="Aksi"
                        class="px-8 py-5"
                        headerStyle="text-align: right"
                        bodyStyle="text-align: right"
                    >
                        <template #body="slotProps">
                            <div class="flex items-center justify-end gap-2">
                                <button
                                    v-if="schedule.status === 'Open'"
                                    @click="togglePenerimaan(slotProps.data.id)"
                                    :class="
                                        slotProps.data.status_distribusi
                                            ? 'text-emerald-500 bg-emerald-50'
                                            : 'text-slate-400 hover:text-emerald-500 hover:bg-emerald-50'
                                    "
                                    class="w-8 h-8 flex items-center justify-center rounded-full transition-all"
                                >
                                    <CheckCircle2 class="w-4 h-4" />
                                </button>
                                <button
                                    @click="
                                        confirmDeleteDonation(slotProps.data.id)
                                    "
                                    class="w-8 h-8 flex items-center justify-center rounded-full text-slate-300 hover:text-rose-500 hover:bg-rose-50 transition-all"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </template>
                    </Column>

                    <template #empty>
                        <div
                            class="py-20 text-center text-slate-400 italic text-sm"
                        >
                            Belum ada donasi yang dicatat.
                        </div>
                    </template>
                </DataTable>
            </div>

        <Dialog
            v-model:visible="isModalOpen"
            modal
            :style="{ width: '425px' }"
            class="custom-dialog"
        >
            <template #header>
                <div class="text-left">
                    <h3
                        class="text-xl font-bold text-slate-900 italic uppercase"
                    >
                        Catat Donasi
                    </h3>
                    <p class="text-xs text-slate-500 italic">
                        Input data jamaah yang memberikan kontribusi.
                    </p>
                </div>
            </template>

            <form
                @submit.prevent="submitDonation"
                class="space-y-6 pt-4 text-left"
            >
                <div class="flex flex-col gap-2">
                    <label
                        class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1"
                        >Nama Donatur</label
                    >
                    <InputText
                        v-model="form.nama_donatur"
                        placeholder="Contoh: Hamba Allah"
                        class="!rounded-xl !h-12 !border-slate-200"
                        required
                    />
                </div>

                <div class="flex flex-col gap-2">
                    <label
                        class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1"
                        >Jenis Kontribusi</label
                    >
                    <Select
                        v-model="form.jenis_donasi"
                        :options="jenisOptions"
                        optionLabel="label"
                        optionValue="value"
                        class="w-full !rounded-xl !h-12 !border-slate-200"
                    />
                </div>

                <div
                    v-if="form.jenis_donasi === 'Uang'"
                    class="flex flex-col gap-2 animate-in fade-in slide-in-from-top-2"
                >
                    <label
                        class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1"
                        >Nominal (IDR)</label
                    >
                    <InputNumber
                        v-model="form.jumlah_donasi"
                        class="w-full"
                        inputClass="!rounded-xl !h-12 !border-slate-200 !w-full"
                        required
                    />
                </div>

                <div
                    v-else
                    class="flex flex-col gap-2 animate-in fade-in slide-in-from-top-2"
                >
                    <label
                        class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1"
                        >Jumlah Porsi</label
                    >
                    <InputNumber
                        v-model="form.jumlah_porsi"
                        class="w-full"
                        inputClass="!rounded-xl !h-12 !border-slate-200 !w-full"
                        required
                    />
                </div>

                <Button
                    type="submit"
                    :disabled="form.processing"
                    class="!w-full !bg-emerald-600 hover:!bg-emerald-700 !h-12 !rounded-xl !font-bold !border-none !mt-4 transition-all uppercase tracking-widest text-[11px]"
                >
                    <Loader2
                        v-if="form.processing"
                        class="w-4 h-4 mr-2 animate-spin"
                    />
                    <span class="text-white font-bold">Simpan Donasi</span>
                </Button>
            </form>
        </Dialog>
    </AdminLayout>
</template>

<style scoped>
:deep(.p-dialog) {
    border-radius: 32px;
    border: none;
    padding: 1.5rem;
    background: #ffffff !important;
}
:deep(.p-dialog-header),
:deep(.p-dialog-content) {
    background: #ffffff !important;
    color: #1e293b !important;
}

/* Styling Toast & Confirm */
:deep(.p-toast-message-success) {
    background: #ecfdf5 !important;
    border-left: 6px solid #10b981 !important;
    border-radius: 16px !important;
}

main {
    animation: fadeIn 0.6s ease-out;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
