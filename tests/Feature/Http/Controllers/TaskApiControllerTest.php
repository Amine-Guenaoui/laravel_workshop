<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskApiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexShouldReturnAllListOfTasks(): void
    {
        $tasks = Task::factory(2)->create();
        $response = $this->get('/api/tasks');
        $response->assertStatus(200);
        $response->assertJsonIsArray();
        $response->assertExactJson($tasks->toArray());
    }

    public function testShowShouldReturnTask(): void
    {
        $task = Task::factory()->create();
        $response = $this->get('/api/tasks/' . $task->id);
        $response->assertStatus(200);
        $response->assertExactJson($task->toArray());
    }

    public function testDestroyShouldDeleteTask(): void
    {
        $task = Task::factory()->create();
        $task = Task::select('id', 'name', 'detail')
            ->where('id', $task->id)->first();
        $this->assertDatabaseHas('tasks', $task->toArray());
        $response = $this->delete('/api/tasks/' . $task->id);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', $task->toArray());
    }

    public function testStoreShouldReturnCreatedTask(): void
    {
        $task = Task::factory()->make();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
        $response = $this->post(
            '/api/tasks',
            [
                'name' => $task->name,
                'detail' => $task->detail
            ]
        );
        $response->assertStatus(201);
        $this->assertDatabaseHas(
            'tasks',
            [
                'name' => $task->name,
                'detail' => $task->detail
            ]
        );
    }

    public function testupdateShouldReturnUpdatedTask(): void
    {
        $task = Task::factory()->create();
        $updatedTask = Task::factory()->make();
        $task = Task::select('id', 'name', 'detail')
            ->where('id', $task->id)->first();
        $this->assertDatabaseHas(
            'tasks',
            [
                'id' => $task->id,
                'name' => $task->name,
                'detail' => $task->detail
            ]
        );
        $response = $this->put(
            '/api/tasks/' . $task->id,
            [
                'name' => $updatedTask->name,
                'detail' => $updatedTask->detail
            ]
        );
        $response->assertStatus(200);
        $this->assertDatabaseHas(
            'tasks',
            [
                'id' => $task->id,
                'name' => $updatedTask->name,
                'detail' => $updatedTask->detail
            ]
        );
    }
}
