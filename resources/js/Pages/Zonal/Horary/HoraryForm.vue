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
    horary: Object,
});

const form = useForm({
    id: props.horary ? String(props.horary.id) : "",
    name: props.horary ? props.horary.name : "",
    description: props.horary ? props.horary.description : "",
});

const submit = () => {
    if (props.horary) {
        form.put(route("horary.update", props.horary), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("horary.store"), {
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
    <TitleForm :title="form.id == 0 ? 'Registrar Horario' : 'Actualizar Horario'" @close-modal="emit('close-modal')" />
    <form @submit.prevent="submit">
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Nombre" required/>
                        <TextInput
                            class="w-full"
                            v-model="form.name"
                            @input="form.name = toTitleCase($event.target.value)"
                            required
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.name"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Descripción"/>
                        <TextArea
                            class="w-full h-24"
                            v-model="form.description"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.description"
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
