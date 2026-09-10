<script setup lang="ts">
import { FolderKanban } from 'lucide-vue-next';
</script>

<template>
    <div
        class="bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-md"
    >
        <FolderKanban class="size-4" />
    </div>

    <div class="grid flex-1 text-left text-sm leading-tight">
        <span class="truncate font-semibold"> Project Tracker </span>

        <span class="text-muted-foreground truncate text-xs">
            Client projects
        </span>
    </div>
</template>
