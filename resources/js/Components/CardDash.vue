<template>
    <div
        class="bg-3D-50 shadow-abajo-1 rounded-xl p-1 flex items-center transition-transform transform hover:scale-105 hover:shadow-xl border-t-4 border-indigo-500"
    >
        <div class="text-white text-3xl mr-4">
            <v-icon class="text-indigo-500" :name="iconName" scale="2"></v-icon>
        </div>
        <div>
            <h3 class="text-sm md:text-base lg:text-lg font-bold text-slate-500">{{ title }}</h3>
            <h5 class="text-sm md:text-base  text-indigo-400">{{ subtitle }}</h5>
            <p class="text-2xl md:text-3xl lg:text-3xl font-extrabold text-slate-500 mt-2">
                {{ quantity }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    title: String,
    subtitle: String,
    iconName: String,
    quantity: Number,
});
</script>

<style scoped>
/* No se necesita CSS adicional ya que Tailwind CSS se encarga del estilo */
</style>
