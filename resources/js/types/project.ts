export type ProjectStatus =
    | 'Planning'
    | 'In Progress'
    | 'On Hold'
    | 'Completed';

export type ProjectPriority = 'Low' | 'Medium' | 'High';

export interface Project {
    id: number;
    client_name: string;
    project_name: string;
    description: string | null;
    status: ProjectStatus;
    priority: ProjectPriority;
    start_date: string;
    due_date: string;
    created_at: string;
    updated_at: string;
}

export interface ProjectCollectionResponse {
    data: Project[];
}

// form data sent to backend
export interface ProjectFormData {
    client_name: string;
    project_name: string;
    description: string;
    status: ProjectStatus | '';
    priority: ProjectPriority | '';
    start_date: string;
    due_date: string;
}

// returned after POST
export interface PaginationMeta {
    current_page: number;
    from: number | null;
    last_page: number;
    per_page: number;
    to: number | null;
    total: number;
}

export interface ProjectCollectionResponse {
    data: Project[];
    meta: PaginationMeta;
}

export type ProjectSortField =
    | 'client_name'
    | 'project_name'
    | 'start_date'
    | 'due_date'
    | 'created_at';

export type ProjectSortDirection = 'asc' | 'desc';

export interface ProjectFiltersState {
    search: string;
    status: ProjectStatus | '';
    priority: ProjectPriority | '';
    sort_by: ProjectSortField;
    sort_direction: ProjectSortDirection;
}
