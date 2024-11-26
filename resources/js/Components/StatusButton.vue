<template>
    <div class="relative group">
        <button
            class="text-slate-500 p-1 rounded-md cursor-pointer shadow-abajo-1"
            :class="{
                'bg-orange-200 hover:bg-orange-300': status === 1,
                'bg-green-200 hover:bg-green-300': status === 0,
            }"
            @click="handleClick"
        >
            <v-icon :name="status === 1 ? 'gi-cancel' : 'fa-check'" />
            <span
                class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-900 rounded-md"
                style="
                    left: 50%;
                    transform: translateX(-50%);
                    transition: opacity 0.3s;
                "
            >
                {{ tooltipText }}
            </span>
        </button>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    tooltipText: String,
    status: Number,
});

const emit = defineEmits(["click"]);

const handleClick = () => {
    emit("click");
};
</script>

<style scoped>
/* Puedes agregar estilos adicionales aquí si es necesario */
</style>
