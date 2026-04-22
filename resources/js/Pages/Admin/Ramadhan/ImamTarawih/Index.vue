<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { format } from "date-fns"; // INI YANG TADI KURANG
import { 
    CalendarDays, Pencil, UserCheck, Loader2, 
    AlertCircle, CheckCircle2, Mic2, BookOpen, Calendar
} from "lucide-vue-next";

// --- PRIME VUE ---
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Dialog from "primevue/dialog";
import Select from "primevue/select";
import InputText from "primevue/inputtext";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import Toast from "primevue/toast";
import DatePicker from "primevue/datepicker";
import { useToast } from "primevue/usetoast";

const props = defineProps({
    schedules: Array,   
    masterImam: Array,  
    currentYear: String 
});

const toast = useToast();
const isModalOpen = ref(false);
const selectedYear = ref(props.currentYear);
const filters = ref({ global: { value: null, matchMode: "contains" } });

const totalMalam = computed(() => props.schedules?.length || 0);
const terisiMalam = computed(() => props.schedules?.filter(s => s.imam_id !== null).length || 0);
const sisaMalam = computed(() => totalMalam.value - terisiMalam.value);

const form = useForm({
    id: null,
    imam_id: null,          
    imam_cadangan_id: null,
    imam_subuh_id: null,
    bilal: "",
    penceramah: "",
    tema_ceramah: "",
});

const setupForm = useForm({
    tahun: props.currentYear,
    tanggal_mulai: null,
});

const changeYear = () => {
    router.get(route('admin.ramadhan.imam.index'), { tahun: selectedYear.value }, { preserveState: true });
};

const handleGenerate = () => {
    setupForm.tahun = selectedYear.value;
    
    // Transformasi tanggal agar sesuai format MySQL (YYYY-MM-DD)
    setupForm.transform((data) => ({
        ...data,
        tanggal_mulai: data.tanggal_mulai ? format(new Date(data.tanggal_mulai), "yyyy-MM-dd") : null,
    })).post(route('admin.ramadhan.generate'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Jadwal 30 hari telah dibuat', life: 3000 });
        },
        onError: (errors) => {
            console.error(errors);
            toast.add({ severity: 'error', summary: 'Gagal', detail: 'Pastikan tanggal sudah dipilih', life: 3000 });
        }
    });
};

const openModal = (data) => {
    form.id = data.id;
    form.imam_id = data.imam_id;
    form.imam_cadangan_id = data.imam_cadangan_id;
    form.imam_subuh_id = data.imam_subuh_id;
    form.bilal = data.bilal || "";
    form.penceramah = data.penceramah || "";
    form.tema_ceramah = data.tema_ceramah || "";
    isModalOpen.value = true;
};

const submit = () => {
    form.patch(route('admin.ramadhan.imam.update', form.id), {
        preserveScroll: true,
        onSuccess: () => {
            isModalOpen.value = false;
            toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Plotting diperbarui', life: 3000 });
        },
    });
};
</script>

<template>
    <Head title="Data Imam Tarawih" />
    <AdminLayout>
        <Toast />

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 px-2 text-left">
            <div class="space-y-1">
                <div class="flex items-center gap-2 text-emerald-600 font-bold text-[9px] uppercase tracking-[0.2em] mb-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></div>
                    Ramadhan Schedule
                </div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight italic uppercase">
                    Imam Tarawih {{ currentYear }}
                </h2>
                <p class="text-slate-500 text-xs font-medium italic">Mapping petugas shalat harian.</p>
            </div>

            <div class="flex items-center gap-3 bg-white p-1.5 rounded-xl border border-slate-200 shadow-sm">
                <span class="text-[9px] font-bold text-slate-400 uppercase ml-2 tracking-widest">Tahun:</span>
                <select v-model="selectedYear" @change="changeYear" class="border-none bg-slate-50 rounded-lg text-xs font-black italic focus:ring-0 cursor-pointer py-1 pr-8">
                    <option v-for="y in [2025, 2026, 2027, 2028, 2029]" :key="y" :value="y">{{ y }}</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 text-left">
            <div v-for="(stat, index) in [
                { label: 'Total Malam', val: totalMalam + ' Hari', icon: Calendar, color: 'blue' },
                { label: 'Ter-Plotting', val: terisiMalam + ' Data', icon: UserCheck, color: 'emerald' },
                { label: 'Belum Set', val: sisaMalam + ' Data', icon: AlertCircle, color: 'rose' },
            ]" :key="index"
            class="bg-white p-6 min-h-[110px] rounded-[28px] border border-slate-100 shadow-md flex items-center gap-5 group transition-all">
                <div :class="`w-14 h-14 rounded-[20px] bg-${stat.color}-50 text-${stat.color}-600 group-hover:bg-${stat.color}-600 group-hover:text-white`"
                    class="flex items-center justify-center transition-all duration-500 shadow-sm">
                    <component :is="stat.icon" class="w-6 h-6" />
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em]">{{ stat.label }}</p>
                    <h3 class="text-xl font-black text-slate-900 italic tracking-tight">{{ stat.val }}</h3>
                </div>
            </div>
        </div>

        <div v-if="schedules.length === 0" class="bg-white rounded-[24px] border border-slate-100 p-12 text-center mb-12 shadow-sm border-dashed border-2">
            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-300">
                <CalendarDays class="w-8 h-8" />
            </div>
            <h3 class="text-lg font-bold text-slate-900 uppercase tracking-tight">Inisialisasi Jadwal {{ selectedYear }}</h3>
            <p class="text-slate-500 text-[11px] mb-8 font-medium italic">Belum ada data untuk tahun ini. Tentukan tanggal 1 Ramadhan:</p>
            
            <div class="flex flex-col md:flex-row justify-center items-end gap-4 max-w-sm mx-auto font-sans">
                <div class="flex-1 text-left w-full">
                    <label class="text-[9px] font-bold text-slate-400 uppercase ml-2 mb-1 block">Tgl 1 Ramadhan</label>
                    <DatePicker 
                        v-model="setupForm.tanggal_mulai" 
                        dateFormat="yy-mm-dd" 
                        showIcon 
                        iconDisplay="input"
                        placeholder="Pilih Tanggal"
                        fluid
                        :inputClass="'!rounded-xl !h-10 !px-4 !bg-slate-100 !border-none !text-xs !font-bold'"
                    />
                </div>
                <Button @click="handleGenerate" :disabled="!setupForm.tanggal_mulai || setupForm.processing" class="!bg-slate-900 !text-white !rounded-xl !px-6 !h-10 !border-none !text-[10px] font-bold uppercase tracking-widest active:scale-95 transition-all">
                    <Loader2 v-if="setupForm.processing" class="w-3 h-3 mr-2 animate-spin" />
                    Generate
                </Button>
            </div>
        </div>

        <div v-else class="bg-white rounded-[12px] border border-slate-100 shadow-sm overflow-hidden mb-12 px-4">
            <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-tight">Penugasan Tahun {{ currentYear }}</h3>
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search text-[10px]" />
                    <InputText v-model="filters['global'].value" placeholder="Cari petugas..." class="!rounded-xl !text-xs !h-9 !w-60 !border-slate-100 !bg-slate-50/50" />
                </IconField>
            </div>

            <DataTable :value="schedules" v-model:filters="filters" dataKey="id" paginator :rows="10" class="p-datatable-sm" tableStyle="min-width: 60rem">
                <Column field="hari_ke" header="Malam" sortable class="px-6 py-4">
                    <template #body="{ data }">
                        <div class="flex flex-col text-left">
                            <span class="text-sm font-black text-slate-900">#{{ data.hari_ke }}</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase leading-none tracking-tighter">{{ data.tanggal }}</span>
                        </div>
                    </template>
                </Column>

                <Column header="Imam Utama & Cadangan" class="px-6 py-4">
                    <template #body="{ data }">
                        <div class="flex flex-col gap-1 text-left">
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full" :class="data.imam_id ? 'bg-emerald-500 animate-pulse' : 'bg-slate-200'"></div>
                                <span class="text-[10px] font-bold text-slate-700 uppercase tracking-tight">{{ data.nama_imam }}</span>
                            </div>
                            <div class="flex items-center gap-2 opacity-50 pl-3.5 italic">
                                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-tighter">Cad: {{ data.nama_cadangan }}</span>
                            </div>
                        </div>
                    </template>
                </Column>

                <Column header="Subuh & Bilal" class="px-6 py-4">
                    <template #body="{ data }">
                        <div class="flex flex-col text-left">
                            <div class="flex items-center gap-1 text-blue-600 font-bold">
                                <CheckCircle2 class="w-3 h-3" />
                                <span class="text-[10px] uppercase italic tracking-tight">{{ data.nama_subuh }}</span>
                            </div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase ml-4">Bilal: {{ data.bilal || '-' }}</span>
                        </div>
                    </template>
                </Column>

                <Column header="Kultum" class="px-6 py-4">
                    <template #body="{ data }">
                        <div class="flex flex-col text-left">
                            <div class="flex items-center gap-1.5 text-slate-700 font-bold">
                                <Mic2 class="w-3 h-3 text-slate-300" />
                                <span class="text-[10px] uppercase tracking-tight leading-none">{{ data.penceramah || '-' }}</span>
                            </div>
                            <span class="text-[9px] text-slate-400 italic line-clamp-1 truncate max-w-[180px] pl-4.5 mt-0.5">"{{ data.tema_ceramah || 'Tema belum diinput' }}"</span>
                        </div>
                    </template>
                </Column>

                <Column header="Aksi" bodyStyle="text-align: center" class="px-6 py-4">
                    <template #body="slotProps">
                        <div class="flex justify-center items-center">
                            <Button @click="openModal(slotProps.data)" severity="secondary" text rounded class="!w-8 !h-8 !p-0 !inline-flex !items-center !justify-center !text-slate-300 hover:!text-slate-900 hover:!bg-slate-50 !transition-all">
                                <Pencil class="w-4 h-4" />
                            </Button>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <Dialog v-model:visible="isModalOpen" modal :style="{ width: '400px' }" class="custom-dialog-compact">
            <template #header>
                <div class="text-left font-sans px-2 pt-2">
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter italic">Update Plotting</h3>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Malam Ramadhan ke-{{ form.id }}</p>
                </div>
            </template>
            <form @submit.prevent="submit" class="space-y-4 pt-2 text-left font-sans px-2">
                <div class="flex flex-col gap-1.5">
                    <label class="text-[9px] font-bold uppercase tracking-widest text-slate-400 ml-1">Imam Utama</label>
                    <Select v-model="form.imam_id" :options="masterImam" optionLabel="nama" optionValue="id" placeholder="Pilih Imam" class="!w-full !rounded-xl !bg-slate-50 !border-none !text-xs !font-bold h-11 flex items-center shadow-inner" filter />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-bold uppercase tracking-widest text-slate-400 ml-1">Cadangan</label>
                        <Select v-model="form.imam_cadangan_id" :options="masterImam" optionLabel="nama" optionValue="id" placeholder="Pilih" class="!rounded-xl !bg-slate-50 !border-none !text-xs !font-bold h-11 flex items-center shadow-inner" filter />
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[9px] font-bold uppercase tracking-widest text-slate-400 ml-1">Imam Subuh</label>
                        <Select v-model="form.imam_subuh_id" :options="masterImam" optionLabel="nama" optionValue="id" placeholder="Pilih" class="!rounded-xl !bg-slate-50 !border-none !text-xs !font-bold h-11 flex items-center shadow-inner" filter />
                    </div>
                </div>
                <div class="h-px bg-slate-100 my-1"></div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[9px] font-bold uppercase tracking-widest text-slate-400 ml-1">Bilal</label>
                    <InputText v-model="form.bilal" class="!w-full !h-11 !px-4 !rounded-xl !bg-slate-50 !border-none !font-bold !text-xs uppercase" />
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[9px] font-bold uppercase tracking-widest text-slate-400 ml-1">Penceramah</label>
                    <InputText v-model="form.penceramah" class="!w-full !h-11 !px-4 !rounded-xl !bg-slate-50 !border-none !font-bold !text-xs uppercase" />
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[9px] font-bold uppercase tracking-widest text-slate-400 ml-1">Tema Kultum</label>
                    <InputText v-model="form.tema_ceramah" class="!w-full !h-11 !px-4 !rounded-xl !bg-slate-50 !border-none !font-bold !text-xs" />
                </div>
                <Button type="submit" :disabled="form.processing" class="!w-full !h-11 !bg-emerald-600 hover:!bg-emerald-700 !text-white !rounded-xl !border-none !shadow-lg !shadow-emerald-100 !mt-2">
                    <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin text-white" />
                    <span class="font-black uppercase tracking-widest text-[10px] text-white italic">Confirm Update</span>
                </Button>
            </form>
        </Dialog>
    </AdminLayout>
</template>

<style scoped>
:deep(.p-datatable-thead > tr > th) { background: #f8fafc; color: #94a3b8; font-size: 9px; text-transform: uppercase; letter-spacing: 0.15em; padding: 0.85rem 1.5rem; border: none; }
:deep(.p-datatable-tbody > tr > td) { padding: 0.75rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
:deep(.p-datatable-tbody > tr:hover) { background: #f8fafc !important; }
:deep(.p-dialog) { border-radius: 24px; border: none; padding: 1.5rem; }
:deep(.p-dialog-header), :deep(.p-dialog-content) { background: #ffffff !important; padding: 0.2rem 0.5rem !important; }
:deep(.p-select-label) { font-size: 0.75rem !important; font-weight: 700 !important; padding: 0.75rem 1rem !important; }
</style>