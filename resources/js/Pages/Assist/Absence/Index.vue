<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted } from "vue";
import Pagination from "@/Components/Pagination.vue";
import TextSearch from "@/Components/TextSearch.vue";
import AbsenceForm from "./AbsenceForm.vue";
import IndexHeader from "@/Components/IndexHeader.vue";
import IconButton from "@/Components/IconButton.vue"
import Modal from "@/Components/Modal.vue";
import AbsenceDetails from "./AbsenceDetails.vue";
import FileDownload from "@/Components/FileDownload.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";
import moment from "moment";

const props = defineProps({
    absences: Object,
    workers: Array,
    reasons: Array,
    texto: String,
});

const form = useForm({
    absence: Object,
});

let absenceObj = ref(null);
let showModal = ref(false);
let showModalDetails = ref(false);
let openMenuId = ref(null);
let query = ref(props.texto);

const toggleOptions = (absenceId) => {
    if (openMenuId.value === absenceId) {
        openMenuId.value = null;
    } else {
        openMenuId.value = absenceId;
    }
};

const addAbsence = () => {
    absenceObj.value = null;
    showModal.value = true;
};

const viewAbsence = (absence) => {
    showModalDetails.value = true;
    absenceObj.value = absence;
};

const editAbsence = (absence) => {
    openMenuId.value = null;
    absenceObj.value = absence;
    showModal.value = true;
};

const onKeydown = (e) => {
    if (e.key === "Escape") {
        closeModal();
    }
};

onMounted(() => {
    window.addEventListener("keydown", onKeydown);
});

onUnmounted(() => {
    window.removeEventListener("keydown", onKeydown);
});

const closeModal = () => {
    showModalDetails.value = false;
    showModal.value = false;
    absenceObj.value = null;
};

const deleteAbsence = (absence) => {
    openMenuId.value = null;
    Swal.fire({
        title: "¿Estás seguro?",
        text: "No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route("absence.destroy", absence), {
                preserveScroll: true,
            });
        }
    });
};

const search = () => {
    form.get(route("absence.search", { texto: query.value }));
};

const goToIndex = () => {
    form.get(route("absence.index"));
};

const removeAbsencePath = (path) => {
    return path.replace(/absences\//, '');
};

</script>

<template>
    <AppLayout title="Ausencias">
        <template #header>
            <IndexHeader title="Gestionar Ausencias" @reload="goToIndex" />
        </template>
        <div class="pt-5">
            <div class="">
                <div class="bg-3D-50 overflow-hidden shadow-abajo-2 rounded-lg ">
                    <TextSearch
                        :query="query"
                        :search="search"
                        :add="addAbsence"
                        @update:query="query = $event"
                        placeholder="Buscar Ausencia"
                    />

                    <div class="p-3">
                        <div class="hidden sm:block">
                            <div class="overflow-x-auto rounded-lg">
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead class="bg-blue-200 shadow-abajo-2">
                                        <tr class="">
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-left text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Registro
                                            </th>
                                            <th
                                                v-if="$page.props.user.roles.includes('administrador') || $page.props.user.roles.includes('visualizador')"
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Pdv
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Trabajador
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Motivo
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Fecha
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Estado
                                            </th>
                                            <th
                                                scope="col"
                                                class="border-l"
                                            ></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-3D-50 divide-gray-200">
                                        <tr
                                            v-for="absence in absences.data"
                                            :key="absence.id"
                                            class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2"
                                        >
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap"
                                            >
                                                {{ moment(absence.created_at).format('DD/MM/YYYY') }}
                                            </td>
                                            <td
                                                v-if="$page.props.user.roles.includes('administrador') || $page.props.user.roles.includes('visualizador')"
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ absence.worker.pdv.zonal.name + ' - ' + absence.worker.pdv.spot }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                <div class="flex justify-center items-center gap-x-2">
                                                    <span>
                                                        {{ absence.worker.lastname + ' ' + absence.worker.name }}
                                                    </span>
                                                    <span>
                                                        <FileDownload v-if="absence.file" :fileName="absence.file" :downloadUrl="`/absences/download/${removeAbsencePath(absence.file)}`" />
                                                        <v-icon v-else name="vi-default-file" scale="1.5" />
                                                    </span>
                                                </div>
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ absence.reason.name }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                <span
                                                    class="text-sm text-blue-500 p-1 whitespace-nowrap text-center bg-blue-100 rounded-md"
                                                    :title="'Fecha de inicio: ' + moment(absence.start_date).format('DD-MM-YYYY') + ', Fecha de fin: ' + moment(absence.end_date).format('DD-MM-YYYY') "
                                                >
                                                    {{ moment(absence.start_date).format('D/MM/YYYY') }} - {{ absence.end_date ? moment(absence.end_date).format('D/MM/YYYY') : '' }}
                                                </span>
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                <div class="text-sm inline-block">
                                                    <div :class="absence.status === 'Aprobado' ? 'bg-green-100 text-green-600' : absence.status === 'Rechazado' ? 'bg-red-100 text-red-600' : absence.status === 'Por aprobar' ? 'bg-gray-300 text-gray-600' : 'bg-yellow-100 text-yellow-600'" class="p-1 rounded-md text-center">
                                                        {{ absence.status }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="px-2 py-3 whitespace-nowrap text-right text-sm font-medium"
                                            >
                                                <div
                                                    class="flex items-center justify-center gap-x-3"
                                                >
                                                    <IconButton
                                                        v-if="absence.status !== 'Por aprobar' || ($page.props.user.roles.includes('administrador') || $page.props.user.roles.includes('visualizador'))"
                                                        class="bg-green-200 hover:bg-green-300"
                                                        iconName="fa-eye"
                                                        tooltipText="Ver Ausencia"
                                                        @click="viewAbsence(absence)"
                                                    />
                                                    <IconButton
                                                        v-if="absence.status === 'Por aprobar' || ($page.props.user.roles.includes('administrador'))"
                                                        class="bg-yellow-200 hover:bg-yellow-300"
                                                        iconName="md-modeedit-round"
                                                        tooltipText="Editar Ausencia"
                                                        @click="editAbsence(absence)"
                                                    />
                                                    <IconButton
                                                        v-if="absence.status === 'Por aprobar' || absence.status === 'Observado' || ($page.props.user.roles.includes('administrador'))"
                                                        class="bg-red-300 hover:bg-red-400"
                                                        iconName="bi-trash"
                                                        tooltipText="Eliminar Ausencia"
                                                        @click="deleteAbsence(absence)"
                                                    />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="absences.data.length <= 0">
                                            <td
                                                class="text-center font-bold text-slate-500 text-md sm:text-lg bg-3D-50 shadow-abajo-2"
                                                colspan="6"
                                            >
                                                No hay registros
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Versión mobile -->
                        <div class="block sm:hidden rounded-lg">
                            <div
                                v-for="absence in absences.data"
                                :key="absence.id"
                                class="p-4 mx-1 mt-4 bg-blue-50 hover:bg-blue-100 rounded-lg relative shadow-abajo-1"
                            >
                                <!-- Contenido de la tarjeta -->
                                <div class="flex items-center space-x-2 mb-4">
                                    <svg
                                        class="h-6 w-6 text-sky-500"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 7h18M3 12h18m-9 5h9"
                                        />
                                    </svg>
                                    <h3 class="text-lg font-bold text-slate-700">
                                        Trabajador:
                                        <span class="font-normal">
                                            {{ absence.worker.lastname + ' ' + absence.worker.name }}
                                        </span>
                                    </h3>
                                </div>
                                <!-- Detalles de la tarjeta -->
                                <div class="text-md text-slate-700">
                                    <p>
                                        <strong>Registro:</strong>
                                        <span class="ml-1">
                                            {{ moment(absence.created_at).format('DD-MM-YYYY') }}
                                        </span>
                                    </p>
                                    <p v-if="$page.props.user.roles.includes('administrador') || $page.props.user.roles.includes('visualizador')">
                                        <strong>Pdv:</strong>
                                        <span class="ml-1">
                                            {{ absence.worker.pdv.zonal.name + ' - ' + absence.worker.pdv.spot }}
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Motivo:</strong>
                                        <span class="ml-1">
                                            {{ absence.reason.name }}
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Fecha:</strong>
                                        <span class="ml-1">
                                            {{ moment(absence.start_date).format('D/MM/YYYY') }} - {{ absence.end_date ? moment(absence.end_date).format('D/MM/YYYY') : 'N/A' }}
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Estado:</strong>
                                        <span
                                            class="ml-1"
                                            :class="
                                                absence.status === 'Aprobado'
                                                    ? 'text-green-500'
                                                    : absence.status === 'Rechazado'
                                                    ? 'text-red-300'
                                                    : 'text-yellow-500'
                                            "
                                        >
                                            {{ absence.status }}
                                        </span>
                                    </p>
                                </div>
                                <!-- Menú de tres puntos -->
                                <div class="absolute top-0 right-0 p-2 z-10">
                                    <button
                                        @click="toggleOptions(absence.id)"
                                        class="text-gray-600 hover:text-gray-900 shadow-md shadow-sky-100"
                                    >
                                        <v-icon name="oi-apps" />
                                    </button>
                                    <div
                                        v-if="openMenuId === absence.id"
                                        class="bg-white flex justify-between shadow-abajo-1 rounded-lg absolute right-0 mt-1 w-[130px] z-20 text-center"
                                    >
                                    <a
                                        v-if="absence.status !== 'Por aprobar' || ($page.props.user.roles.includes('administrador') || $page.props.user.roles.includes('visualizador'))"
                                        href="#"
                                        @click="viewAbsence(absence)"
                                        class="block px-3 py-1 text-sm text-slate-500 bg-green-200 hover:bg-green-300 rounded-l-md"
                                    >
                                        <v-icon name="fa-eye" class="text-slate-500" />
                                    </a>
                                    <a
                                        v-if="absence.status === 'Por aprobar' || ($page.props.user.roles.includes('administrador'))"
                                        href="#"
                                        @click="editAbsence(absence)"
                                        class="block px-3 py-1 text-sm text-slate-500 bg-yellow-200 hover:bg-yellow-300"
                                    >
                                        <v-icon name="md-modeedit-round" class="text-slate-500" />
                                    </a>
                                    <a
                                        v-if="absence.status === 'Por aprobar' || absence.status === 'Observado' || ($page.props.user.roles.includes('administrador'))"
                                        href="#"
                                        @click="deleteAbsence(absence)"
                                        class="block px-3 py-1 text-sm text-slate-500 bg-red-300 hover:bg-red-400 rounded-r-md"
                                    >
                                        <v-icon name="bi-trash" class="text-slate-500" />
                                    </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <Pagination class="mt-2" :pagination="absences" />
                    </div>
                    <Modal :show="showModal" maxWidth="3xl">
                        <AbsenceForm
                            :absence="absenceObj"
                            :workers="workers"
                            :reasons="reasons"
                            @close-modal="closeModal"
                        />
                    </Modal>
                    <Modal :show="showModalDetails" maxWidth="3xl">
                        <AbsenceDetails :absence="absenceObj" @close-modal="closeModal" />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
