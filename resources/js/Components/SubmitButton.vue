<template>
    <button
        class="bg-blue-100 hover:bg-blue-200 text-slate-500 font-bold px-4 py-2 rounded-md mr-2 shadow-abajo-1"
        :disabled="processing"
    >
        {{ text }}
    </button>
</template>

<script setup>
defineProps({
    text: {
        type: String,
        required: true
    },
    processing: {
        type: Boolean,
        default: false
    }
});
</script>
