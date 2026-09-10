<script setup lang="ts">
import CreateProjectForm from '@/components/projects/CreateProjectForm.vue';
import EditProjectForm from '@/components/projects/EditProjectForm.vue';
import ProjectTable from '@/components/projects/ProjectTable.vue';
import type { Project, ProjectCollectionResponse } from '@/types/project';
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

function handleProjectCreated(project: Project) {
    projects.value.unshift(project);
    showCreateForm.value = false;
}

function handleProjectUpdated(project: Project) {
    const index = projects.value.findIndex((item) => item.id === project.id);

    if (index !== -1) {
        projects.value[index] = project;
    }

    selectedProject.value = null;
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
        onSuccess: () => {
            projects.value = projects.value.filter(
                (item) => item.id !== project.id,
            );

            if (selectedProject.value?.id === project.id) {
                selectedProject.value = null;
            }
        },

        onHttpException: () => {
            deleteError.value = 'Unable to delete project.';
        },

        onNetworkError: () => {
            deleteError.value = 'Unable to connect to the server.';
        },
    });
}

async function loadProjects() {
    loadError.value = null;

    await projectsRequest.get('/projects', {
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

        <div
            v-if="projectsRequest.processing"
            class="text-muted-foreground py-10 text-center text-sm"
        >
            Loading projects...
        </div>

        <div v-else-if="loadError" class="rounded-lg border p-4 text-sm">
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
