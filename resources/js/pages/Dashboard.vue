<script setup lang="ts">
import CreateProjectForm from '@/components/projects/CreateProjectForm.vue';
import EditProjectForm from '@/components/projects/EditProjectForm.vue';
import ProjectFilters from '@/components/projects/ProjectFilters.vue';
import ProjectTable from '@/components/projects/ProjectTable.vue';
import type {
    Project,
    ProjectCollectionResponse,
    ProjectFiltersState,
} from '@/types/project';
import { Head, useHttp } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Projects',
                href: '/dashboard',
            },
        ],
    },
});

const projects = ref<Project[]>([]);
const selectedProject = ref<Project | null>(null);

const showCreateForm = ref(false);

const loadError = ref<string | null>(null);
const deleteError = ref<string | null>(null);

const activeFilters = ref<ProjectFiltersState>({
    search: '',
    status: '',
    priority: '',
    sort_by: 'created_at',
    sort_direction: 'desc',
});

const projectsRequest = useHttp<
    Record<string, never>,
    ProjectCollectionResponse
>({});

const deleteRequest = useHttp({});

function startCreating() {
    selectedProject.value = null;
    showCreateForm.value = true;
}

function startEditing(project: Project) {
    showCreateForm.value = false;
    selectedProject.value = project;
}

async function handleProjectCreated() {
    showCreateForm.value = false;

    await loadProjects();
}

async function handleProjectUpdated() {
    selectedProject.value = null;

    await loadProjects();
}

async function deleteProject(project: Project) {
    const confirmed = window.confirm(
        `Are you sure you want to delete "${project.project_name}"?`,
    );

    if (!confirmed) {
        return;
    }

    deleteError.value = null;

    await deleteRequest.delete(`/projects/${project.id}`, {
        onSuccess: async () => {
            if (selectedProject.value?.id === project.id) {
                selectedProject.value = null;
            }

            await loadProjects();
        },

        onHttpException: () => {
            deleteError.value = 'Unable to delete project.';
        },

        onNetworkError: () => {
            deleteError.value = 'Unable to connect to the server.';
        },
    });
}

async function loadProjects(filters?: ProjectFiltersState) {
    if (filters) {
        activeFilters.value = { ...filters };
    }

    loadError.value = null;

    const params = new URLSearchParams();
    const current = activeFilters.value;

    if (current.search.trim()) {
        params.set('search', current.search.trim());
    }

    if (current.status) {
        params.set('status', current.status);
    }

    if (current.priority) {
        params.set('priority', current.priority);
    }

    params.set('sort_by', current.sort_by);
    params.set('sort_direction', current.sort_direction);

    await projectsRequest.get(`/projects?${params.toString()}`, {
        onSuccess: (response) => {
            projects.value = response.data;
        },

        onHttpException: () => {
            loadError.value = 'Unable to load projects.';
        },

        onNetworkError: () => {
            loadError.value = 'Unable to connect to the server.';
        },
    });
}

onMounted(loadProjects);
</script>

<template>
    <Head title="Projects" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">
                    Client Project Tracker
                </h1>

                <p class="mt-1 text-sm text-muted-foreground">
                    Manage and monitor client projects.
                </p>
            </div>

            <button
                v-if="!showCreateForm"
                type="button"
                class="rounded-md bg-primary px-4 py-2 text-sm text-primary-foreground"
                @click="startCreating"
            >
                Create Project
            </button>
        </div>

        <CreateProjectForm
            v-if="showCreateForm"
            @created="handleProjectCreated"
            @cancel="showCreateForm = false"
        />

        <EditProjectForm
            v-if="selectedProject"
            :key="selectedProject.id"
            :project="selectedProject"
            @updated="handleProjectUpdated"
            @cancel="selectedProject = null"
        />

        <div
            v-if="deleteError"
            class="rounded-lg border p-4 text-sm text-destructive"
        >
            {{ deleteError }}
        </div>

        <ProjectFilters @apply="loadProjects" />

        <div
            v-if="projectsRequest.processing"
            class="py-10 text-center text-sm text-muted-foreground"
        >
            Loading projects...
        </div>

        <div
            v-else-if="loadError"
            class="rounded-lg border p-4 text-sm"
        >
            {{ loadError }}
        </div>

        <ProjectTable
            v-else
            :projects="projects"
            :deleting="deleteRequest.processing"
            @edit="startEditing"
            @delete="deleteProject"
        />
    </div>
</template>