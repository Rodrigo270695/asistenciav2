<script setup>
defineProps({
    value: String,
    required: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <label class="block text-sm sm:text-base md:text-lg text-slate-500 font-semibold">
        <span v-if="value">{{ value }}<span v-if="required" class="text-red-400">&nbsp;*</span></span>
        <span v-else><slot /></span>
    </label>
</template>
