<template>
    <TitleForm title="Detalles de la Ausencia" @close-modal="emit('close-modal')" />
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div v-if="absence" class="mt-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div v-if="$page.props.user.roles.includes('administrador') || $page.props.user.roles.includes('visualizador')" class="border-b pb-4">
                    <InputLabel value="Zonal - PDV" />
                    <p class="text-gray-800 font-semibold text-lg">{{ absence.worker.pdv.zonal.name }} - {{ absence.worker.pdv.spot }}</p>
                </div>
                <div class="border-b pb-4">
                    <InputLabel value="Trabajador" />
                    <p class="text-gray-800 font-semibold text-lg">{{ absence.worker.lastname }} {{ absence.worker.name }}</p>
                </div>
                <div class="border-b pb-4">
                    <InputLabel value="Razón" />
                    <p class="text-gray-800 font-semibold text-lg">{{ absence.reason.name }}</p>
                </div>
                <div class="border-b pb-4">
                    <InputLabel value="Fecha de Inicio" />
                    <p class="text-gray-800 font-semibold text-lg">{{ absence.start_date }}</p>
                </div>
                <div class="border-b pb-4">
                    <InputLabel value="Fecha de Fin" />
                    <p class="text-gray-800 font-semibold text-lg">{{ absence.end_date || 'N/A' }}</p>
                </div>
                <div class="border-b pb-4">
                    <InputLabel value="Estado" />
                    <p :class="statusClass">{{ absence.status }}</p>
                </div>
                <div class="col-span-1 sm:col-span-2 border-b pb-4">
                    <InputLabel value="Detalle del Estado" />
                    <p class="text-gray-800 font-semibold text-lg whitespace-pre-line">{{ absence.status_detail || 'N/A' }}</p>
                </div>
                <div class="col-span-1 sm:col-span-2">
                    <InputLabel value="Archivo" />
                    <FileDownload v-if="absence.file" :fileName="absence.file" :downloadUrl="`/absences/download/${removeAbsencePath(absence.file)}`" />
                    <p v-else class="text-gray-500">No disponible</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TitleForm from "@/Components/TitleForm.vue";
import FileDownload from "@/Components/FileDownload.vue";
import { defineProps, computed, defineEmits } from 'vue';

const props = defineProps({
    absence: Object
});

const emit = defineEmits(["close-modal"]);

const removeAbsencePath = (path) => {
    return path.replace('absences/', '');
};

const statusClass = computed(() => {
    switch (props.absence.status) {
        case 'Aprobado':
            return 'text-green-600 font-bold text-lg';
        case 'Rechazado':
            return 'text-red-600 font-bold text-lg';
        case 'Observado':
            return 'text-yellow-600 font-bold text-lg';
        default:
            return 'text-gray-800 font-semibold text-lg';
    }
});
</script>

<style scoped>
p {
    margin-top: 0.5rem;
    line-height: 1.5;
}
</style>
