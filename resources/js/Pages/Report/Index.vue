<script setup>
import { Inertia } from '@inertiajs/inertia';
import AppLayout from "@/Layouts/AppLayout.vue";
import IndexHeader from "@/Components/IndexHeader.vue";
import CheckboxSelect from "@/Components/CheckboxSelect.vue";
import DividerWithText from "@/Components/DividerWithText.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Pagination from "@/Components/Pagination.vue";
import SimpleSearch from "@/Components/SimpleSearch.vue";
import InputError from "@/Components/InputError.vue";
import { ref, computed, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import moment from "moment";

const props = defineProps({
    assists: Object,
    zonals: Array,
    pdvs: Array,
    query: String,
    startDate: String,
    endDate: String,
    selectedPdvs: Array,
    selectedZonals: Array,
});

const query = ref(props.query);
const selectedZonals = ref(props.zonals.map((zonal) => zonal.id));
const selectedPdvs = ref(props.selectedPdvs);
const startDate = ref(props.startDate);
const endDate = ref(props.endDate);

const form = useForm({
    pdvs: selectedPdvs.value,
    startDate: startDate.value,
    endDate: endDate.value,
    query: query.value,
    zonals: selectedZonals.value,
});

// Filtrar PDVs según los zonales seleccionados
const filteredPdvs = computed(() => {
    if (selectedZonals.value.length === 0) {
        return props.pdvs;
    }
    return props.pdvs.filter((pdv) =>
        selectedZonals.value.includes(pdv.zonal_id)
    );
});

// Watch para actualizar selectedPdvs cuando cambian los zonales seleccionados
watch(selectedZonals, () => {
    selectedPdvs.value = filteredPdvs.value.map((pdv) => pdv.id);
});

// Funciones para los botones
const search = () => {
    form.startDate = startDate.value;
    form.endDate = endDate.value;
    form.query = query.value;
    form.get(route("report.index"));
};

const mostrarDatos = () => {
    form.startDate = startDate.value;
    form.endDate = endDate.value;
    form.query = query.value;
    form.zonals = selectedZonals.value;
    form.pdvs = selectedPdvs.value;
    form.get(route("report.index"));
};

const consultarHoy = () => {
    const today = moment().format("YYYY-MM-DD");
    startDate.value = today;
    endDate.value = today;
    mostrarDatos();
};

const consultarAyer = () => {
    const yesterday = moment().subtract(1, "days").format("YYYY-MM-DD");
    startDate.value = yesterday;
    endDate.value = yesterday;
    mostrarDatos();
};

const consultarMes = () => {
    const startOfMonth = moment().startOf("month").format("YYYY-MM-DD");
    const endOfMonth = moment().endOf("month").format("YYYY-MM-DD");
    startDate.value = startOfMonth;
    endDate.value = endOfMonth;
    mostrarDatos();
};

const goToIndex = () => {
    Inertia.visit(route('report.index'));
};

const exportToExcel = () => {
    const params = {
        startDate: startDate.value,
        endDate: endDate.value,
        pdvs: selectedPdvs.value,
        query: query.value,
    };
    const queryString = new URLSearchParams(params).toString();
    window.location.href = `${route('report.export')}?${queryString}`;
};
</script>


<template>
    <AppLayout title="Reportes">
        <template #header>
            <IndexHeader title="Reportes" @reload="goToIndex" />
        </template>
        <div class="pt-5">
            <div class="shadow-abajo-2 rounded-lg p-4 bg-slate-200">
                <h2 class="text-sm sm:text-base md:text-lg lg:text-xl text-slate-500 font-extrabold my-2">
                    Introduce los criterios de búsqueda
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-8 gap-4">
                    <div class="order-2 md:order-1 col-span-8 md:col-span-3">
                        <InputLabel>Buscar Trabajador</InputLabel>
                        <SimpleSearch
                            v-model:query="query"
                            @search="search"
                            placeholder="Buscar por nombre, apellido o DNI"
                        />
                    </div>
                    <div class="hidden sm:block order-3 md:order-2 col-span-8 md:col-span-3"></div>
                    <div class="order-1 md:order-3 row-span-3 col-span-8 md:col-span-2 shadow-abajo-1 rounded-lg p-4 flex flex-col gap-1">
                        <DividerWithText text="Indique la consulta" />
                        <div class="grid grid-cols-2 md:grid-cols-1 gap-1">
                            <button
                                @click="consultarHoy"
                                class="text-sm sm:text-base bg-blue-100 text-slate-500 font-bold px-2 py-1 hover:bg-blue-200 rounded-md w-full"
                            >
                                Consultar hoy
                            </button>
                            <button
                                @click="consultarAyer"
                                class="text-sm sm:text-base bg-blue-100 text-slate-500 font-bold px-2 py-1 hover:bg-blue-200 rounded-md w-full"
                            >
                                Consultar ayer
                            </button>
                            <button
                                @click="consultarMes"
                                class="text-sm sm:text-base bg-blue-100 text-slate-500 font-bold px-2 py-1 hover:bg-blue-200 rounded-md w-full"
                            >
                                Consultar mes
                            </button>
                            <button
                                @click="exportToExcel"
                                class="text-sm sm:text-base bg-green-100 text-slate-500 font-bold px-2 py-1 hover:bg-green-200 rounded-md w-full"
                            >
                                Exportar excel
                            </button>
                            <button
                                @click="mostrarDatos"
                                class="col-span-2 md:col-span-1 text-sm sm:text-base bg-violet-200 text-slate-500 font-bold px-2 py-1 hover:bg-violet-300 rounded-md w-full"
                            >
                                Mostrar Datos
                            </button>
                        </div>
                    </div>
                    <div class="order-5 md:order-4 col-span-8 md:col-span-3">
                        <InputLabel>Zonales</InputLabel>
                        <CheckboxSelect
                            v-model="selectedZonals"
                            :options="props.zonals"
                            displayField="name"
                        />
                    </div>
                    <div class="order-6 md:order-5 col-span-8 md:col-span-3">
                        <InputLabel>PDVs</InputLabel>
                        <CheckboxSelect
                            v-model="selectedPdvs"
                            :options="filteredPdvs"
                            displayField="spot"
                        />
                    </div>
                    <div class="order-7 md:order-7 col-span-4 md:col-span-3">
                        <InputLabel>Desde</InputLabel>
                        <TextInput
                            type="date"
                            class="w-full"
                            v-model="startDate"
                        />
                        <InputError :message="form.errors.startDate" />
                    </div>
                    <div class="order-8 md:order-8 col-span-4 md:col-span-3">
                        <InputLabel>Hasta</InputLabel>
                        <TextInput
                            type="date"
                            class="w-full"
                            v-model="endDate"
                        />
                    </div>
                </div>
            </div>
            <DividerWithText class="my-3" text="Asistencia" />
            <div class="">
                <div class="hidden md:block">
                    <div class="overflow-x-auto rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-200 shadow-abajo-2">
                                <tr>
                                    <th class="px-2 py-2 text-left text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-wider border-l">
                                        Fecha
                                    </th>
                                    <th class="px-2 py-2 text-center text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-wider border-l">
                                        Punto de Venta
                                    </th>
                                    <th class="px-2 py-2 text-center text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-wider border-l">
                                        Trabajador
                                    </th>
                                    <th class="px-2 py-2 text-center text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-wider border-l">
                                        Ingreso laboral
                                    </th>
                                    <th class="px-2 py-2 text-center text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-wider border-l">
                                        Receso Salida
                                    </th>
                                    <th class="px-2 py-2 text-center text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-wider border-l">
                                        Receso Ingreso
                                    </th>
                                    <th class="px-2 py-2 text-center text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-wider border-l">
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
                                    <td class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap">
                                        {{
                                            moment(assist.current_date).format(
                                                "DD/MM/YYYY"
                                            )
                                        }}
                                    </td>
                                    <td class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center">
                                        {{
                                            assist.worker.pdv.zonal.name +
                                            " - " +
                                            assist.worker.pdv.spot
                                        }}
                                    </td>
                                    <td class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center">
                                        {{
                                            assist.worker.lastname +
                                            " " +
                                            assist.worker.name
                                        }}
                                    </td>
                                    <td class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center">
                                        <span
                                            class="text-sm p-1 whitespace-nowrap text-center rounded-md"
                                            :class="
                                                assist.status_entry === 1
                                                    ? 'bg-green-100 text-green-600'
                                                    : assist.status_entry === 0
                                                    ? 'bg-red-100 text-red-600'
                                                    : assist.status_entry === 2
                                                    ? 'bg-yellow-100 text-yellow-600'
                                                    : ''
                                            "
                                        >
                                            {{
                                                assist.hi
                                                    ? moment(
                                                          assist.hi,
                                                          "HH:mm:ss"
                                                      ).format("hh:mm:ss A")
                                                    : ""
                                            }}
                                        </span>
                                    </td>
                                    <td class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center">
                                        <span
                                            class="text-sm p-1 whitespace-nowrap text-center rounded-md"
                                            :class="
                                                assist.status_foodout === 1
                                                    ? 'bg-green-100 text-green-600'
                                                    : assist.status_foodout ===
                                                      0
                                                    ? 'bg-red-100 text-red-600'
                                                    : ''
                                            "
                                        >
                                            {{
                                                assist.rs
                                                    ? moment(
                                                          assist.rs,
                                                          "HH:mm:ss"
                                                      ).format("hh:mm:ss A")
                                                    : ""
                                            }}
                                        </span>
                                    </td>
                                    <td class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center">
                                        <span
                                            class="text-sm p-1 whitespace-nowrap text-center rounded-md"
                                            :class="
                                                assist.status_foodentry === 1
                                                    ? 'bg-green-100 text-green-600'
                                                    : assist.status_foodentry ===
                                                      0
                                                    ? 'bg-red-100 text-red-600'
                                                    : assist.status_foodentry ===
                                                      2
                                                    ? 'bg-yellow-100 text-yellow-600'
                                                    : ''
                                            "
                                        >
                                            {{
                                                assist.ri
                                                    ? moment(
                                                          assist.ri,
                                                          "HH:mm:ss"
                                                      ).format("hh:mm:ss A")
                                                    : ""
                                            }}
                                        </span>
                                    </td>
                                    <td class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center">
                                        <span
                                            class="text-sm p-1 whitespace-nowrap text-center rounded-md"
                                            :class="
                                                assist.status_out === 1
                                                    ? 'bg-green-100 text-green-600'
                                                    : assist.status_out === 0
                                                    ? 'bg-red-100 text-red-600'
                                                    : ''
                                            "
                                        >
                                            {{
                                                assist.hs
                                                    ? moment(
                                                          assist.hs,
                                                          "HH:mm:ss"
                                                      ).format("hh:mm:ss A")
                                                    : ""
                                            }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="assists.data.length <= 0">
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
                <div class="block md:hidden rounded-lg">
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
                                    {{
                                        assist.worker.lastname +
                                        " " +
                                        assist.worker.name
                                    }}
                                </span>
                            </h3>
                        </div>
                        <!-- Detalles de la tarjeta -->
                        <div class="text-md text-slate-700">
                            <p>
                                <strong>fecha:</strong>
                                <span class="ml-1">
                                    {{
                                        moment(assist.current_date).format(
                                            "DD/MM/YYYY"
                                        )
                                    }}
                                </span>
                            </p>
                            <p>
                                <strong>Punto de Venta:</strong>
                                <span class="ml-1">
                                    {{
                                        assist.worker.pdv.zonal.name +
                                        " - " +
                                        assist.worker.pdv.spot
                                    }}
                                </span>
                            </p>
                            <p>
                                <strong>Ingreso laboral:</strong>
                                <span
                                    class="text-sm p-1 whitespace-nowrap text-center rounded-md ml-1"
                                    :class="
                                        assist.status_entry === 1
                                            ? ' text-green-600'
                                            : assist.status_entry === 0
                                            ? ' text-red-600'
                                            : assist.status_entry === 2
                                            ? ' text-yellow-600'
                                            : ''
                                    "
                                >
                                    {{
                                        assist.hi
                                            ? moment(
                                                  assist.hi,
                                                  "HH:mm:ss"
                                              ).format("hh:mm:ss A")
                                            : ""
                                    }}
                                </span>
                            </p>
                            <p>
                                <strong>Receso Salida:</strong>
                                <span
                                    class="text-sm p-1 whitespace-nowrap text-center rounded-md ml-1"
                                    :class="
                                        assist.status_foodout === 1
                                            ? 'text-green-600'
                                            : assist.status_foodout === 0
                                            ? ' text-red-600'
                                            : ''
                                    "
                                >
                                    {{
                                        assist.rs
                                            ? moment(
                                                  assist.rs,
                                                  "HH:mm:ss"
                                              ).format("hh:mm:ss A")
                                            : ""
                                    }}
                                </span>
                            </p>
                            <p>
                                <strong>Receso Ingreso:</strong>
                                <span
                                    class="text-sm p-1 whitespace-nowrap text-center rounded-md ml-1"
                                    :class="
                                        assist.status_foodentry === 1
                                            ? 'text-green-600'
                                            : assist.status_foodentry === 0
                                            ? ' text-red-600'
                                            : assist.status_foodentry === 2
                                            ? ' text-yellow-600'
                                            : ''
                                    "
                                >
                                    {{
                                        assist.ri
                                            ? moment(
                                                  assist.ri,
                                                  "HH:mm:ss"
                                              ).format("hh:mm:ss A")
                                            : ""
                                    }}
                                </span>
                            </p>
                            <p>
                                <strong>Hora Salida:</strong>
                                <span
                                    class="text-sm p-1 whitespace-nowrap text-center rounded-md ml-1"
                                    :class="
                                        assist.status_out === 1
                                            ? ' text-green-600'
                                            : assist.status_out === 0
                                            ? ' text-red-600'
                                            : ''
                                    "
                                >
                                    {{
                                        assist.hs
                                            ? moment(
                                                  assist.hs,
                                                  "HH:mm:ss"
                                              ).format("hh:mm:ss A")
                                            : ""
                                    }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <Pagination class="mt-2" :pagination="assists" />
            </div>
        </div>
    </AppLayout>
</template>
