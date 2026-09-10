<script setup lang="ts">
import type { Project } from '@/types/project';

defineProps<{
    projects: Project[];
}>();
</script>

<template>
    <div class="overflow-hidden rounded-xl border">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b bg-muted/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Client</th>
                        <th class="px-4 py-3 text-left font-medium">Project</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-left font-medium">Priority</th>
                        <th class="px-4 py-3 text-left font-medium">Start Date</th>
                        <th class="px-4 py-3 text-left font-medium">Due Date</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="project in projects"
                        :key="project.id"
                        class="border-b last:border-b-0 hover:bg-muted/30"
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
                    </tr>

                    <tr v-if="projects.length === 0">
                        <td
                            colspan="6"
                            class="px-4 py-10 text-center text-muted-foreground"
                        >
                            No projects found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>