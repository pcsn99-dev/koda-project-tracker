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