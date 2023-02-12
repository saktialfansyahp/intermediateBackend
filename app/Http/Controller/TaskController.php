<?php

namespace App\Http\Controller;

use App\ContohBootcamp\Services\TaskService;
use App\Helpers\MongoModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller {
	private TaskService $taskService;
	public function __construct() {
		$this->taskService = new TaskService();
	}

	public function showTasks()
	{
		$tasks = $this->taskService->getTasks();
		return response()->json($tasks);
	}

	public function createTask(Request $request)
	{
		$request->validate([
			'title'=>'required|string|min:3',
			'description'=>'required|string'
		]);

		$data = [
			'title'=>$request->post('title'),
			'description'=>$request->post('description')
		];

		$dataSaved = [
			'title'=>$data['title'],
			'description'=>$data['description'],
			'assigned'=>null,
			'subtasks'=> [],
			'created_at'=>time()
		];

		$id = $this->taskService->addTask($dataSaved);
		$task = $this->taskService->getById($id);

		return response()->json($task);
	}


	public function updateTask(Request $request)
	{
		$request->validate([
			'task_id'=>'required|string',
			'title'=>'string',
			'description'=>'string',
			'assigned'=>'string',
			'subtasks'=>'array',
		]);

		$taskId = $request->post('task_id');
		$formData = $request->only('title', 'description', 'assigned', 'subtasks');
		$task = $this->taskService->getById($taskId);

		$this->taskService->updateTask($task, $formData);

		$task = $this->taskService->getById($taskId);

		return response()->json($task);
	}


	// TODO: deleteTask()
	public function deleteTask(Request $request)
	{
		$request->validate([
			'task_id'=>'required'
		]);

		$taskId = $request->task_id;

        $task = $this->taskService->deleteTask($taskId);

		return response()->json([
			'message'=> 'Success delete task '.$taskId
		]);
	}

	// TODO: assignTask()
	public function assignTask(Request $request)
	{
		$mongoTasks = new MongoModel('tasks');
		$request->validate([
			'task_id'=>'required',
			'assigned'=>'required'
		]);
		$taskId = $request->get('task_id');
		$data = [
            $request->post('assigned')
        ];

        $task = $this->taskService->assignTask($taskId, $data);

		$task = $mongoTasks->find(['_id'=>$taskId]);

		return response()->json($task);
	}

	// TODO: unassignTask()
	public function unassignTask(Request $request)
	{
        $mongoTasks = new MongoModel('tasks');
		$request->validate([
			'task_id'=>'required'
		]);
		$taskId = $request->get('task_id');

        $task = $this->taskService->unassignTask($taskId);

		$task = $mongoTasks->find(['_id'=>$taskId]);

		return response()->json($task);
	}

	// TODO: createSubtask()
	public function createSubtask(Request $request)
	{
		$mongoTasks = new MongoModel('tasks');
		$request->validate([
			'task_id'=>'required',
			'title'=>'required|string',
			'description'=>'required|string'
		]);

        $taskId = $request->post('task_id');
        $data = [
            'title' => $request->post('title'),
            'description' => $request->post('description')
        ];

        $task = $this->taskService->create_subTask($taskId, $data);
		$task = $mongoTasks->find(['_id'=>$taskId]);

		return response()->json($task);
	}

	// TODO deleteSubTask()
	public function deleteSubtask(Request $request)
	{
		$mongoTasks = new MongoModel('tasks');
		$request->validate([
			'task_id'=>'required',
			'subtask_id'=>'required'
		]);
        $taskId = $request->post('task_id');
        $subtaskId = $request->post('subtask_id');

		$task = $this->taskService->delete_subTask($taskId, $subtaskId);

		$task = $mongoTasks->find(['_id'=>$taskId]);

		return response()->json($task);
	}

}
