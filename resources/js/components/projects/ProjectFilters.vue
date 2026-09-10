<script setup lang="ts">
import type { ProjectFiltersState } from '@/types/project';
import { reactive } from 'vue';

const emit = defineEmits<{
    (e: 'apply', filters: ProjectFiltersState): void;
}>();

const defaultFilters: ProjectFiltersState = {
    search: '',
    status: '',
    priority: '',
    sort_by: 'created_at',
    sort_direction: 'desc',
};

const filters = reactive<ProjectFiltersState>({
    ...defaultFilters,
});

function applyFilters() {
    emit('apply', { ...filters });
}

function resetFilters() {
    Object.assign(filters, defaultFilters);
    applyFilters();
}
</script>

<template>
    <form class="bg-card rounded-xl border p-5" @submit.prevent="applyFilters">
        <div class="space-y-5">
            <!-- Search -->
            <div class="space-y-2">
                <label for="project-search" class="text-sm font-medium">
                    Search
                </label>

                <input
                    id="project-search"
                    v-model="filters.search"
                    type="search"
                    placeholder="Search by client or project name..."
                    class="bg-background w-full rounded-md border px-3 py-2 text-sm"
                />
            </div>

            <!-- Filter + Sort -->
            <div class="grid gap-5 lg:grid-cols-2">
                <!-- Filters -->
                <div class="space-y-3">
                    <div>
                        <h3 class="text-sm font-medium">Filters</h3>

                        <p class="text-muted-foreground text-xs">
                            Narrow projects by status or priority.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label
                                for="status-filter"
                                class="text-muted-foreground text-xs font-medium"
                            >
                                Status
                            </label>

                            <select
                                id="status-filter"
                                v-model="filters.status"
                                class="bg-background w-full rounded-md border px-3 py-2 text-sm"
                            >
                                <option value="">All statuses</option>
                                <option value="Planning">Planning</option>
                                <option value="In Progress">In Progress</option>
                                <option value="On Hold">On Hold</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label
                                for="priority-filter"
                                class="text-muted-foreground text-xs font-medium"
                            >
                                Priority
                            </label>

                            <select
                                id="priority-filter"
                                v-model="filters.priority"
                                class="bg-background w-full rounded-md border px-3 py-2 text-sm"
                            >
                                <option value="">All priorities</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sorting -->
                <div class="space-y-3">
                    <div>
                        <h3 class="text-sm font-medium">Sort</h3>

                        <p class="text-muted-foreground text-xs">
                            Choose how projects are ordered.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label
                                for="sort-by"
                                class="text-muted-foreground text-xs font-medium"
                            >
                                Sort by
                            </label>

                            <select
                                id="sort-by"
                                v-model="filters.sort_by"
                                class="bg-background w-full rounded-md border px-3 py-2 text-sm"
                            >
                                <option value="created_at">Created Date</option>
                                <option value="client_name">Client Name</option>
                                <option value="project_name">
                                    Project Name
                                </option>
                                <option value="start_date">Start Date</option>
                                <option value="due_date">Due Date</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label
                                for="sort-direction"
                                class="text-muted-foreground text-xs font-medium"
                            >
                                Direction
                            </label>

                            <select
                                id="sort-direction"
                                v-model="filters.sort_direction"
                                class="bg-background w-full rounded-md border px-3 py-2 text-sm"
                            >
                                <option value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex flex-col-reverse gap-2 border-t pt-4 sm:flex-row sm:justify-end"
            >
                <button
                    type="button"
                    class="rounded-md border px-4 py-2 text-sm"
                    @click="resetFilters"
                >
                    Reset
                </button>

                <button
                    type="submit"
                    class="bg-primary text-primary-foreground rounded-md px-4 py-2 text-sm"
                >
                    Apply Filters
                </button>
            </div>
        </div>
    </form>
</template>
