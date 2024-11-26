<template>
    <div>
        <a :href="downloadUrl" :download="fileName">
            <v-icon v-if="isPdf" name="vi-file-type-pdf" scale="1.5" />
            <v-icon v-else-if="isWord" name="vi-file-type-word" scale="1.5" />
            <v-icon v-else-if="isImage" name="vi-file-type-image" scale="1.5" />
            <v-icon v-else name="vi-default-file" scale="1.5" />
        </a>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { defineProps } from "vue";

const props = defineProps({
    fileName: {
        type: String,
        required: true,
    },
    downloadUrl: {
        type: String,
        required: true,
    },
});

const isPdf = computed(() => props.fileName.endsWith(".pdf"));
const isWord = computed(
    () => props.fileName.endsWith(".docx") || props.fileName.endsWith(".doc")
);
const isImage = computed(() =>
    [".png", ".jpg", ".jpeg"].some((ext) => props.fileName.endsWith(ext))
);
</script>
