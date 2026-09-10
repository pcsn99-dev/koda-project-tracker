<script setup lang="ts">
import CreateProjectForm from '@/components/projects/CreateProjectForm.vue';
import EditProjectForm from '@/components/projects/EditProjectForm.vue';
import ProjectFilters from '@/components/projects/ProjectFilters.vue';
import ProjectPagination from '@/components/projects/ProjectPagination.vue';
import ProjectTable from '@/components/projects/ProjectTable.vue';
import type {
    PaginationMeta,
    Project,
    ProjectCollectionResponse,
    ProjectFiltersState,
} from '@/types/project';
import { Head, useHttp } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import DeleteProjectDialog from '@/components/projects/DeleteProjectDialog.vue';

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

const projectToDelete = ref<Project | null>(null);
const projects = ref<Project[]>([]);
const selectedProject = ref<Project | null>(null);
const pagination = ref<PaginationMeta | null>(null);

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

function applyFilters(filters: ProjectFiltersState) {
    void loadProjects(filters, 1);
}

function changePage(page: number) {
    void loadProjects(undefined, page);
}

function requestDelete(project: Project) {
    projectToDelete.value = project;
}

async function confirmDelete() {
    const project = projectToDelete.value;

    if (!project) {
        return;
    }

    deleteError.value = null;

    await deleteRequest.delete(`/projects/${project.id}`, {
        onSuccess: async () => {
            if (selectedProject.value?.id === project.id) {
                selectedProject.value = null;
            }

            projectToDelete.value = null;

            let page = pagination.value?.current_page ?? 1;

            if (projects.value.length === 1 && page > 1) {
                page -= 1;
            }

            await loadProjects(undefined, page);
        },

        onHttpException: () => {
            deleteError.value = 'Unable to delete project.';
        },

        onNetworkError: () => {
            deleteError.value = 'Unable to connect to the server.';
        },
    });
}

async function loadProjects(
    filters?: ProjectFiltersState,
    page = pagination.value?.current_page ?? 1,
) {
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
    params.set('page', page.toString());

    await projectsRequest.get(`/projects?${params.toString()}`, {
        onSuccess: (response) => {
            projects.value = response.data;
            pagination.value = response.meta;
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

                <p class="text-muted-foreground mt-1 text-sm">
                    Manage and monitor client projects.
                </p>
            </div>

            <button
                v-if="!showCreateForm"
                type="button"
                class="bg-primary text-primary-foreground rounded-md px-4 py-2 text-sm"
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
            class="text-destructive rounded-lg border p-4 text-sm"
        >
            {{ deleteError }}
        </div>

        <ProjectFilters @apply="applyFilters" />

        <div
            v-if="projectsRequest.processing"
            class="text-muted-foreground py-10 text-center text-sm"
        >
            Loading projects...
        </div>

        <div v-else-if="loadError" class="rounded-lg border p-4 text-sm">
            {{ loadError }}
        </div>

        <template v-else>
            <ProjectTable
                :projects="projects"
                :deleting="deleteRequest.processing"
                @edit="startEditing"
                @delete="requestDelete"
            />

            <ProjectPagination
                v-if="pagination"
                :meta="pagination"
                :loading="projectsRequest.processing"
                @change="changePage"
            />
        </template>
    </div>

    <DeleteProjectDialog
        v-if="projectToDelete"
        :project="projectToDelete"
        :processing="deleteRequest.processing"
        @confirm="confirmDelete"
        @cancel="projectToDelete = null"
    />
</template>
