<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted } from "vue";
import Pagination from "@/Components/Pagination.vue";
import TextSearch from "@/Components/TextSearch.vue";
import DetailhoraryForm from "./DetailhoraryForm.vue";
import IndexHeader from "@/Components/IndexHeader.vue";
import IconButton from "@/Components/IconButton.vue";
import Modal from "@/Components/Modal.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";
import moment from 'moment';

const props = defineProps({
    detailhoraries: Object,
    horary: Object,
    texto: String,
});

const form = useForm({
    detailhorary: Object,
});

let detailhoraryObj = ref(null);
let showModal = ref(false);
let openMenuId = ref(null);
let query = ref(props.texto);

const toggleOptions = (detailhoraryId) => {
    if (openMenuId.value === detailhoraryId) {
        openMenuId.value = null;
    } else {
        openMenuId.value = detailhoraryId;
    }
};

const addDetailhorary = () => {
    detailhoraryObj.value = null;
    showModal.value = true;
};

const editDetailhorary = (detailhorary) => {
    openMenuId.value = null;
    detailhoraryObj.value = detailhorary;
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
    showModal.value = false;
    detailhoraryObj.value = null;
};

const deleteDetailhorary = (detailhorary) => {
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
            form.delete(route("detailhorary.destroy", detailhorary), {
                preserveScroll: true,
            });
        }
    });
};

const goToIndex = () => {
    form.get(route("detailhorary.index"));
};

const goBack = () => {
    window.history.back();
};
</script>

<template>
    <AppLayout title="Detalles de Horarios">
        <template #header>
            <IndexHeader title="Gestionar Detalles de Horarios" @reload="goToIndex" @goBack="goBack" :showBackButton="true" />
        </template>
        <div class="pt-5">
            <div class="">
                <div class="bg-3D-50 overflow-hidden shadow-abajo-2 rounded-lg">
                    <TextSearch
                        :query="query"
                        :add="addDetailhorary"
                        @update:query="query = $event"
                        placeholder="Buscar Día"
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
                                                Semana
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Hora Inicio
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Receso Salida
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Receso Ingreso
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Hora Salida
                                            </th>
                                            <th
                                                scope="col"
                                                class="border-l"
                                            ></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-3D-50 divide-gray-200">
                                        <tr
                                            v-for="detailhorary in detailhoraries"
                                            :key="detailhorary.id"
                                            class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2"
                                        >
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap"
                                            >
                                                {{ detailhorary.week }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ detailhorary.hi ? moment(detailhorary.hi, 'HH:mm:ss').format('hh:mm A') : 'Sin Horario' }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ detailhorary.rs ? moment(detailhorary.rs, 'HH:mm:ss').format('hh:mm A') : 'Sin Horario' }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ detailhorary.ri ? moment(detailhorary.ri, 'HH:mm:ss').format('hh:mm A') : 'Sin Horario' }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ detailhorary.hs ? moment(detailhorary.hs, 'HH:mm:ss').format('hh:mm A') : 'Sin Horario' }}
                                            </td>
                                            <td
                                                class="px-2 py-3 whitespace-nowrap text-right text-sm font-medium"
                                            >
                                                <div
                                                    class="flex items-center justify-center gap-x-3"
                                                >
                                                    <IconButton
                                                        class="bg-yellow-200 hover:bg-yellow-300"
                                                        iconName="md-modeedit-round"
                                                        tooltipText="Editar Detalle"
                                                        @click="editDetailhorary(detailhorary)"
                                                    />
                                                    <IconButton
                                                        class="bg-red-300 hover:bg-red-400"
                                                        iconName="bi-trash"
                                                        tooltipText="Eliminar Detalle"
                                                        @click="deleteDetailhorary(detailhorary)"
                                                    />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="detailhoraries.length <= 0">
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
                                v-for="detailhorary in detailhoraries.data"
                                :key="detailhorary.id"
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
                                    <h3
                                        class="text-lg font-bold text-slate-700"
                                    >
                                        Semana:
                                        <span class="font-normal">
                                            {{ detailhorary.week }}
                                        </span>
                                    </h3>
                                </div>
                                <!-- Detalles de la tarjeta -->
                                <div class="text-md text-slate-700">
                                    <p>
                                        <strong>Hora Inicio:</strong>
                                        <span class="ml-1">
                                            {{ detailhorary.hi ? moment(detailhorary.hi, 'HH:mm:ss').format('hh:mm A') : 'Sin Horario' }}
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Receso Salida:</strong>
                                        <span class="ml-1">
                                            {{ detailhorary.rs ? moment(detailhorary.rs, 'HH:mm:ss').format('hh:mm A') : 'Sin Horario' }}
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Receso Ingreso:</strong>
                                        <span class="ml-1">
                                            {{ detailhorary.ri ? moment(detailhorary.ri, 'HH:mm:ss').format('hh:mm A') : 'Sin Horario' }}
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Hora Salida:</strong>
                                        <span class="ml-1">
                                            {{ detailhorary.hs ? moment(detailhorary.hs, 'HH:mm:ss').format('hh:mm A') : 'Sin Horario' }}
                                        </span>
                                    </p>
                                </div>
                                <!-- Menú de tres puntos -->
                                <div class="absolute top-0 right-0 p-2 z-10">
                                    <button
                                        @click="toggleOptions(detailhorary.id)"
                                        class="text-gray-600 hover:text-gray-900 shadow-md shadow-sky-100"
                                    >
                                        <v-icon name="oi-apps" />
                                    </button>
                                    <div
                                        v-if="openMenuId === detailhorary.id"
                                        class="bg-white flex justify-between shadow-abajo-1 rounded-lg absolute right-0 mt-1 w-[130px] z-20 text-center"
                                    >
                                        <a
                                            href="#"
                                            @click="editDetailhorary(detailhorary)"
                                            class="block px-3 py-1 text-sm text-slate-500 bg-yellow-200 hover:bg-yellow-300 rounded-l-lg"
                                        >
                                            <v-icon
                                                name="md-modeedit-round"
                                                class="text-slate-500"
                                            />
                                        </a>
                                        <a
                                            href="#"
                                            @click="deleteDetailhorary(detailhorary)"
                                            class="block px-3 py-1 text-sm text-slate-500 bg-red-300 hover:bg-red-400 rounded-r-lg"
                                        >
                                            <v-icon
                                                name="bi-trash"
                                                class="text-slate-500"
                                            />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Modal :show="showModal" maxWidth="xl">
                        <DetailhoraryForm
                            :detailhorary="detailhoraryObj"
                            :horary="horary"
                            @close-modal="closeModal"
                        />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
