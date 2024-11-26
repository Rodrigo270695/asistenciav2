<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import SubmitButton from "@/Components/SubmitButton.vue";
import TextArea from "@/Components/TextArea.vue";
import TitleForm from "@/Components/TitleForm.vue";
import DividerWithText from "@/Components/DividerWithText.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    absence: Object,
    workers: Array,
    reasons: Array,
});

const form = useForm({
    _method: props.absence ? 'PUT' : 'POST',
    id: props.absence ? String(props.absence.id) : "",
    start_date: props.absence ? props.absence.start_date : "",
    end_date: props.absence ? props.absence.end_date : "",
    file: null,
    status: props.absence ? props.absence.status : "Por aprobar",
    status_detail: props.absence ? props.absence.status_detail : "",
    worker_id: props.absence ? String(props.absence.worker_id) : "",
    reason_id: props.absence ? String(props.absence.reason_id) : "",
});

const submit = () => {
    if (props.absence) {
        form.post(route("absence.update", props.absence), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("absence.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const handleFileChange = (event) => {
    form.file = event.target.files[0];
};

const emit = defineEmits(["close-modal"]);

const estados = [
    { value: "Por aprobar", text: "Por aprobar" },
    { value: "Aprobado", text: "Aprobado" },
    { value: "Rechazado", text: "Rechazado" },
    { value: "Observado", text: "Observado" },
];
</script>

<template>
    <TitleForm :title="form.id == 0 ? 'Registrar Ausencia' : 'Actualizar Ausencia'" @close-modal="emit('close-modal')" />
    <form @submit.prevent="submit">
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <DividerWithText text="Datos de la ausencia" class="mb-3"/>
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Trabajador" required/>
                        <select
                            v-model="form.worker_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="worker in workers"
                                :key="worker.id"
                                :value="worker.id"
                            >
                                {{ worker.lastname + ' ' + worker.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.worker_id"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Razón" required/>
                        <select
                            v-model="form.reason_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="reason in reasons"
                                :key="reason.id"
                                :value="reason.id"
                            >
                                {{ reason.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.reason_id"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Fecha de inicio" required/>
                        <TextInput
                            type="date"
                            class="w-full"
                            v-model="form.start_date"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.start_date"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Fecha de fin"/>
                        <TextInput
                            type="date"
                            class="w-full"
                            v-model="form.end_date"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.end_date"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Archivo (img, doc, pdf)"/>
                        <TextInput
                            type="file"
                            class="w-full file:bg-transparent file:border-none file:px-1 file:mt-2 file:p-0 file:shadow-abajo-1 file:rounded-md file:text-slate-500 file:font-bold file:hover:text-slate-500  file:cursor-pointer"
                            @change="handleFileChange"
                            accept=".jpg,.jpeg,.png,.doc,.docx,.pdf"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.file"
                        />
                    </div>
                </div>
                <DividerWithText v-if="$page.props.user.roles.includes('administrador') || $page.props.user.roles.includes('visualizador')" text="Detalles de la ausencia" class="my-3"/>
                <div v-if="$page.props.user.roles.includes('administrador') || $page.props.user.roles.includes('visualizador')" class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Estado" required/>
                        <select
                            v-model="form.status"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="estado in estados"
                                :key="estado.value"
                                :value="estado.value"
                            >
                                {{ estado.text }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.status"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Detalle del estado"/>
                        <TextArea
                            class="w-full h-28"
                            v-model="form.status_detail"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.status_detail"
                        />
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <SubmitButton :text="form.id == 0 ? 'Registrar' : 'Actualizar'" :processing="form.processing" />
            </div>
        </div>
    </form>
</template>
