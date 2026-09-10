<script setup lang="ts">
import type {
    Project,
    ProjectPriority,
    ProjectStatus,
} from '@/types/project';

defineProps<{
    projects: Project[];
    deleting?: boolean;
}>();

const emit = defineEmits<{
    (e: 'edit', project: Project): void;
    (e: 'delete', project: Project): void;
}>();

const statusClasses: Record<ProjectStatus, string> = {
    Planning:
        'bg-blue-500/10 text-blue-700 dark:text-blue-400',
    'In Progress':
        'bg-amber-500/10 text-amber-700 dark:text-amber-400',
    'On Hold':
        'bg-slate-500/10 text-slate-700 dark:text-slate-400',
    Completed:
        'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
};

const priorityClasses: Record<ProjectPriority, string> = {
    Low: 'bg-slate-500/10 text-slate-700 dark:text-slate-400',
    Medium:
        'bg-amber-500/10 text-amber-700 dark:text-amber-400',
    High: 'bg-red-500/10 text-red-700 dark:text-red-400',
};
</script>

<template>
    <div class="overflow-hidden rounded-xl border bg-card">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b bg-muted/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">
                            Client
                        </th>

                        <th class="px-4 py-3 text-left font-medium">
                            Project
                        </th>

                        <th class="px-4 py-3 text-left font-medium">
                            Status
                        </th>

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
                        class="border-b transition-colors last:border-b-0 hover:bg-muted/30"
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
                                class="mt-1 max-w-xs truncate text-xs text-muted-foreground"
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

                        <td class="whitespace-nowrap px-4 py-3">
                            {{ project.start_date }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3">
                            {{ project.due_date }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <button
                                    type="button"
                                    class="rounded-md border px-3 py-1.5 text-sm transition-colors hover:bg-muted"
                                    @click="emit('edit', project)"
                                >
                                    Edit
                                </button>

                                <button
                                    type="button"
                                    class="rounded-md border px-3 py-1.5 text-sm text-destructive transition-colors hover:bg-destructive/10"
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
                            class="px-4 py-12 text-center"
                        >
                            <p class="font-medium">
                                No projects found
                            </p>

                            <p class="mt-1 text-sm text-muted-foreground">
                                Try adjusting your search or filters.
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>