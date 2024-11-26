<template>
    <div class="relative">
        <input
            type="search"
            :value="query"
            @input="$emit('update:query', $event.target.value)"
            class="w-full text-sm sm:text-base hover:border-slate-200 focus:border-slate-500 focus:ring focus:ring-slate-200 bg-3D-50 h-10 px-5 pr-16 rounded-lg text-slate-500 border-slate-200 font-bold shadow-abajo-2"
            :placeholder="placeholder"
            @keyup.enter="onSearch"
        />
        <button
            @click.prevent="onSearch"
            class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-500 bg-blue-100 rounded-e-md hover:bg-blue-200 shadow-abajo-1"
        >
            <v-icon name="fa-search" scale="1.5" />
        </button>
    </div>
</template>

<script setup>
defineProps({
    query: String,
    placeholder: String,
});

const emit = defineEmits(['update:query', 'search']);

const onSearch = () => {
    emit('search');
};
</script>
