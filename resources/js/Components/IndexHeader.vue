<template>
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <button
                v-if="showBackButton"
                class="bg-blue-100 hover:bg-blue-200 w-12 rounded-md shadow-abajo-1 mr-2"
                @click="$emit('goBack')"
            >
                <v-icon class="text-slate-500" name="md-arrowbackios-round" scale="1.7" />
            </button>
            <h2 class="font-bold text-xl text-slate-500">
                {{ title }}
            </h2>
        </div>
        <button class="bg-green-100 hover:bg-green-200 w-12 rounded-md shadow-abajo-1" @click="$emit('reload')">
            <v-icon class="text-slate-500" name="io-reload-circle-sharp" scale="1.7" />
        </button>
    </div>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        required: true
    },
    showBackButton: {
        type: Boolean,
        default: false
    }
});
</script>
