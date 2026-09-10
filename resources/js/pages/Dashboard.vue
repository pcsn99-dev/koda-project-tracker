<script setup lang="ts">
import ProjectTable from '@/components/projects/ProjectTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type {Project, ProjectCollectionResponse, } from '@/types/project';
import { Head, useHttp } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';





const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Projects',
        href: '/dashboard',
    },
];


const projects = ref<Project[]>([]);

const loadError = ref<string | null>(null);


const projectsRequest = useHttp<
    Record<string, never>,
    ProjectCollectionResponse
>({});



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

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">
                    Client Project Tracker
                </h1>

                <p class="mt-1 text-sm text-muted-foreground">
                    Manage and monitor client projects.
                </p>
            </div>

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
            />
        </div>

    </AppLayout>


    
</template>