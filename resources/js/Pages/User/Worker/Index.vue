<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted } from "vue";
import Pagination from "@/Components/Pagination.vue";
import TextSearch from "@/Components/TextSearch.vue";
import WorkerForm from "./WorkerForm.vue";
import IndexHeader from "@/Components/IndexHeader.vue";
import IconButton from "@/Components/IconButton.vue";
import Modal from "@/Components/Modal.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";
import moment from 'moment';

const props = defineProps({
    workers: Object,
    pdvs: Array,
    charges: Array,
    texto: String,
});

const form = useForm({
    worker: Object,
});

let workerObj = ref(null);
let showModal = ref(false);
let openMenuId = ref(null);
let query = ref(props.texto);

const toggleOptions = (workerId) => {
    if (openMenuId.value === workerId) {
        openMenuId.value = null;
    } else {
        openMenuId.value = workerId;
    }
};

const addWorker = () => {
    workerObj.value = null;
    showModal.value = true;
};

const editWorker = (worker) => {
    openMenuId.value = null;
    workerObj.value = worker;
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
    workerObj.value = null;
};

const deleteWorker = (worker) => {
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
            form.delete(route("worker.destroy", worker), {
                preserveScroll: true,
            });
        }
    });
};

const changeStatus = (worker) => {
    openMenuId.value = null;
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿Quieres cambiar el estado del trabajador?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cambiar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.put(route("worker.change", worker), {
                preserveScroll: true,
            });
        }
    });
};

const search = () => {
    form.get(route("worker.search", { texto: query.value }));
};

const goToIndex = () => {
    form.get(route("worker.index"));
};
</script>

<template>
    <AppLayout title="Trabajadores">
        <template #header>
            <IndexHeader title="Gestionar Trabajadores" @reload="goToIndex" />
        </template>
        <div class="pt-5">
            <div class="">
                <div class="bg-3D-50 overflow-hidden shadow-abajo-2 rounded-lg">
                    <TextSearch
                        :query="query"
                        :search="search"
                        :add="addWorker"
                        @update:query="query = $event"
                        placeholder="Buscar Trabajador"
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
                                                Ingreso/salida
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                PDV
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Nombre Completo
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Documento
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Cargo
                                            </th>
                                            <th
                                                scope="col"
                                                class="border-l"
                                            ></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-3D-50 divide-gray-200">
                                        <tr
                                            v-for="worker in workers.data"
                                            :key="worker.id"
                                            class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2"
                                        >
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                <div   div class="text-sm inline-block">
                                                    <div class="bg-green-100 text-green-600 p-1 rounded-md text-center">
                                                        {{ moment(worker.entry_date).format('DD-MM-YYYY') }}
                                                    </div>
                                                    <div v-if="!worker.exit_date" class="bg-red-100 text-red-600 p-1 rounded-md text-center">
                                                        Sin salida
                                                    </div>
                                                    <div v-else class="bg-red-100 text-red-600 p-1 rounded-md text-center">
                                                        {{ moment(worker.exit_date).format('DD-MM-YYYY') }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ worker.pdv.zonal.name +' - '+worker.pdv.spot }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ worker.lastname+' '+worker.name }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ worker.document_type }}: {{ worker.num_document }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ worker.charge.name }}
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
                                                        tooltipText="Editar Trabajador"
                                                        @click="editWorker(worker)"
                                                    />
                                                    <IconButton
                                                        class="bg-red-300 hover:bg-red-400"
                                                        iconName="bi-trash"
                                                        tooltipText="Eliminar Trabajador"
                                                        @click="deleteWorker(worker)"
                                                    />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="workers.data.length <= 0">
                                            <td
                                                class="text-center font-bold text-slate-500 text-md sm:text-lg bg-3D-50 shadow-abajo-2"
                                                colspan="7"
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
                                v-for="worker in workers.data"
                                :key="worker.id"
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
                                        Nombre:
                                        <span class="font-normal">
                                            {{ worker.name }} {{ worker.lastname }}
                                        </span>
                                    </h3>
                                </div>
                                <!-- Detalles de la tarjeta -->
                                <div class="text-md text-slate-700">
                                    <p>
                                        <strong>Ingreso/salida:</strong>
                                        <span class="text-sm">
                                            <span class="bg-green-100 text-green-600 p-1 rounded-md ">
                                                {{ moment(worker.entry_date).format('DD-MM-YYYY') }}
                                            </span>,
                                            <span v-if="!worker.exit_date" class="bg-red-100 text-red-600 p-1 rounded-md ">
                                                Sin salida
                                            </span>
                                            <span v-else class="bg-red-100 text-red-600 p-1 rounded-md ">
                                                {{ moment(worker.exit_date).format('DD-MM-YYYY') }}
                                            </span>
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Pdv:</strong>
                                        <span class="ml-1">
                                            {{ worker.pdv.zonal.name +' - '+worker.pdv.spot }}
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Documento:</strong>
                                        <span class="ml-1">
                                            {{ worker.document_type }}: {{ worker.num_document }}
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Cargo:</strong>
                                        <span class="ml-1">
                                            {{ worker.charge.name }}
                                        </span>
                                    </p>
                                </div>
                                <!-- Menú de tres puntos -->
                                <div class="absolute top-0 right-0 p-2 z-10">
                                    <button
                                        @click="toggleOptions(worker.id)"
                                        class="text-gray-600 hover:text-gray-900 shadow-md shadow-sky-100"
                                    >
                                        <v-icon name="oi-apps" />
                                    </button>
                                    <div
                                        v-if="openMenuId === worker.id"
                                        class="bg-white flex justify-between shadow-abajo-1 rounded-lg absolute right-0 mt-1 w-[86px] z-20 text-center"
                                    >
                                        <a
                                            href="#"
                                            @click="editWorker(worker)"
                                            class="block px-3 py-1 text-sm text-slate-500 bg-yellow-200 hover:bg-yellow-300 rounded-l-lg"
                                        >
                                            <v-icon
                                                name="md-modeedit-round"
                                                class="text-slate-500"
                                            />
                                        </a>
                                        <a
                                            href="#"
                                            @click="deleteWorker(worker)"
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
                        <Pagination class="mt-2" :pagination="workers" />
                    </div>
                    <Modal :show="showModal" maxWidth="3xl">
                        <WorkerForm
                            :worker="workerObj"
                            :pdvs="pdvs"
                            :charges="charges"
                            @close-modal="closeModal"
                        />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
