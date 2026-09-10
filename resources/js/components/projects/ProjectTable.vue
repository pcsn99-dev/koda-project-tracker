<script setup lang="ts">
import type { Project } from '@/types/project';

defineProps<{
    projects: Project[];
    deleting?: boolean;
}>();

const emit = defineEmits<{
    (e: 'edit', project: Project): void;
    (e: 'delete', project: Project): void;
}>();
</script>

<template>
    <div class="overflow-hidden rounded-xl border">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-muted/50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Client</th>
                        <th class="px-4 py-3 text-left font-medium">Project</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-left font-medium">
                            Priority
                        </th>
                        <th class="px-4 py-3 text-left font-medium">
                            Start Date
                        </th>
                        <th class="px-4 py-3 text-left font-medium">
                            Due Date
                        </th>
                        <th class="px-4 py-3 text-right font-medium">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="project in projects"
                        :key="project.id"
                        class="hover:bg-muted/30 border-b last:border-b-0"
                    >
                        <td class="px-4 py-3">
                            {{ project.client_name }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="font-medium">
                                {{ project.project_name }}
                            </div>

                            <div
                                v-if="project.description"
                                class="text-muted-foreground mt-1 max-w-xs truncate text-xs"
                            >
                                {{ project.description }}
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            {{ project.status }}
                        </td>

                        <td class="px-4 py-3">
                            {{ project.priority }}
                        </td>

                        <td class="px-4 py-3">
                            {{ project.start_date }}
                        </td>

                        <td class="px-4 py-3">
                            {{ project.due_date }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <button
                                    type="button"
                                    class="rounded-md border px-3 py-1.5 text-sm"
                                    @click="emit('edit', project)"
                                >
                                    Edit
                                </button>

                                <button
                                    type="button"
                                    class="rounded-md border px-3 py-1.5 text-sm"
                                    :disabled="deleting"
                                    @click="emit('delete', project)"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="projects.length === 0">
                        <td
                            colspan="7"
                            class="text-muted-foreground px-4 py-10 text-center"
                        >
                            No projects found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
