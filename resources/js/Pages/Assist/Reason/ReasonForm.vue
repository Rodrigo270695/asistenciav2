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
    reason: Object,
});

const form = useForm({
    id: props.reason ? String(props.reason.id) : "",
    name: props.reason ? props.reason.name : "",
    description: props.reason ? props.reason.description : "",
    status: props.reason ? props.reason.status : true,
});

const submit = () => {
    if (props.reason) {
        form.put(route("reason.update", props.reason), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("reason.store"), {
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
    <TitleForm :title="form.id == 0 ? 'Registrar Razón' : 'Actualizar Razón'" @close-modal="emit('close-modal')" />
    <form @submit.prevent="submit">
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Nombre" required/>
                        <TextInput class="w-full" v-model="form.name" @input="form.name = toTitleCase($event.target.value)" />
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
