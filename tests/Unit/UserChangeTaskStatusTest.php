<?php

namespace Tests\Unit;

use App\Http\Controllers\TaskController;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Support\Str;

class UserChangeTaskStatusTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testUserChangeTaskStatusTest()
    {
        $user = User::create([
            'username' => 'user',
            'password' => Str::random('20'),
            'role' => 'team_member'
        ]);

        $project = Project::create([
            'name' => 'New Project'
        ]);

        $task = Task::create([
            'title' => 'new task',
            'description' => 'task description',
            'status' => 'not_started',
            'user_id' => $user->id,
            'project_id' => $project->id,
        ]);

        $taskController = new TaskController();
        $request = new Request(['status' => 'in_progress']);
        $response = $taskController->updateTaskByMember($request, $task);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertArrayHasKey('message', json_decode($response->getContent(), 200));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => $task->status
        ]);
    }
}
