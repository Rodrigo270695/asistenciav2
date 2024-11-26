<template>
    <div class="relative group rounded-md">
        <button
            class="text-slate-500 p-1 rounded-md cursor-pointer shadow-abajo-1"
            @click="handleClick"
        >
            <v-icon :name="iconName" />
            <span
                class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-900 rounded-md"
                style="
                    left: 50%;
                    transform: translateX(-50%);
                    transition: opacity 0.3s;
                "
            >
                {{ tooltipText }}
            </span>
        </button>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    iconName: String,
    tooltipText: String,
});

const emit = defineEmits(["click"]);

const handleClick = () => {
    emit("click");
};
</script>
