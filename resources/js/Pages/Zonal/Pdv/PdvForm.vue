<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import SubmitButton from "@/Components/SubmitButton.vue";
import TitleForm from "@/Components/TitleForm.vue";
import DividerWithText from "@/Components/DividerWithText.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    pdv: Object,
    zonals: Array,
    horaries: Array,
});

const form = useForm({
    id: props.pdv ? String(props.pdv.id) : "",
    unit: props.pdv ? props.pdv.unit : "",
    spot: props.pdv ? props.pdv.spot : "",
    address: props.pdv ? props.pdv.address : "",
    zonal_id: props.pdv ? String(props.pdv.zonal_id) : "",
    horary_id: props.pdv ? String(props.pdv.horary_id) : "",
});

const submit = () => {
    if (props.pdv) {
        form.put(route("pdv.update", props.pdv), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("pdv.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);

const unidades = [
    { id: 1, name: 'Distribuidora' },
    { id: 2, name: 'Franquicia' },
    { id: 3, name: 'DAM' },
];

const toTitleCase = (str) => {
    return str.replace(/\w\S*/g, (txt) => {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
};

</script>

<template>
    <TitleForm :title="form.id == 0 ? 'Registrar PDV' : 'Actualizar PDV'" @close-modal="emit('close-modal')" />
    <form @submit.prevent="submit">
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-2">
                        <InputLabel value="Unidad" required/>
                        <select
                            v-model="form.unit"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="unidad in unidades"
                                :key="unidad.id"
                                :value="unidad.name"
                            >
                                {{ unidad.name }}
                            </option>
                        </select>
                        <InputError class="w-full" :message="form.errors.unit" />
                    </div>
                    <div class="col-span-6 sm:col-span-2">
                        <InputLabel value="Zonal" required/>
                        <select
                            v-model="form.zonal_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="zonal in zonals"
                                :key="zonal.id"
                                :value="zonal.id"
                            >
                                {{ zonal.name }}
                            </option>
                        </select>
                        <InputError class="w-full" :message="form.errors.zonal_id" />
                    </div>
                    <div class="col-span-6 sm:col-span-2">
                        <InputLabel value="Nombre" required/>
                        <TextInput class="w-full" v-model="form.spot" @input="form.spot = toTitleCase(form.spot)" />
                        <InputError class="w-full" :message="form.errors.spot" />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Dirección" />
                        <TextInput class="w-full" v-model="form.address" />
                        <InputError class="w-full" :message="form.errors.address" />
                    </div>
                </div>
                <DividerWithText text="Asignar Horario" />
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Horario" />
                        <select v-model="form.horary_id" class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500">
                            <option disabled selected value="">Elija una opción</option>
                            <option v-for="horary in horaries" :key="horary.id" :value="horary.id">{{ horary.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <SubmitButton :text="form.id == 0 ? 'Registrar' : 'Actualizar'" :processing="form.processing" />
            </div>
        </div>
    </form>
</template>
