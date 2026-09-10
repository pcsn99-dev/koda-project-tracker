<script setup lang="ts">
import type {
    Project,
    ProjectFormData,
    ProjectResponse,
} from '@/types/project';
import { useHttp } from '@inertiajs/vue3';
import { ref } from 'vue';

const emit = defineEmits<{
    (e: 'created', project: Project): void;
    (e: 'cancel'): void;
}>();

const submitError = ref<string | null>(null);

const form = useHttp<ProjectFormData, ProjectResponse>({
    client_name: '',
    project_name: '',
    description: '',
    status: 'Planning',
    priority: 'Medium',
    start_date: '',
    due_date: '',
});

async function submit() {
    submitError.value = null;

    await form.post('/projects', {
        onSuccess: (response) => {
            emit('created', response.data);
        },

        onHttpException: () => {
            submitError.value = 'Unable to create project.';
        },

        onNetworkError: () => {
            submitError.value = 'Unable to connect to the server.';
        },
    });
}
</script>

<template>
    <div class="rounded-xl border p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold">Create Project</h2>

            <p class="text-muted-foreground text-sm">
                Add a new client project.
            </p>
        </div>

        <form class="space-y-6" @submit.prevent="submit">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="client_name" class="text-sm font-medium">
                        Client Name
                    </label>

                    <input
                        id="client_name"
                        v-model="form.client_name"
                        type="text"
                        class="bg-background w-full rounded-md border px-3 py-2 text-sm"
                    />

                    <p
                        v-if="form.errors.client_name"
                        class="text-destructive text-sm"
                    >
                        {{ form.errors.client_name }}
                    </p>
                </div>

                <div class="space-y-2">
                    <label for="project_name" class="text-sm font-medium">
                        Project Name
                    </label>

                    <input
                        id="project_name"
                        v-model="form.project_name"
                        type="text"
                        class="bg-background w-full rounded-md border px-3 py-2 text-sm"
                    />

                    <p
                        v-if="form.errors.project_name"
                        class="text-destructive text-sm"
                    >
                        {{ form.errors.project_name }}
                    </p>
                </div>
            </div>

            <div class="space-y-2">
                <label for="description" class="text-sm font-medium">
                    Description
                </label>

                <textarea
                    id="description"
                    v-model="form.description"
                    rows="3"
                    class="bg-background w-full rounded-md border px-3 py-2 text-sm"
                />

                <p
                    v-if="form.errors.description"
                    class="text-destructive text-sm"
                >
                    {{ form.errors.description }}
                </p>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="status" class="text-sm font-medium">
                        Status
                    </label>

                    <select
                        id="status"
                        v-model="form.status"
                        class="bg-background w-full rounded-md border px-3 py-2 text-sm"
                    >
                        <option value="Planning">Planning</option>
                        <option value="In Progress">In Progress</option>
                        <option value="On Hold">On Hold</option>
                        <option value="Completed">Completed</option>
                    </select>

                    <p
                        v-if="form.errors.status"
                        class="text-destructive text-sm"
                    >
                        {{ form.errors.status }}
                    </p>
                </div>

                <div class="space-y-2">
                    <label for="priority" class="text-sm font-medium">
                        Priority
                    </label>

                    <select
                        id="priority"
                        v-model="form.priority"
                        class="bg-background w-full rounded-md border px-3 py-2 text-sm"
                    >
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>

                    <p
                        v-if="form.errors.priority"
                        class="text-destructive text-sm"
                    >
                        {{ form.errors.priority }}
                    </p>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="start_date" class="text-sm font-medium">
                        Start Date
                    </label>

                    <input
                        id="start_date"
                        v-model="form.start_date"
                        type="date"
                        class="bg-background w-full rounded-md border px-3 py-2 text-sm"
                    />

                    <p
                        v-if="form.errors.start_date"
                        class="text-destructive text-sm"
                    >
                        {{ form.errors.start_date }}
                    </p>
                </div>

                <div class="space-y-2">
                    <label for="due_date" class="text-sm font-medium">
                        Due Date
                    </label>

                    <input
                        id="due_date"
                        v-model="form.due_date"
                        type="date"
                        class="bg-background w-full rounded-md border px-3 py-2 text-sm"
                    />

                    <p
                        v-if="form.errors.due_date"
                        class="text-destructive text-sm"
                    >
                        {{ form.errors.due_date }}
                    </p>
                </div>
            </div>

            <p v-if="submitError" class="text-destructive text-sm">
                {{ submitError }}
            </p>

            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    class="rounded-md border px-4 py-2 text-sm"
                    :disabled="form.processing"
                    @click="emit('cancel')"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="bg-primary text-primary-foreground rounded-md px-4 py-2 text-sm"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Creating...' : 'Create Project' }}
                </button>
            </div>
        </form>
    </div>
</template>
