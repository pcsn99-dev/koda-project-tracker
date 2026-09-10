<script setup lang="ts">
import type { Project } from '@/types/project';

defineProps<{
    project: Project;
    processing?: boolean;
}>();

const emit = defineEmits<{
    (e: 'confirm'): void;
    (e: 'cancel'): void;
}>();
</script>

<template>
    <div
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        role="presentation"
        @click.self="emit('cancel')"
    >
        <div
            class="w-full max-w-md rounded-xl border bg-background p-6 shadow-lg"
            role="dialog"
            aria-modal="true"
            aria-labelledby="delete-project-title"
        >
            <h2
                id="delete-project-title"
                class="text-lg font-semibold"
            >
                Delete project?
            </h2>

            <p class="mt-2 text-sm text-muted-foreground">
                You are about to permanently delete
                <span class="font-medium text-foreground">
                    {{ project.project_name }}
                </span>.
                This action cannot be undone.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    class="rounded-md border px-4 py-2 text-sm"
                    :disabled="processing"
                    @click="emit('cancel')"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="rounded-md bg-destructive px-4 py-2 text-sm text-destructive-foreground"
                    :disabled="processing"
                    @click="emit('confirm')"
                >
                    {{ processing ? 'Deleting...' : 'Delete Project' }}
                </button>
            </div>
        </div>
    </div>
</template>