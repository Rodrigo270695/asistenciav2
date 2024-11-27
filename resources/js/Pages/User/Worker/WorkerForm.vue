<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import SubmitButton from "@/Components/SubmitButton.vue";
import TitleForm from "@/Components/TitleForm.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    worker: Object,
    pdvs: Array,
    charges: Array, 
});

const form = useForm({
    id: props.worker ? String(props.worker.id) : "",
    name: props.worker ? props.worker.name : "",
    lastname: props.worker ? props.worker.lastname : "",
    document_type: props.worker ? props.worker.document_type : "",
    num_document: props.worker ? props.worker.num_document : "",
    entry_date: props.worker ? props.worker.entry_date : "",
    exit_date: props.worker ? props.worker.exit_date : "",
    birth_date: props.worker ? props.worker.birth_date : "",
    phone: props.worker ? props.worker.phone : "",
    email: props.worker ? props.worker.email : "",
    address: props.worker ? props.worker.address : "",
    status: props.worker ? props.worker.status : true,
    charge_id: props.worker ? String(props.worker.charge_id) : "",
    pdv_id: props.worker ? String(props.worker.pdv_id) : "",
});

const submit = () => {
    if (props.worker) {
        form.put(route("worker.update", props.worker), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("worker.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);

const toTitleCase = (str) => {
    return str.replace(/\w\S*/g, (txt) => {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
};

const documentTypes = ['DNI', 'CARNET DE EXTRANJERIA', 'OTROS'];

</script>

<template>
    <TitleForm :title="form.id == 0 ? 'Registrar Trabajador' : 'Actualizar Trabajador'" @close-modal="emit('close-modal')" />
    <form @submit.prevent="submit">
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-3 sm:col-span-3">
                        <InputLabel value="Nombre" required/>
                        <TextInput class="w-full" v-model="form.name" @input="form.name = toTitleCase(form.name)" />
                        <InputError class="w-full" :message="form.errors.name" />
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <InputLabel value="Apellido" required/>
                        <TextInput class="w-full" v-model="form.lastname" @input="form.lastname = toTitleCase(form.lastname)" />
                        <InputError class="w-full" :message="form.errors.lastname" />
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <InputLabel value="Tipo Documento" required/>
                        <select
                            v-model="form.document_type"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="type in documentTypes"
                                :key="type"
                                :value="type"
                            >
                                {{ type }}
                            </option>
                        </select>
                        <InputError class="w-full" :message="form.errors.document_type" />
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <InputLabel value="Número Documento" required/>
                        <TextInput class="w-full" v-model="form.num_document" />
                        <InputError class="w-full" :message="form.errors.num_document" />
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <InputLabel value="Fecha de Ingreso" required/>
                        <TextInput type="date" class="w-full" v-model="form.entry_date" />
                        <InputError class="w-full" :message="form.errors.entry_date" />
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <InputLabel value="PDV" required/>
                        <select
                            v-model="form.pdv_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="pdv in pdvs"
                                :key="pdv.id"
                                :value="pdv.id"
                            >
                                {{ pdv.zonal.name + ' - ' + pdv.spot }}
                            </option>
                        </select>
                        <InputError class="w-full" :message="form.errors.pdv_id" />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Cargo" required/>
                        <select
                            v-model="form.charge_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="charge in charges"
                                :key="charge.id"
                                :value="charge.id"
                            >
                                {{ charge.name }}
                            </option>
                        </select>
                        <InputError class="w-full" :message="form.errors.charge_id" />
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <InputLabel value="Celular" />
                        <TextInput class="w-full" v-model="form.phone" />
                        <InputError class="w-full" :message="form.errors.phone" />
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <InputLabel value="Fecha Nacimiento" />
                        <TextInput type="date" class="w-full" v-model="form.birth_date" />
                        <InputError class="w-full" :message="form.errors.birth_date" />
                    </div>
                    <div v-if="worker" class="col-span-6 sm:col-span-2">
                        <InputLabel value="Fecha de Salida" />
                        <TextInput type="date" class="w-full" v-model="form.exit_date" />
                        <InputError class="w-full" :message="form.errors.exit_date" />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Email" />
                        <TextInput class="w-full" v-model="form.email" />
                        <InputError class="w-full" :message="form.errors.email" />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Dirección" />
                        <TextInput class="w-full" v-model="form.address" />
                        <InputError class="w-full" :message="form.errors.address" />
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <SubmitButton :text="form.id == 0 ? 'Registrar' : 'Actualizar'" :processing="form.processing" />
            </div>
        </div>
    </form>
</template>
