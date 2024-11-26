<template>
    <div class="flex items-center">
        <div class="flex-grow border-t border-gray-300"></div>
        <span class="mx-4 text-gray-500">{{ text }}</span>
        <div class="flex-grow border-t border-gray-300"></div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    text: {
        type: String,
        required: true,
    },
});
</script>

<style scoped>
/* Puedes agregar estilos adicionales aquí si es necesario */
</style>
