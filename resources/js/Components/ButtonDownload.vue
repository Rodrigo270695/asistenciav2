<template>
    <button
        @click="handleClick"
        class="text-white font-bold mx-2 rounded flex items-center justify-center shadow-abajo-1 p-1"
    >
        <v-icon name="io-reload-sharp" class="animate-spin text-blue-400" scale="1.4" v-if="isDownloading" />
        <v-icon :name="iconName" scale="1.4" v-else />
    </button>
</template>

<script setup>
import { defineProps, defineEmits, ref, computed  } from "vue";

const props = defineProps({
    iconName: {
        type: String,
        required: true,
    },
    onClick: {
        type: Function,
        required: true,
    },
});

const emit = defineEmits(["click"]);
const isDownloading = ref(false);

const handleClick = () => {
    isDownloading.value = true;
    emit('click');
    props.onClick();

    setTimeout(() => {
        isDownloading.value = false;
    }, 2000);
};

const currentIcon = computed(() => (isDownloading.value ? props.downloadIcon : props.iconName));
</script>
