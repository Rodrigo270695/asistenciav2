<template>
    <div class="flex items-center py-2 px-3 my-3">
        <TextInput
            :modelValue="query"
            @update:modelValue="$emit('update:query', $event)"
            class="w-64 md:w-72 lg:w-96 hover:border-slate-200 focus:border-blue-50 bg-3D-50 h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none shadow-abajo-2 text-slate-500 border-slate-200 font-bold"
            :placeholder="placeholder"
            @keyup.enter="search"
        />
        <button
            @click.prevent="search"
            class="ml-2 px-3 flex items-center text-slate-500 bg-blue-100 rounded-md hover:bg-blue-200 shadow-abajo-1"
        >
            <v-icon name="fa-search" scale="1.5" />
        </button>
    </div>
</template>

<script setup>
import TextInput from './TextInput.vue';

defineProps({
    query: String,
    search: Function,
    placeholder: String,
});
</script>
