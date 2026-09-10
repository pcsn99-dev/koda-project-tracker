<script setup lang="ts">
import { FolderKanban } from 'lucide-vue-next';
</script>

<template>
    <div
        class="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground"
    >
        <FolderKanban class="size-4" />
    </div>

    <div class="grid flex-1 text-left text-sm leading-tight">
        <span class="truncate font-semibold">
            Project Tracker
        </span>

        <span class="truncate text-xs text-muted-foreground">
            Client projects
        </span>
    </div>
</template>