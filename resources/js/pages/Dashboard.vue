<script setup lang="ts">
import CreateProjectForm from '@/components/projects/CreateProjectForm.vue';
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

const showCreateForm = ref(false);

const projects = ref<Project[]>([]);
const loadError = ref<string | null>(null);

const projectsRequest = useHttp<
    Record<string, never>,
    ProjectCollectionResponse
>({});

function handleProjectCreated(project: Project) {
    projects.value.unshift(project);
    showCreateForm.value = false;
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
                @click="showCreateForm = true"
            >
                Create Project
            </button>
        </div>

        <CreateProjectForm
            v-if="showCreateForm"
            @created="handleProjectCreated"
            @cancel="showCreateForm = false"
        />

        <div
            v-if="projectsRequest.processing"
            class="text-muted-foreground py-10 text-center text-sm"
        >
            Loading projects...
        </div>

        <div v-else-if="loadError" class="rounded-lg border p-4 text-sm">
            {{ loadError }}
        </div>

        <ProjectTable v-else :projects="projects" />
    </div>
</template>
