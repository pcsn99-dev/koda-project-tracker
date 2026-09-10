<script setup lang="ts">
import type { Project, ProjectPriority, ProjectStatus } from '@/types/project';

defineProps<{
    projects: Project[];
    deleting?: boolean;
}>();

const emit = defineEmits<{
    (e: 'edit', project: Project): void;
    (e: 'delete', project: Project): void;
}>();

const statusClasses: Record<ProjectStatus, string> = {
    Planning: 'bg-blue-500/10 text-blue-700 dark:text-blue-400',
    'In Progress': 'bg-amber-500/10 text-amber-700 dark:text-amber-400',
    'On Hold': 'bg-slate-500/10 text-slate-700 dark:text-slate-400',
    Completed: 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
};

const priorityClasses: Record<ProjectPriority, string> = {
    Low: 'bg-slate-500/10 text-slate-700 dark:text-slate-400',
    Medium: 'bg-amber-500/10 text-amber-700 dark:text-amber-400',
    High: 'bg-red-500/10 text-red-700 dark:text-red-400',
};
</script>

<template>
    <div class="bg-card overflow-hidden rounded-xl border">
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
                        class="hover:bg-muted/30 border-b transition-colors last:border-b-0"
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
                            <span
                                class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium"
                                :class="statusClasses[project.status]"
                            >
                                {{ project.status }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <span
                                class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium"
                                :class="priorityClasses[project.priority]"
                            >
                                {{ project.priority }}
                            </span>
                        </td>

                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ project.start_date }}
                        </td>

                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ project.due_date }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <button
                                    type="button"
                                    class="hover:bg-muted rounded-md border px-3 py-1.5 text-sm transition-colors"
                                    @click="emit('edit', project)"
                                >
                                    Edit
                                </button>

                                <button
                                    type="button"
                                    class="text-destructive hover:bg-destructive/10 rounded-md border px-3 py-1.5 text-sm transition-colors"
                                    :disabled="deleting"
                                    @click="emit('delete', project)"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="projects.length === 0">
                        <td colspan="7" class="px-4 py-12 text-center">
                            <p class="font-medium">No projects found</p>

                            <p class="text-muted-foreground mt-1 text-sm">
                                Try adjusting your search or filters.
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
