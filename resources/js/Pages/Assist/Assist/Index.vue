<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import IndexHeader from "@/Components/IndexHeader.vue";
import SubmitButton from "@/Components/SubmitButton.vue";
import Pagination from "@/Components/Pagination.vue";
import DividerWithText from "@/Components/DividerWithText.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import moment from 'moment';

const props = defineProps({
    worker: Object,
    assists: Object,
    dni: String,
});

const form = useForm({
    id: props.worker ? props.worker.id : "",
    name: props.worker ? props.worker.name + " " + props.worker.lastname : "",
    pdv: props.worker ? props.worker.pdv.spot : "",
    tipoRegistro: "",
});

let fechaActual = ref("");
let horaActual = ref("");
let dniWorker = ref(props.dni);

const getCurrentDateTime = () => {
    const currentDate = moment();
    fechaActual.value = currentDate.format('DD/MM/YYYY');
    horaActual.value = currentDate.format('hh:mm:ss A');
};

getCurrentDateTime();

setInterval(() => {
    getCurrentDateTime();
}, 1000);

const getWorker = () => {
    form.get(route("assist.worker", dniWorker.value), {
        preserveScroll: true,
    });
};

const submit = () => {
    form.post(route("assist.store"), {
        preserveScroll: true,
    });
};

const registros = ref([
    { id: 1, name: "Ingreso" },
    { id: 2, name: "Refrigerio/S" },
    { id: 3, name: "Refrigerio/I" },
    { id: 4, name: "Salida" },
]);

const goToIndex = () => {
    form.get(route("assist.index"));
};
</script>

<template>
    <AppLayout title="Asistencias">
        <template #header>
            <IndexHeader title="Registro de Asistencias" @reload="goToIndex" />
        </template>
        <div class="pt-5">
            <div class="bg-3D-50 overflow-hidden shadow-abajo-2 rounded-lg">
                <div class="m-10 p-2 bg-green-100 rounded-md shadow-abajo-1">
                    <div class="flex flex-col text-slate-500">
                        <p class="text-xl font-semibold">
                            Fecha:
                            <span class="font-normal">{{ fechaActual }}</span>
                        </p>
                        <p class="text-xl font-semibold mt-2">
                            Hora:
                            <span class="font-normal">{{ horaActual }}</span>
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mx-10 mb-5">
                    <div>
                        <InputLabel value="DNI" />
                        <div class="relative">
                            <TextInput
                                class="w-full"
                                v-model="dniWorker"
                                @keyup.enter="getWorker"
                            />
                            <InputError
                                class="w-full"
                                :message="form.errors.id"
                            />
                            <button
                                @click.prevent="getWorker"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-500 bg-blue-100 rounded-e-md hover:bg-blue-200 shadow-abajo-1"
                            >
                                <v-icon name="fa-search" scale="1.5" />
                            </button>
                        </div>
                    </div>
                    <div>
                        <InputLabel value="Nombres" />
                        <TextInput
                            class="w-full"
                            v-model="form.name"
                            readonly
                        />
                    </div>
                    <div>
                        <InputLabel value="Punto de venta" />
                        <TextInput class="w-full" v-model="form.pdv" readonly />
                    </div>
                </div>
                <form @submit.prevent="submit">
                    <div
                        class="grid grid-cols-1 md:grid-cols-3 gap-4 mx-10 mb-5"
                    >
                        <div class="">
                            <InputLabel value="Tipo de registro" />
                            <select
                                v-model="form.tipoRegistro"
                                class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                            >
                                <option disabled selected value="">
                                    Elija una opción
                                </option>
                                <option
                                    v-for="registro in registros"
                                    :key="registro.id"
                                    :value="registro.name"
                                >
                                    {{ registro.name }}
                                </option>
                            </select>
                            <InputError
                                class="w-full"
                                :message="form.errors.tipoRegistro"
                            />
                        </div>
                        <div class="col-span-3 text-center flex">
                            <SubmitButton
                                class="w-full"
                                text="Registrar"
                                :processing="form.processing"
                            >
                            </SubmitButton>
                        </div>
                    </div>
                </form>
                <DividerWithText text="Registros de Asistencia" />
                <div class="p-3">
                    <div class="hidden sm:block">
                        <div class="overflow-x-auto rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-blue-200 shadow-abajo-2">
                                    <tr>
                                        <th
                                            class="px-2 py-2 text-left text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-wider border-l"
                                        >
                                            Punto de Venta
                                        </th>
                                        <th
                                            class="px-2 py-2 text-center text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-wider border-l"
                                        >
                                            Trabajador
                                        </th>
                                        <th
                                            class="px-2 py-2 text-center text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-wider border-l"
                                        >
                                            Ingreso laboral
                                        </th>
                                        <th
                                            class="px-2 py-2 text-center text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-wider border-l"
                                        >
                                            Receso Salida
                                        </th>
                                        <th
                                            class="px-2 py-2 text-center text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-wider border-l"
                                        >
                                            Receso Ingreso
                                        </th>
                                        <th
                                            class="px-2 py-2 text-center text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-wider border-l"
                                        >
                                            Hora Salida
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-3D-50 divide-gray-200">
                                    <tr
                                        v-for="assist in assists.data"
                                        :key="assist.id"
                                        class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2"
                                    >
                                        <td
                                            class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap"
                                        >
                                            {{
                                                assist.worker.pdv.zonal.name +
                                                    " - " +
                                                    assist.worker.pdv.spot
                                            }}
                                        </td>
                                        <td
                                            class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                        >
                                            {{
                                                assist.worker.lastname +
                                                    " " +
                                                    assist.worker.name
                                            }}
                                        </td>
                                        <td
                                            class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                        >
                                            <span
                                                class="text-sm p-1 whitespace-nowrap text-center rounded-md"
                                                :class="assist.status_entry === 1 ? 'bg-green-100 text-green-600' : assist.status_entry === 0 ? 'bg-red-100 text-red-600' : assist.status_entry === 2 ? 'bg-yellow-100 text-yellow-600' : ''"
                                            >
                                                {{ assist.hi ? moment(assist.hi, 'HH:mm:ss').format('hh:mm:ss A') : "" }}
                                            </span>
                                        </td>
                                        <td
                                            class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                        >
                                            <span
                                                class="text-sm p-1 whitespace-nowrap text-center rounded-md"
                                                :class="assist.status_foodout === 1 ? 'bg-green-100 text-green-600' : assist.status_foodout === 0 ? 'bg-red-100 text-red-600' : ''"
                                            >
                                                {{ assist.rs ? moment(assist.rs, 'HH:mm:ss').format('hh:mm:ss A') : "" }}
                                            </span>
                                        </td>
                                        <td
                                            class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                        >
                                            <span
                                                class="text-sm p-1 whitespace-nowrap text-center rounded-md"
                                                :class="assist.status_foodentry === 1 ? 'bg-green-100 text-green-600' : assist.status_foodentry === 0 ? 'bg-red-100 text-red-600' : assist.status_foodentry === 2 ? 'bg-yellow-100 text-yellow-600' : ''"
                                            >
                                                {{ assist.ri ? moment(assist.ri, 'HH:mm:ss').format('hh:mm:ss A') : "" }}
                                            </span>
                                        </td>
                                        <td
                                            class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                        >
                                            <span
                                                class="text-sm p-1 whitespace-nowrap text-center rounded-md"
                                                :class="assist.status_out === 1 ? 'bg-green-100 text-green-600' : assist.status_out === 0 ? 'bg-red-100 text-red-600' : ''"
                                            >
                                                {{ assist.hs ? moment(assist.hs, 'HH:mm:ss').format('hh:mm:ss A') : "" }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="assists.data.length <= 0">
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
                            v-for="assist in assists.data"
                            :key="assist.id"
                            class="p-4 mx-1 mt-4 bg-blue-50 hover:bg-blue-100 rounded-lg relative shadow-abajo-1"
                        >
                            <!-- Contenido de la tarjeta -->
                            <div class="flex items-center space-x-2 mb-4">
                                <h3 class="text-lg font-bold text-slate-700">
                                    Nombre:
                                    <span class="font-normal">
                                        {{ assist.worker.lastname+' '+assist.worker.name }}
                                    </span>
                                </h3>
                            </div>
                            <!-- Detalles de la tarjeta -->
                            <div class="text-md text-slate-700">
                                <p>
                                    <strong>Punto de Venta:</strong>
                                    <span class="ml-1">
                                        {{ assist.worker.pdv.zonal.name+' - '+assist.worker.pdv.spot }}
                                    </span>
                                </p>
                                <p>
                                    <strong>Ingreso laboral:</strong>
                                    <span
                                            class="text-sm p-1 whitespace-nowrap text-center rounded-md ml-1"
                                            :class="assist.status_entry === 1 ? ' text-green-600' : assist.status_entry === 0 ? ' text-red-600' : assist.status_entry === 2 ? ' text-yellow-600' : ''"
                                        >
                                            {{ assist.hi ? moment(assist.hi, 'HH:mm:ss').format('hh:mm:ss A') : "" }}
                                        </span>
                                </p>
                                <p>
                                    <strong>Receso Salida:</strong>
                                    <span
                                        class="text-sm p-1 whitespace-nowrap text-center rounded-md ml-1"
                                        :class="assist.status_foodout === 1 ? 'text-green-600' : assist.status_foodout === 0 ? ' text-red-600' : ''"
                                    >
                                        {{ assist.rs ? moment(assist.rs, 'HH:mm:ss').format('hh:mm:ss A') : "" }}
                                    </span>
                                </p>
                                <p>
                                    <strong>Receso Ingreso:</strong>
                                    <span
                                        class="text-sm p-1 whitespace-nowrap text-center rounded-md ml-1"
                                        :class="assist.status_foodentry === 1 ? 'text-green-600' : assist.status_foodentry === 0 ? ' text-red-600' : assist.status_foodentry === 2 ? ' text-yellow-600' : ''"
                                    >
                                        {{ assist.ri ? moment(assist.ri, 'HH:mm:ss').format('hh:mm:ss A') : "" }}
                                    </span>
                                </p>
                                <p>
                                    <strong>Hora Salida:</strong>
                                    <span
                                        class="text-sm p-1 whitespace-nowrap text-center rounded-md ml-1"
                                        :class="assist.status_out === 1 ? ' text-green-600' : assist.status_out === 0 ? ' text-red-600' : ''"
                                    >
                                        {{ assist.hs ? moment(assist.hs, 'HH:mm:ss').format('hh:mm:ss A') : "" }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <Pagination class="mt-2" :pagination="assists" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
