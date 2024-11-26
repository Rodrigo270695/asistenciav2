<template>
    <div class="flex items-center justify-center min-h-screen bg-3D-50">
        <div class="p-8 rounded-lg text-center shadow-abajo-2 ">
            <h1 class="text-4xl font-bold text-red-400 mb-4">403 - Acceso Denegado</h1>
            <p class="text-lg text-slate-500 mb-6">No tienes permiso para acceder a esta página.</p>
            <button
                @click="goBack"
                class="px-4 py-2 bg-blue-200 rounded hover:bg-blue-300 transition duration-300 text-slate-500 hover:text-slate-600 shadow-abajo-1"
            >
                Regresar
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Error403",
    methods: {
        goBack() {
            window.history.back();
        }
    }
};
</script>

<style scoped>
/* No se necesita CSS adicional ya que Tailwind CSS se encarga del estilo */
</style>
