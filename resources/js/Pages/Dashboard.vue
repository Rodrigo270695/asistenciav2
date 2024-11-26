<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, watch } from "vue";
import CardDash from "@/Components/CardDash.vue";

const props = defineProps({
    assists: Number,
    workers: Number,
    pdvs: Number,
    zonals: Number,
    zonalsData: Array,
    zonalsDataAyer: Array,
    absencesData: Array,
    asistenciasPorEstado: Object,
    inasistenciasPorEstado: Object,
});

const selectedDate = ref("hoy"); // Estado para controlar la fecha seleccionada

const series = ref([]);
const chartOptions = ref({
    chart: {
        type: "pie",
        width: "100%",
    },
    labels: [],
    responsive: [
        {
            breakpoint: 768,
            options: {
                chart: {
                    width: 300,
                },
                legend: {
                    position: "right",
                },
            },
        },
    ],
});

// Actualiza los datos del gráfico cuando cambia la fecha seleccionada
watch(selectedDate, (newDate) => {
    const data = newDate === "hoy" ? props.zonalsData : props.zonalsDataAyer;
    series.value = data.map((zonal) => zonal.assists_count);
    chartOptions.value.labels = data.map((zonal) => zonal.zonal_name);
});

// Inicializa los datos del gráfico
series.value = props.zonalsData.map((zonal) => zonal.assists_count);
chartOptions.value.labels = props.zonalsData.map((zonal) => zonal.zonal_name);

const absencesBarSeries = ref([
    {
        name: "Inasistencias",
        data: props.absencesData.map((zonal) => zonal.absences_count),
    },
]);

/* Gráfico 3 */
const absencesBarOptions = ref({
    chart: {
        type: "bar",
        height: 300,
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: "55%",
            endingShape: "rounded",
        },
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        show: true,
        width: 2,
        colors: ["transparent"],
    },
    xaxis: {
        categories: props.absencesData.map((zonal) => zonal.zonal_name),
    },
    yaxis: {
        title: {
            text: "Número de Inasistencias",
        },
    },
    fill: {
        opacity: 1,
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return val + " inasistencias";
            },
        },
    },
});

/* Gráfico 2 */
const entryStatusSeries = ref([
    {
        name: "Ingresaron Bien",
        data: [props.asistenciasPorEstado[1] || 0],
    },
    {
        name: "Ingresaron con Tolerancia",
        data: [props.asistenciasPorEstado[2] || 0],
    },
    {
        name: "Llegaron Tarde",
        data: [props.asistenciasPorEstado[0] || 0],
    },
]);

const entryStatusOptions = ref({
    chart: {
        type: "bar",
        height: 300,
        stacked: true,
    },
    plotOptions: {
        bar: {
            horizontal: true,
        },
    },
    xaxis: {
        categories: ["Estado de Entrada"],
    },
    yaxis: {
        title: {
            text: "Número de Trabajadores",
        },
    },
    fill: {
        opacity: 1,
    },
    legend: {
        position: "top",
        horizontalAlign: "left",
        offsetX: 40,
    },
});

/* Gráfico 4 */
const inasistenciasPorEstado = ref(props.inasistenciasPorEstado);

const pieChartSeries = ref(inasistenciasPorEstado.value.map((e) => e.count));
const pieChartOptions = ref({
    chart: {
        type: "pie",
        width: "100%",
    },
    labels: inasistenciasPorEstado.value.map((e) => e.status),
    responsive: [
        {
            breakpoint: 768,
            options: {
                chart: {
                    width: 300,
                },
                legend: {
                    position: "right",
                },
            },
        },
    ],
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-bold text-xl text-slate-500">Dashboard</h2>
            </div>
        </template>
        <div class="py-5">
            <div class="">
                <div class="bg-3D-50 overflow-hidden shadow-abajo-2 rounded-lg">
                    <div
                        class="py-5 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 px-5"
                    >
                        <CardDash
                            title="Asistencias"
                            subtitle="Hoy"
                            iconName="bi-check"
                            :quantity="props.assists"
                        />
                        <CardDash
                            title="Trabajadores"
                            subtitle="Todos"
                            iconName="ri-user-fill"
                            :quantity="props.workers"
                        />
                        <CardDash
                            title="PDVs"
                            subtitle="Todos"
                            iconName="fa-store-alt"
                            :quantity="props.pdvs"
                        />
                        <CardDash
                            title="Zonales"
                            subtitle="Todos"
                            iconName="md-place"
                            :quantity="props.zonals"
                        />
                    </div>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 mx-5 gap-4 mb-3"
                    >
                        <div class="shadow-abajo-1 rounded-lg">
                            <h2
                                class="text-center text-2xl font-bold text-slate-500 my-2"
                            >
                                Asistencias por Zonal
                            </h2>
                            <div class="flex justify-center mb-4">
                                <button
                                    @click="selectedDate = 'hoy'"
                                    class="bg-blue-200 hover:bg-blue-300 px-4 py-1 rounded-lg shadow-abajo-2 hover:shadow-abajo-1 text-slate-500 hover:text-white font-bold"
                                >
                                    Hoy
                                </button>
                                <button
                                    @click="selectedDate = 'ayer'"
                                    class="bg-green-200 hover:bg-green-300 px-4 py-1 mx-2 rounded-lg shadow-abajo-2 hover:shadow-abajo-1 text-slate-500 font-bold"
                                >
                                    Ayer
                                </button>
                            </div>
                            <apexchart
                                width="100%"
                                type="pie"
                                :options="chartOptions"
                                :series="series"
                            ></apexchart>
                        </div>
                        <div class="shadow-abajo-1 rounded-lg">
                            <h2
                                class="text-center text-2xl font-bold text-slate-500 my-2"
                            >
                                Estado de Entrada de Trabajadores (Hoy)
                            </h2>
                            <apexchart
                                width="100%"
                                type="bar"
                                :options="entryStatusOptions"
                                :series="entryStatusSeries"
                            ></apexchart>
                        </div>
                        <div class="shadow-abajo-1 rounded-lg">
                            <h2
                                class="text-center text-2xl font-bold text-slate-500 my-2"
                            >
                                Inasistencias por Zonal
                            </h2>
                            <apexchart
                                width="100%"
                                type="bar"
                                :options="absencesBarOptions"
                                :series="absencesBarSeries"
                            ></apexchart>
                        </div>
                        <div class="shadow-abajo-1 rounded-lg">
                            <h2
                                class="text-center text-2xl font-bold text-slate-500 my-2"
                            >
                                Inasistencias por Estado
                            </h2>
                            <apexchart
                                width="100%"
                                type="pie"
                                :options="pieChartOptions"
                                :series="pieChartSeries"
                            ></apexchart>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Estilos adicionales si es necesario */
</style>
