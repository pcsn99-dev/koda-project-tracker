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
    <form
        class="grid gap-3 rounded-xl border p-4 lg:grid-cols-6"
        @submit.prevent="applyFilters"
    >
        <input
            v-model="filters.search"
            type="search"
            placeholder="Search client or project..."
            class="rounded-md border bg-background px-3 py-2 text-sm lg:col-span-2"
        >

        <select
            v-model="filters.status"
            class="rounded-md border bg-background px-3 py-2 text-sm"
        >
            <option value="">All statuses</option>
            <option value="Planning">Planning</option>
            <option value="In Progress">In Progress</option>
            <option value="On Hold">On Hold</option>
            <option value="Completed">Completed</option>
        </select>

        <select
            v-model="filters.priority"
            class="rounded-md border bg-background px-3 py-2 text-sm"
        >
            <option value="">All priorities</option>
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
        </select>

        <select
            v-model="filters.sort_by"
            class="rounded-md border bg-background px-3 py-2 text-sm"
        >
            <option value="created_at">Created Date</option>
            <option value="client_name">Client Name</option>
            <option value="project_name">Project Name</option>
            <option value="start_date">Start Date</option>
            <option value="due_date">Due Date</option>
        </select>

        <select
            v-model="filters.sort_direction"
            class="rounded-md border bg-background px-3 py-2 text-sm"
        >
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
        </select>

        <div class="flex gap-2 lg:col-span-6">
            <button
                type="submit"
                class="rounded-md bg-primary px-4 py-2 text-sm text-primary-foreground"
            >
                Apply
            </button>

            <button
                type="button"
                class="rounded-md border px-4 py-2 text-sm"
                @click="resetFilters"
            >
                Reset
            </button>
        </div>
    </form>
</template>