<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import SubmitButton from "@/Components/SubmitButton.vue";
import TitleForm from "@/Components/TitleForm.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    charge: Object,
});

const form = useForm({
    id: props.charge ? String(props.charge.id) : "",
    name: props.charge ? props.charge.name : "",
    description: props.charge ? props.charge.description : "",
    status: props.charge ? props.charge.status : true,
});

const submit = () => {
    if (props.charge) {
        form.put(route("charge.update", props.charge), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("charge.store"), {
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

</script>

<template>
    <TitleForm :title="form.id == 0 ? 'Registrar Cargo' : 'Actualizar Cargo'" @close-modal="emit('close-modal')" />
    <form @submit.prevent="submit">
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Nombre" required/>
                        <TextInput class="w-full" v-model="form.name" @input="form.name = toTitleCase(form.name)" />
                        <InputError class="w-full" :message="form.errors.name" />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Descripción" />
                        <TextArea class="w-full h-24" v-model="form.description" />
                        <InputError class="w-full" :message="form.errors.description" />
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <SubmitButton :text="form.id == 0 ? 'Registrar' : 'Actualizar'" :processing="form.processing" />
            </div>
        </div>
    </form>
</template>
