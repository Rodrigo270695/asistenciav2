<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted } from "vue";
import Pagination from "@/Components/Pagination.vue";
import TextSearch from "@/Components/TextSearch.vue";
import HoraryForm from "./HoraryForm.vue";
import IndexHeader from "@/Components/IndexHeader.vue";
import IconButton from "@/Components/IconButton.vue";
import ButtonDownload from "@/Components/ButtonDownload.vue";
import Modal from "@/Components/Modal.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    horaries: Object,
    texto: String,
});

const form = useForm({
    horary: Object,
});

let horaryObj = ref(null);
let showModal = ref(false);
let openMenuId = ref(null);
let query = ref(props.texto);

const toggleOptions = (horaryId) => {
    if (openMenuId.value === horaryId) {
        openMenuId.value = null;
    } else {
        openMenuId.value = horaryId;
    }
};

const addHorary = () => {
    horaryObj.value = null;
    showModal.value = true;
};

const editHorary = (horary) => {
    openMenuId.value = null;
    horaryObj.value = horary;
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
    horaryObj.value = null;
};

const deleteHorary = (horary) => {
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
            form.delete(route("horary.destroy", horary), {
                preserveScroll: true,
            });
        }
    });
};

const search = () => {
    form.get(route("horary.search", { texto: query.value }));
};

const goToIndex = () => {
    form.get(route("horary.index"));
};

const goToDetailHorary = (horary) => {
    form.get(route("detailhorary.index", horary));
};

const exportHoraries = () => {
    window.location.href = route("horary.export", { texto: query.value });
};
</script>

<template>
    <AppLayout title="Horarios">
        <template #header>
            <IndexHeader title="Gestionar Horarios" @reload="goToIndex" />
        </template>
        <div class="pt-5">
            <div class="">
                <div class="bg-3D-50 overflow-hidden shadow-abajo-2 rounded-lg">
                    <TextSearch
                        :query="query"
                        :search="search"
                        :add="addHorary"
                        @update:query="query = $event"
                        placeholder="Buscar Horario"
                    />

                    <div class="flex items-center justify-end mx-2">
                        <ButtonDownload
                            class="hover:bg-green-200"
                            iconName="vi-file-type-excel"
                            @click="exportHoraries"
                        />
                    </div>

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
                                                Nombre
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Descripción
                                            </th>
                                            <th
                                                scope="col"
                                                class="border-l"
                                            ></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-3D-50 divide-gray-200">
                                        <tr
                                            v-for="horary in horaries.data"
                                            :key="horary.id"
                                            class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2"
                                        >
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap"
                                            >
                                                {{ horary.name }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ horary.description }}
                                            </td>
                                            <td
                                                class="px-2 py-3 whitespace-nowrap text-right text-sm font-medium"
                                            >
                                                <div
                                                    class="flex items-center justify-center gap-x-3"
                                                >
                                                    <IconButton
                                                        class="bg-green-200 hover:bg-green-300"
                                                        iconName="co-clockify"
                                                        tooltipText="Ver Horario"
                                                        @click="goToDetailHorary(horary)"
                                                    />
                                                    <IconButton
                                                        class="bg-yellow-200 hover:bg-yellow-300"
                                                        iconName="md-modeedit-round"
                                                        tooltipText="Editar Horario"
                                                        @click="editHorary(horary)"
                                                    />
                                                    <IconButton
                                                        class="bg-red-300 hover:bg-red-400"
                                                        iconName="bi-trash"
                                                        tooltipText="Eliminar Horario"
                                                        @click="deleteHorary(horary)"
                                                    />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="horaries.data.length <= 0">
                                            <td
                                                class="text-center font-bold text-slate-500 text-md sm:text-lg bg-3D-50 shadow-abajo-2"
                                                colspan="3"
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
                                v-for="horary in horaries.data"
                                :key="horary.id"
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
                                            {{ horary.name }}
                                        </span>
                                    </h3>
                                </div>
                                <!-- Detalles de la tarjeta -->
                                <div class="text-md text-slate-700">
                                    <p>
                                        <strong>Descripción:</strong>
                                        <span class="ml-1">
                                            {{ horary.description }}
                                        </span>
                                    </p>
                                </div>
                                <!-- Menú de tres puntos -->
                                <div class="absolute top-0 right-0 p-2 z-10">
                                    <button
                                        @click="toggleOptions(horary.id)"
                                        class="text-gray-600 hover:text-gray-900 shadow-md shadow-sky-100"
                                    >
                                        <v-icon name="oi-apps" />
                                    </button>
                                    <div
                                        v-if="openMenuId === horary.id"
                                        class="bg-white flex justify-between shadow-abajo-1 rounded-lg absolute right-0 mt-1 w-[130px] z-20 text-center"
                                    >
                                        <a
                                            href="#"
                                            @click="editHorary(horary)"
                                            class="block px-3 py-1 text-sm text-slate-500 bg-yellow-200 hover:bg-yellow-300 rounded-l-lg"
                                        >
                                            <v-icon
                                                name="md-modeedit-round"
                                                class="text-slate-500"
                                            />
                                        </a>
                                        <a
                                            href="#"
                                            @click="goToDetailHorary(horary)"
                                            class="block px-3 py-1 text-sm text-slate-500 bg-green-200 hover:bg-green-300 "
                                        >
                                            <v-icon
                                                name="co-clockify"
                                                class="text-slate-500"
                                            />
                                        </a>
                                        <a
                                            href="#"
                                            @click="deleteHorary(horary)"
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
                        <Pagination class="mt-2" :pagination="horaries" />
                    </div>
                    <Modal :show="showModal" maxWidth="xl">
                        <HoraryForm
                            :horary="horaryObj"
                            @close-modal="closeModal"
                        />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
