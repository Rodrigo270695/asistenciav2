<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted } from "vue";
import Pagination from "@/Components/Pagination.vue";
import TextSearch from "@/Components/TextSearch.vue";
import ReasonForm from "./ReasonForm.vue";
import IndexHeader from "@/Components/IndexHeader.vue";
import IconButton from "@/Components/IconButton.vue";
import StatusButton from "@/Components/StatusButton.vue";
import Modal from "@/Components/Modal.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    reasons: Object,
    texto: String,
});

const form = useForm({
    reason: Object,
});

let reasonObj = ref(null);
let showModal = ref(false);
let openMenuId = ref(null);
let query = ref(props.texto);

const toggleOptions = (reasonId) => {
    if (openMenuId.value === reasonId) {
        openMenuId.value = null;
    } else {
        openMenuId.value = reasonId;
    }
};

const addReason = () => {
    reasonObj.value = null;
    showModal.value = true;
};

const editReason = (reason) => {
    openMenuId.value = null;
    reasonObj.value = reason;
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
    reasonObj.value = null;
};

const deleteReason = (reason) => {
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
            form.delete(route("reason.destroy", reason), {
                preserveScroll: true,
            });
        }
    });
};

const changeStatus = (reason) => {
    openMenuId.value = null;
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿Quieres cambiar el estado de la Razón?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cambiar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.put(route("reason.change", reason), {
                preserveScroll: true,
            });
        }
    });
};

const search = () => {
    form.get(route("reason.search", { texto: query.value }));
};

const goToIndex = () => {
    form.get(route("reason.index"));
};
</script>

<template>
    <AppLayout title="Razones">
        <template #header>
            <IndexHeader title="Gestionar Razones" @reload="goToIndex" />
        </template>
        <div class="pt-5">
            <div class="">
                <div class="bg-3D-50 overflow-hidden shadow-abajo-2 rounded-lg">
                    <TextSearch
                        :query="query"
                        :search="search"
                        :add="addReason"
                        @update:query="query = $event"
                        placeholder="Buscar Razón"
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
                                                Nombre
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
                                            v-for="reason in reasons.data"
                                            :key="reason.id"
                                            class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2"
                                        >
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap"
                                            >
                                                {{ reason.name }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base px-2 py-3 whitespace-nowrap text-center"
                                                :class="
                                                    reason.status === 1
                                                        ? 'text-green-500'
                                                        : 'text-red-300'
                                                "
                                            >
                                                {{
                                                    reason.status === 1
                                                        ? "Activo"
                                                        : "Inactivo"
                                                }}
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
                                                        tooltipText="Editar Razón"
                                                        @click="editReason(reason)"
                                                    />
                                                    <StatusButton
                                                        :status="reason.status"
                                                        tooltipText="Cambiar Estado"
                                                        @click="changeStatus(reason)"
                                                    />
                                                    <IconButton
                                                        class="bg-red-300 hover:bg-red-400"
                                                        iconName="bi-trash"
                                                        tooltipText="Eliminar Razón"
                                                        @click="deleteReason(reason)"
                                                    />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="reasons.data.length <= 0">
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
                                v-for="reason in reasons.data"
                                :key="reason.id"
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
                                            {{ reason.name }}
                                        </span>
                                    </h3>
                                </div>
                                <!-- Detalles de la tarjeta -->
                                <div class="text-md text-slate-700">
                                    <p>
                                        <strong>Estado:</strong>
                                        <span
                                            class="ml-1"
                                            :class="
                                                reason.status === 1
                                                    ? 'text-green-500'
                                                    : 'text-red-300'
                                            "
                                        >
                                            {{
                                                reason.status === 1
                                                    ? "Activo"
                                                    : "Inactivo"
                                            }}
                                        </span>
                                    </p>
                                </div>
                                <!-- Menú de tres puntos -->
                                <div class="absolute top-0 right-0 p-2 z-10">
                                    <button
                                        @click="toggleOptions(reason.id)"
                                        class="text-gray-600 hover:text-gray-900 shadow-md shadow-sky-100"
                                    >
                                        <v-icon name="oi-apps" />
                                    </button>
                                    <div
                                        v-if="openMenuId === reason.id"
                                        class="bg-white flex justify-between shadow-abajo-1 rounded-lg absolute right-0 mt-1 w-[130px] z-20 text-center"
                                    >
                                        <a
                                            href="#"
                                            @click="editReason(reason)"
                                            class="block px-3 py-1 text-sm text-slate-500 bg-yellow-200 hover:bg-yellow-300 rounded-l-lg"
                                        >
                                            <v-icon
                                                name="md-modeedit-round"
                                                class="text-slate-500"
                                            />
                                        </a>
                                        <a
                                            href="#"
                                            @click="changeStatus(reason)"
                                            class="block px-3 py-1 text-sm text-slate-500"
                                            :class="{
                                                'bg-orange-200 hover:bg-orange-300':
                                                    reason.status == 1,
                                                'bg-green-200 hover:bg-green-300':
                                                    reason.status == 0,
                                            }"
                                        >
                                            <v-icon
                                                v-if="reason.status == 1"
                                                name="gi-cancel"
                                            />
                                            <v-icon v-else name="fa-check" />
                                        </a>
                                        <a
                                            href="#"
                                            @click="deleteReason(reason)"
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
                        <Pagination class="mt-2" :pagination="reasons" />
                    </div>
                    <Modal :show="showModal" maxWidth="xl">
                        <ReasonForm
                            :reason="reasonObj"
                            @close-modal="closeModal"
                        />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
