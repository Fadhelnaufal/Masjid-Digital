<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { format } from "date-fns";
import { id } from "date-fns/locale";
import {
    Plus,
    TrendingUp,
    Calendar,
    Trash2,
    Loader2,
    Landmark,
    ReceiptText,
    FileSpreadsheet,
    FileText,
} from "lucide-vue-next";

// --- PRIME VUE COMPONENTS ---
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import DatePicker from "primevue/datepicker";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import Toast from "primevue/toast";
import ConfirmDialog from "primevue/confirmdialog";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";

const props = defineProps({
    infaqs: Array,
    stats: Object,
});

const toast = useToast();
const confirm = useConfirm();
const isModalOpen = ref(false);
const filters = ref({
    global: { value: null, matchMode: "contains" },
});

const form = useForm({
    tanggal: new Date(),
    nominal: null, // Di-set null agar input awal bersih
    keterangan: "",
});

const submit = () => {
    // Pastikan tanggal diformat string YYYY-MM-DD untuk Laravel
    let formattedDate = form.tanggal;
    if (form.tanggal instanceof Date) {
        formattedDate = format(form.tanggal, "yyyy-MM-dd");
    }

    form.transform((data) => ({
        ...data,
        tanggal: formattedDate,
    })).post(route("infaq.store"), {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
            form.nominal = null; // Memastikan input nominal benar-benar kosong untuk input selanjutnya
            toast.add({
                severity: "success",
                summary: "Berhasil",
                detail: "Catatan infaq telah disimpan",
                life: 3000,
            });
        },
        onError: () => {
            // Jika error, kembalikan format tanggal ke object Date agar DatePicker tidak crash
            if (typeof form.tanggal === "string")
                form.tanggal = new Date(form.tanggal);
        },
    });
};

const deleteInfaq = (id) => {
    confirm.require({
        message:
            "Hapus catatan infaq ini? Data saldo akan ikut terupdate otomatis.",
        header: "Konfirmasi Hapus",
        icon: "pi pi-exclamation-triangle",
        acceptLabel: "Ya, Hapus",
        rejectLabel: "Batal",
        acceptClass: "p-button-danger !rounded-xl !bg-rose-600 !border-none",
        rejectClass: "p-button-secondary !rounded-xl !text-slate-500",
        accept: () => {
            form.delete(route("infaq.destroy", id), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.add({
                        severity: "info",
                        summary: "Dihapus",
                        detail: "Catatan infaq berhasil dibuang",
                        life: 3000,
                    });
                },
            });
        },
    });
};
// Fungsi untuk Export PDF
const exportPdf = () => {
    // Kita arahkan ke route export dengan parameter format 'pdf'
    // window.open akan membuka tab baru sekejap lalu mendownload file
    const url = route('infaq.export', { format: 'pdf' });
    window.open(url, '_blank');
};

// Fungsi untuk Export Excel
const exportExcel = () => {
    // 1. Buat elemen link siluman
    const link = document.createElement('a');
    
    // 2. Set URL-nya ke route export excel
    link.href = route('infaq.export'); 
    
    // 3. Tambahkan atribut download (ini kuncinya agar tidak buka tab baru)
    link.setAttribute('download', 'Laporan_Infaq.xlsx');
    
    // 4. Masukkan ke dokumen sebentar, klik, lalu hapus lagi
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
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
    <Head title="Kas Infaq Masjid" />

    <AdminLayout>
        <Toast />
        <ConfirmDialog />

        <div
            class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 px-2 text-left"
        >
            <div class="space-y-1">
                <div
                    class="flex items-center gap-2 text-amber-600 font-bold text-[9px] uppercase tracking-[0.2em] mb-1"
                >
                    <div
                        class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"
                    ></div>
                    Financial Report
                </div>
                <h2
                    class="text-2xl font-black text-slate-900 tracking-tight italic uppercase"
                >
                    Kas Infaq
                </h2>
                <p class="text-slate-500 text-xs font-medium italic">
                    Pencatatan saldo masuk kas masjid harian.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div
                    class="flex items-center bg-white p-1 rounded-xl border border-slate-200 shadow-sm"
                >
                    <Button
                        @click="exportPdf"
                        v-tooltip.top="'Cetak Laporan PDF'"
                        severity="secondary"
                        text
                        class="!h-9 !w-9 !p-0 !flex !items-center !justify-center !rounded-lg hover:!bg-rose-50 !transition-all"
                    >
                        <FileText class="w-4 h-4 text-rose-500" />
                    </Button>

                    <div class="w-[1px] h-4 bg-slate-200 mx-1"></div>

                    <Button
                        @click="exportExcel"
                        v-tooltip.top="'Download Data Excel'"
                        severity="secondary"
                        text
                        class="!h-9 !w-9 !p-0 !flex !items-center !justify-center !rounded-lg hover:!bg-emerald-50 !transition-all"
                    >
                        <FileSpreadsheet class="w-4 h-4 text-emerald-500" />
                    </Button>
                </div>

                <Button
                    @click="isModalOpen = true"
                    v-tooltip.top="'Catat Pemasukan Baru'"
                    class="!bg-amber-600 hover:!bg-amber-700 !text-white !rounded-xl !shadow-lg !shadow-amber-100 !h-11 !px-5 !border-none !transition-all active:scale-95"
                >
                    <Plus class="w-4 h-4 mr-2" />
                    <span class="font-bold uppercase tracking-wider text-[10px]"
                        >Catat Infaq</span
                    >
                </Button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 text-left">
            <div
                v-for="(stat, index) in [
                    {
                        label: 'Total Saldo',
                        val: stats.total_nominal,
                        icon: Landmark,
                        color: 'amber',
                    },
                    {
                        label: 'Bulan Ini',
                        val: stats.bulan_ini,
                        icon: TrendingUp,
                        color: 'emerald',
                    },
                    {
                        label: 'Transaksi',
                        val: stats.total_transaksi + ' Data',
                        icon: ReceiptText,
                        color: 'blue',
                    },
                ]"
                :key="index"
                class="bg-white p-6 min-h-[110px] rounded-[28px] border border-slate-100 shadow-md shadow-slate-100/50 flex items-center gap-5 group transition-all duration-300 hover:border-slate-200 hover:shadow-xl hover:shadow-slate-200/40 hover:-translate-y-1"
            >
                <div
                    :class="`w-14 h-14 rounded-[20px] bg-${stat.color}-50 text-${stat.color}-600 group-hover:bg-${stat.color}-600 group-hover:text-white`"
                    class="flex items-center justify-center transition-all duration-500 shadow-sm flex-shrink-0"
                >
                    <component :is="stat.icon" class="w-6 h-6" />
                </div>

                <div class="space-y-1">
                    <p
                        class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em]"
                    >
                        {{ stat.label }}
                    </p>
                    <h3
                        class="text-xl font-black text-slate-900 italic tracking-tight"
                    >
                        {{
                            stat.label.includes("Transaksi")
                                ? stat.val
                                : formatRupiah(stat.val)
                        }}
                    </h3>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-[12px] border border-slate-100 shadow-sm overflow-hidden mb-12 px-4"
        >
            <div
                class="p-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4"
            >
                <div class="text-left">
                    <h3 class="text-md font-bold text-slate-900 uppercase">
                        Riwayat Infaq
                    </h3>
                </div>
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search text-[10px]" />
                    <InputText
                        v-model="filters['global'].value"
                        placeholder="Cari transaksi..."
                        class="!rounded-xl !text-xs !h-9 !w-60 !border-slate-100 !bg-slate-50/50"
                    />
                </IconField>
            </div>

            <DataTable
                :value="infaqs"
                tableStyle="min-width: 50rem"
                v-model:filters="filters"
                dataKey="id"
                paginator
                :rows="10"
                :globalFilterFields="['keterangan']"
                class="p-datatable-sm"
            >
                <Column
                    field="tanggal"
                    header="Tanggal"
                    sortable
                    class="px-6 py-4"
                >
                    <template #body="slotProps">
                        <div
                            class="flex items-center gap-2 text-xs font-bold text-slate-600"
                        >
                            <Calendar class="w-3.5 h-3.5 text-slate-300" />
                            {{
                                format(
                                    new Date(slotProps.data.tanggal),
                                    "dd MMM yyyy",
                                    { locale: id },
                                )
                            }}
                        </div>
                    </template>
                </Column>

                <Column
                    field="keterangan"
                    header="Keterangan"
                    class="px-6 py-4"
                >
                    <template #body="slotProps">
                        <span
                            class="font-bold text-slate-800 uppercase text-[10px] tracking-tight"
                        >
                            {{ slotProps.data.keterangan }}
                        </span>
                    </template>
                </Column>

                <Column
                    field="nominal"
                    header="Nominal"
                    sortable
                    class="px-6 py-4"
                >
                    <template #body="slotProps">
                        <span class="font-black text-emerald-600 text-sm">
                            {{ formatRupiah(slotProps.data.nominal) }}
                        </span>
                    </template>
                </Column>

                <Column
                    header="Aksi"
                    headerStyle="text-align: center"
                    bodyStyle="text-align: center"
                    class="px-6 py-4"
                >
                    <template #body="slotProps">
                        <div class="flex justify-center items-center">
                            <Button
                                @click="deleteInfaq(slotProps.data.id)"
                                severity="danger"
                                text
                                rounded
                                class="!w-8 !h-8 !p-0 !inline-flex !items-center !justify-center !text-slate-300 hover:!text-rose-500 hover:!bg-rose-50 !transition-all !duration-300"
                            >
                                <Trash2 class="w-4 h-4" />
                            </Button>
                        </div>
                    </template>
                </Column>

                <template #empty>
                    <div
                        class="py-20 text-center opacity-20 font-bold uppercase text-xs"
                    >
                        Belum ada data mutasi
                    </div>
                </template>
            </DataTable>
        </div>

        <Dialog
            v-model:visible="isModalOpen"
            modal
            :style="{ width: '400px' }"
            class="custom-dialog-compact"
        >
            <template #header>
                <div class="text-left">
                    <h3 class="text-xl font-black text-slate-900 uppercase">
                        Input Infaq
                    </h3>
                    <p class="text-[10px] text-slate-500">
                        Catat mutasi kas masuk masjid hari ini.
                    </p>
                </div>
            </template>

            <form @submit.prevent="submit" class="space-y-5 pt-2 text-left">
                <div class="flex flex-col gap-1.5">
                    <label
                        class="text-[9px] font-bold uppercase tracking-widest text-slate-400 ml-1"
                        >Tanggal</label
                    >
                    <DatePicker
                        v-model="form.tanggal"
                        dateFormat="yy-mm-dd"
                        showIcon
                        iconDisplay="input"
                        fluid
                        :inputClass="'!rounded-xl !h-11 !px-4 !bg-slate-50 !border-none !text-xs !font-bold'"
                    />
                </div>

                <div class="flex flex-col gap-1.5">
                    <label
                        class="text-[9px] font-bold uppercase tracking-widest text-slate-400 ml-1"
                        >Nominal (IDR)</label
                    >
                    <InputNumber
                        v-model="form.nominal"
                        mode="currency"
                        currency="IDR"
                        locale="id-ID"
                        class="w-full"
                        inputClass="!w-full !h-11 !px-4 !rounded-xl !bg-slate-50 !border-none !font-black ! !text-md"
                        placeholder="Rp 0"
                        selectOnFocus
                        required
                    />
                </div>

                <div class="flex flex-col gap-1.5">
                    <label
                        class="text-[9px] font-bold uppercase tracking-widest text-slate-400 ml-1"
                        >Keterangan</label
                    >
                    <InputText
                        v-model="form.keterangan"
                        placeholder="Contoh: Tromol Jumat"
                        class="!w-full !h-11 !px-4 !rounded-xl !bg-slate-50 !border-none !font-bold !text-xs"
                        required
                    />
                </div>

                <Button
                    type="submit"
                    :disabled="form.processing"
                    class="!w-full !h-11 !bg-amber-600 hover:!bg-amber-700 !text-white !rounded-xl !border-none !shadow-lg !shadow-amber-100 !mt-2"
                >
                    <Loader2
                        v-if="form.processing"
                        class="w-4 h-4 mr-2 animate-spin text-white"
                    />
                    <span
                        class="font-black uppercase tracking-widest text-[10px] text-white"
                        >Simpan Laporan</span
                    >
                </Button>
            </form>
        </Dialog>
    </AdminLayout>
</template>

<style scoped>
:deep(.p-datatable-thead > tr > th) {
    background: #f8fafc;
    color: #94a3b8;
    font-size: 9px;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    padding: 0.85rem 1.5rem;
    border: none;
}
:deep(.p-datatable-tbody > tr > td) {
    padding: 0.75rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
}
:deep(.p-datatable-tbody > tr:hover) {
    background: #f8fafc !important;
}
:deep(.p-dialog) {
    border-radius: 24px;
    border: none;
    padding: 1.5rem;
}
:deep(.p-dialog-header),
:deep(.p-dialog-content) {
    background: #ffffff !important;
    padding: 0.5rem 1rem !important;
}
:deep(.p-datepicker .p-highlight) {
    background: #d97706 !important;
    color: white !important;
}
main {
    animation: fadeIn 0.5s ease-out;
}
@keyframes fadeIn {
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
