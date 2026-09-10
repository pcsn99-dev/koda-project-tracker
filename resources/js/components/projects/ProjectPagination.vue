<script setup lang="ts">
import type { PaginationMeta } from '@/types/project';

defineProps<{
    meta: PaginationMeta;
    loading?: boolean;
}>();

const emit = defineEmits<{
    (e: 'change', page: number): void;
}>();
</script>

<template>
    <div
        class="flex flex-col gap-3 border-t pt-4 sm:flex-row sm:items-center sm:justify-between"
    >
        <p class="text-sm text-muted-foreground">
            <template v-if="meta.total > 0">
                Showing {{ meta.from }} to {{ meta.to }} of
                {{ meta.total }} projects
            </template>

            <template v-else>
                No projects found
            </template>
        </p>

        <div
            v-if="meta.last_page > 1"
            class="flex items-center gap-2"
        >
            <button
                type="button"
                class="rounded-md border px-3 py-2 text-sm disabled:cursor-not-allowed disabled:opacity-50"
                :disabled="meta.current_page === 1 || loading"
                @click="emit('change', meta.current_page - 1)"
            >
                Previous
            </button>

            <span class="px-2 text-sm text-muted-foreground">
                Page {{ meta.current_page }} of {{ meta.last_page }}
            </span>

            <button
                type="button"
                class="rounded-md border px-3 py-2 text-sm disabled:cursor-not-allowed disabled:opacity-50"
                :disabled="
                    meta.current_page === meta.last_page || loading
                "
                @click="emit('change', meta.current_page + 1)"
            >
                Next
            </button>
        </div>
    </div>
</template>