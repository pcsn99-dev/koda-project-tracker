<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});



function validProjectData(array $overrides = []): array
{
    return array_merge([
        'client_name' => 'Acme Corporation',
        'project_name' => 'Website Redesign',
        'description' => 'Redesign the corporate website.',
        'status' => 'Planning',
        'priority' => 'High',
        'start_date' => '2026-09-10',
        'due_date' => '2026-10-10',
    ], $overrides);


}



test('projects can be listed', function () {
    Project::factory()->count(3)->create();

    $this->getJson('/projects')
        ->assertOk()
        ->assertJsonCount(3, 'data');


});


test('a single project can be retrieved', function () {
    $project = Project::factory()->create();

    $this->getJson("/projects/{$project->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $project->id);


});

test('a project can be created', function () {
    $data = validProjectData();

    $this->postJson('/projects', $data)
        ->assertCreated()
        ->assertJsonPath('data.project_name', 'Website Redesign')
        ->assertJsonPath('data.status', 'Planning');

    $this->assertDatabaseHas('projects', [
        'client_name' => 'Acme Corporation',
        'project_name' => 'Website Redesign',
    ]);


});



test('a project can be updated', function () {

    $project = Project::factory()->create();

    $data = validProjectData([
        'project_name' => 'Updated Website',
        'status' => 'In Progress',
    ]);

    $this->putJson("/projects/{$project->id}", $data)
        ->assertOk()
        ->assertJsonPath('data.project_name', 'Updated Website')
        ->assertJsonPath('data.status', 'In Progress');

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'project_name' => 'Updated Website',
    ]);


});



test('a project can be deleted', function () {

    $project = Project::factory()->create();

    $this->deleteJson("/projects/{$project->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('projects', [
        'id' => $project->id,
    ]);


});




test('client and project names are required', function () {

    $this->postJson('/projects', validProjectData([
        'client_name' => '',
        'project_name' => '',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'client_name',
            'project_name',
        ]);


});


test('status must be valid', function () {

    $this->postJson('/projects', validProjectData([
        'status' => 'Definitely Not A Status',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors('status');



});


test('priority must be valid', function () {

    $this->postJson('/projects', validProjectData([
        'priority' => 'Critical',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors('priority');

});



test('due date cannot be earlier than start date', function () {

    $this->postJson('/projects', validProjectData([
        'start_date' => '2026-10-10',
        'due_date' => '2026-10-01',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors('due_date');


});


test('unauthenticated users cannot access projects', function () {

    auth()->logout();

    $this->getJson('/projects')
        ->assertUnauthorized();


});


test('projects can be searched by client or project name', function () {
    Project::factory()->create([
        'client_name' => 'Acme Corporation',
        'project_name' => 'Corporate Website',
    ]);

    Project::factory()->create([
        'client_name' => 'Other Company',
        'project_name' => 'Mobile Application',
    ]);

    $this->getJson('/projects?search=Acme')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.client_name', 'Acme Corporation');

    $this->getJson('/projects?search=Mobile')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.project_name', 'Mobile Application');
});


test('projects can be filtered by status', function () {
    Project::factory()->create([
        'status' => 'Planning',
    ]);

    Project::factory()->create([
        'status' => 'Completed',
    ]);

    $this->getJson('/projects?status=Completed')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.status', 'Completed');
});

test('projects can be filtered by priority', function () {
    Project::factory()->create([
        'priority' => 'High',
    ]);

    Project::factory()->create([
        'priority' => 'Low',
    ]);

    $this->getJson('/projects?priority=High')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.priority', 'High');
});



test('projects can be sorted', function () {
    Project::factory()->create([
        'project_name' => 'Later Project',
        'due_date' => '2026-12-01',
    ]);

    Project::factory()->create([
        'project_name' => 'Earlier Project',
        'due_date' => '2026-10-01',
    ]);

    $this->getJson(
        '/projects?sort_by=due_date&sort_direction=asc',
    )
        ->assertOk()
        ->assertJsonPath('data.0.project_name', 'Earlier Project')
        ->assertJsonPath('data.1.project_name', 'Later Project');
});

test('invalid project filters are rejected', function () {
    $this->getJson(
        '/projects?status=Invalid&sort_by=password&sort_direction=sideways',
    )
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'status',
            'sort_by',
            'sort_direction',
        ]);
});