<template>
    <div class="flex justify-between py-2 px-3 my-3">
        <div v-if="search" class="relative">
            <input
                type="search"
                :value="query"
                @input="$emit('update:query', $event.target.value)"
                class="w-64 md:w-72 lg:w-96 hover:border-slate-200 focus:border-blue-50 bg-3D-50 h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none shadow-abajo-2 text-slate-500 border-slate-200 font-bold"
                :placeholder="placeholder"
                @keyup.enter="search"
            />
            <button
                @click.prevent="search"
                class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-500 bg-blue-100 rounded-e-md hover:bg-blue-200 shadow-abajo-1"
            >
                <v-icon name="fa-search" scale="1.5" />
            </button>
        </div>
        <div v-if="add">
            <button
                class="bg-blue-100 shadow-abajo-1 p-2 text-slate-500 font-bold rounded-lg flex items-center hover:bg-blue-200 cursor-pointer"
                @click="add"
            >
                <v-icon name="io-add-circle-sharp" scale="1.1" />
                <p class="sm:block hidden ml-2">Agregar</p>
            </button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    query: String,
    search: Function,
    add: Function,
    placeholder: String,
});
</script>
