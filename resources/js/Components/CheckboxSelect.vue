<template>
    <div class="relative">
        <div class="flex items-center">
            <div @click="toggleDropdown" class="border py-0 md:py-1 px-3 cursor-pointer bg-3D-50 flex justify-between items-center w-full shadow-abajo-2 text-slate-500 font-bold hover:border-slate-200 focus:border-blue-50 rounded-lg">
                <span class="text-sm sm:text-base">{{ selectedValues.length }} Opciones Seleccionadas</span>
                <span class="ml-2">
                    <v-icon name="ri-arrow-drop-down-line" scale="1.5" />
                </span>
            </div>
            <div class="ml-2 flex">
                <button @click="selectAll" class="border rounded bg-green-100 hover:bg-green-200 shadow-abajo-1" title="Seleccionar todas">
                    <v-icon class="p-0 text-slate-500" name="bi-check" scale="1.2" />
                </button>
                <button @click="deselectAll" class="border rounded bg-red-100 hover:bg-red-200 shadow-abajo-1 ml-1" title="Deseleccionar todas">
                    <v-icon class="p-0 text-slate-500" name="bi-x" scale="1.2" />
                </button>
            </div>
        </div>
        <div v-if="isOpen" class="absolute border bg-3D-50 mt-1 w-full z-10 max-h-60 overflow-y-auto shadow-abajo-2 rounded-lg">
            <div v-for="option in options" :key="option.id" class="flex items-center pt-2 pl-3 rounded-lg ">
                <input type="checkbox" class="text-slate-500 focus:border-slate-50 focus:ring-slate-500 bg-3D-50" v-model="selectedValues" :value="option.id" />
                <span class="ml-2 text-slate-500 text-sm sm:text-base">{{ option[displayField] }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    options: Array,
    modelValue: Array,
    displayField: String,
});

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const selectedValues = ref([...props.modelValue]);

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const selectAll = () => {
    selectedValues.value = props.options.map(option => option.id);
};

const deselectAll = () => {
    selectedValues.value = [];
};

watch(selectedValues, (newValue) => {
    emit('update:modelValue', newValue);
});
</script>
