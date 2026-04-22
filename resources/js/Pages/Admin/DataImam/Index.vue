<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

// --- IMPORT IKON ---
import {
    Plus,
    Users,
    ShieldCheck,
    Trash2,
    Loader2,
    Phone,
    MapPin,
    Search,
    Pencil,
    AlertCircle,
    LucideSearch
} from "lucide-vue-next";

// --- PRIME VUE COMPONENTS ---
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import Toast from "primevue/toast";
import ConfirmDialog from "primevue/confirmdialog";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";

const props = defineProps({
    imams: Array,
});

const toast = useToast();
const confirm = useConfirm();
const isModalOpen = ref(false);
const isEdit = ref(false);
const filters = ref({
    global: { value: null, matchMode: "contains" },
});

const form = useForm({
    id: null,
    nama: "",
    no_hp: "",
    alamat: "",
});

const openModal = (data = null) => {
    if (data) {
        isEdit.value = true;
        form.id = data.id;
        form.nama = data.nama;
        form.no_hp = data.no_hp;
        form.alamat = data.alamat;
    } else {
        isEdit.value = false;
        form.reset();
    }
    isModalOpen.value = true;
};

const submit = () => {
    const url = isEdit.value ? `/admin/data-imam/${form.id}` : "/admin/data-imam";
    const method = isEdit.value ? "patch" : "post";

    form[method](url, {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
            toast.add({
                severity: "success",
                summary: "Berhasil",
                detail: "Data personel telah diperbarui",
                life: 3000,
            });
        },
    });
};

const deleteImam = (id) => {
    confirm.require({
        header: "Konfirmasi Hapus",
        message: "Apakah Anda yakin ingin menghapus data personel ini? Tindakan ini tidak dapat dibatalkan.",
        icon: "pi pi-exclamation-triangle",
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true,
            class: "rounded-lg text-sm font-medium border-slate-200 px-4"
        },
        acceptProps: {
            label: 'Hapus Data',
            severity: 'danger',
            class: "rounded-lg bg-rose-600 border-none text-sm font-medium text-white px-4 shadow-sm"
        },
        accept: () => {
            form.delete(`/admin/data-imam/${id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    toast.add({ severity: 'info', summary: 'Dihapus', detail: 'Data berhasil dihapus', life: 3000 });
                },
            });
        }
    });
};
</script>

<template>
    <Head title="Data Imam Masjid" />

    <AdminLayout>
        <Toast />
        <ConfirmDialog class="clean-confirm" />

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 px-2">
            <div class="space-y-1 text-left">
                <div class="flex items-center gap-2 text-emerald-600 font-semibold text-xs tracking-wide mb-1">
                    <ShieldCheck class="w-4 h-4" />
                    Manajemen SDM
                </div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                    Data Imam
                </h2>
                <p class="text-slate-500 text-sm font-normal">
                    Kelola informasi ustadz dan imam masjid secara terpusat.
                </p>
            </div>

            <Button
                @click="openModal()"
                class="rounded-xl bg-slate-900 hover:bg-emerald-700 text-white shadow-md h-11 px-6 border-none transition-all"
            >
                <Plus class="w-4 h-4 mr-2 text-white" />
                <span class="font-semibold text-sm text-white">Tambah Imam</span>
            </Button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5 transition-all hover:shadow-md">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <Users class="w-6 h-6" />
                </div>
                <div class="space-y-0.5 text-left">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Imam</p>
                    <h3 class="text-xl font-bold text-slate-800">{{ imams.length }} Orang</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden mb-12">
            <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider text-left">List Personel</h3>
                <IconField iconPosition="left">
                    <InputText
                    v-model="filters['global'].value"
                    placeholder="Cari nama ustadz..."
                    class="rounded-lg text-sm h-10 w-64 border-slate-200 bg-slate-50/50"
                    />
                </IconField>
            </div>

            <DataTable
                :value="imams"
                v-model:filters="filters"
                paginator :rows="10"
                class="p-datatable-md p-datatable-hoverable px-2"
                dataKey="id"
                :globalFilterFields="['nama']"
            >
                <Column field="nama" header="Nama Lengkap" sortable class="px-6 py-4">
                    <template #body="slotProps">
                        <span class="font-semibold text-slate-700 text-sm">
                            {{ slotProps.data.nama }}
                        </span>
                    </template>
                </Column>

                <Column field="no_hp" header="Kontak" class="px-6 py-4 text-left">
                    <template #body="slotProps">
                        <div class="flex items-center gap-2 text-sm text-slate-600 font-medium">
                            <Phone class="w-4 h-4 text-slate-400" />
                            {{ slotProps.data.no_hp || "-" }}
                        </div>
                    </template>
                </Column>

                <Column field="alamat" header="Alamat" class="px-6 py-4 text-left">
                    <template #body="slotProps">
                        <div class="flex items-center gap-2 text-sm text-slate-500 font-normal">
                            <MapPin class="w-4 h-4 text-slate-300" />
                            <span class="truncate max-w-[250px]">{{ slotProps.data.alamat || "-" }}</span>
                        </div>
                    </template>
                </Column>

                <Column header="Aksi" bodyStyle="text-align: center" class="px-6 py-4">
                    <template #body="slotProps">
                        <div class="flex justify-center items-center gap-3">
                            <button @click="openModal(slotProps.data)" class="text-slate-400 hover:text-emerald-600 transition-colors">
                                <Pencil class="w-4 h-4" />
                            </button>
                            <button @click="deleteImam(slotProps.data.id)" class="text-slate-400 hover:text-rose-600 transition-colors">
                                <Trash2 class="w-4 h-4" />
                            </button>
                        </div>
                    </template>
                </Column>

                <template #empty>
                    <div class="py-12 text-center text-slate-400 text-sm">
                        Data personel tidak ditemukan
                    </div>
                </template>
            </DataTable>
        </div>

        <Dialog
            v-model:visible="isModalOpen"
            modal
            header="Informasi Imam"
            :style="{ width: '400px' }"
            class="clean-dialog"
        >
            <form @submit.prevent="submit" class="space-y-5 pt-4 text-left">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-600 ml-1">Nama Lengkap</label>
                    <InputText
                        v-model="form.nama"
                        placeholder="Nama ustadz..."
                        class="w-full rounded-lg h-11 px-4 border-slate-200 focus:border-emerald-500 text-sm"
                        required
                    />
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-600 ml-1">No. WhatsApp</label>
                    <InputText
                        v-model="form.no_hp"
                        placeholder="08..."
                        class="w-full rounded-lg h-11 px-4 border-slate-200 focus:border-emerald-500 text-sm"
                    />
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-600 ml-1">Alamat</label>
                    <InputText
                        v-model="form.alamat"
                        placeholder="Alamat domisili..."
                        class="w-full rounded-lg h-11 px-4 border-slate-200 focus:border-emerald-500 text-sm"
                    />
                </div>
                <Button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full h-11 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg border-none mt-4 transition-all shadow-sm shadow-emerald-100"
                >
                    <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin text-white" />
                    <span class="font-bold text-sm text-white">Simpan Perubahan</span>
                </Button>
            </form>
        </Dialog>
    </AdminLayout>
</template>

<style scoped>
/* TABLE STYLE CLEAN */
:deep(.p-datatable-thead > tr > th) {
    background: #fafafa;
    color: #64748b;
    font-size: 11px;
    text-transform: uppercase;
    font-weight: 700;
    padding: 1rem 1.5rem;
    border: none;
}
:deep(.p-datatable-tbody > tr > td) {
    border-bottom: 1px solid #f1f5f9;
}

/* DIALOG STYLE CLEAN */
:deep(.p-dialog) {
    border-radius: 16px;
    border: none;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}
:deep(.p-dialog-header) {
    border-bottom: 1px solid #f8fafc;
    padding: 1.25rem 1.5rem;
}
:deep(.p-dialog-title) {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
}

/* CONFIRM DIALOG CLEAN */
:deep(.p-dialog.clean-confirm .p-dialog-header) {
    display: none;
}
:deep(.p-dialog.clean-confirm .p-confirm-dialog-message) {
    font-size: 0.875rem;
    color: #475569;
    line-height: 1.5;
    margin-top: 1rem;
}
:deep(.p-dialog.clean-confirm .p-confirm-dialog-icon) {
    font-size: 1.25rem;
    color: #f43f5e;
}
:deep(.p-dialog.clean-confirm .p-dialog-footer) {
    border-top: none;
    padding: 1rem 1.5rem 1.5rem 1.5rem;
}

main { animation: fadeIn 0.4s ease-out; }
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.98); }
    to { opacity: 1; transform: scale(1); }
}
</style>