<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import SubmitButton from "@/Components/SubmitButton.vue";
import TitleForm from "@/Components/TitleForm.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    detailhorary: Object,
    horary: Object
});

const form = useForm({
    id: props.detailhorary ? String(props.detailhorary.id) : "",
    week: props.detailhorary ? props.detailhorary.week : [],
    hi: props.detailhorary ? props.detailhorary.hi : "",
    rs: props.detailhorary ? props.detailhorary.rs : "",
    ri: props.detailhorary ? props.detailhorary.ri : "",
    hs: props.detailhorary ? props.detailhorary.hs : "",
});

const submit = () => {
    const data = {
        hi: form.hi,
        rs: form.rs,
        ri: form.ri,
        hs: form.hs,
    };

    if (!props.detailhorary || form.week.length > 0) {
        data.week = form.week;
    }

    if (props.detailhorary) {
        form.put(route("detailhorary.update", props.detailhorary), {
            data,
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("detailhorary.store", { horary: props.horary.id }), {
            data,
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);

const weekOptions = [
    'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'
];
</script>

<template>
    <TitleForm :title="form.id == 0 ? 'Registrar Detalle de Horario' : 'Actualizar Detalle de Horario'" @close-modal="emit('close-modal')" />
    <form @submit.prevent="submit">
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-8 gap-3">
                    <div class="col-span-8 sm:col-span-8">
                        <InputLabel v-if="!props.detailhorary" value="Día de la Semana" :required="!props.detailhorary"/>
                        <select
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                            v-model="form.week"
                            multiple
                        >
                            <option disabled value="">
                                Elija una opción o múltiples (Ctrl + Click)
                            </option>
                            <option
                                v-for="day in weekOptions"
                                :key="day"
                                :value="day"
                            >
                                {{ day }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.week"
                        />
                    </div>
                    <div class="col-span-4 sm:col-span-2">
                        <InputLabel value="Hora Inicio" required/>
                        <TextInput
                            type="time"
                            class="w-full"
                            v-model="form.hi"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.hi"
                        />
                    </div>
                    <div class="col-span-4 sm:col-span-2">
                        <InputLabel value="Receso Salida"/>
                        <TextInput
                            type="time"
                            class="w-full"
                            v-model="form.rs"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.rs"
                        />
                    </div>
                    <div class="col-span-4 sm:col-span-2">
                        <InputLabel value="Receso Ingreso"/>
                        <TextInput
                            type="time"
                            class="w-full"
                            v-model="form.ri"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.ri"
                        />
                    </div>
                    <div class="col-span-4 sm:col-span-2">
                        <InputLabel value="Hora Salida" required/>
                        <TextInput
                            type="time"
                            class="w-full"
                            v-model="form.hs"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.hs"
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
