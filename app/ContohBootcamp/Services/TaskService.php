<?php

namespace App\ContohBootcamp\Services;

use App\ContohBootcamp\Repositories\TaskRepository;

class TaskService {
	private TaskRepository $taskRepository;

	public function __construct() {
		$this->taskRepository = new TaskRepository();
	}

	/**
	 * NOTE: untuk mengambil semua tasks di collection task
	 */
	public function getTasks()
	{
		$tasks = $this->taskRepository->getAll();
		return $tasks;
	}

	/**
	 * NOTE: menambahkan task
	 */
	public function addTask(array $data)
	{
		$taskId = $this->taskRepository->create($data);
		return $taskId;
	}

	/**
	 * NOTE: UNTUK mengambil data task
	 */
	public function getById(string $taskId)
	{
		$task = $this->taskRepository->getById($taskId);
		return $task;
	}

	/**
	 * NOTE: untuk update task
	 */
	public function updateTask(array $editTask, array $formData)
	{
		if(isset($formData['title']))
		{
			$editTask['title'] = $formData['title'];
		}

		if(isset($formData['description']))
		{
			$editTask['description'] = $formData['description'];
		}

		$id = $this->taskRepository->save( $editTask);
		return $id;
	}
    public function deleteTask(string $taskId)
    {
        if(!$taskId)
		{
			return response()->json([
				"message"=> "Task ".$taskId." tidak ada"
			], 401);
		}
        $task = $this->taskRepository->delete($taskId);
        return $task;
    }
    public function assignTask(string $taskId, array $data)
    {
        if(!$taskId)
		{
            return response()->json([
                "message"=> "Task ".$taskId." tidak ada"
			], 401);
		}
        $task = $this->taskRepository->assign($taskId, $data);
        return $task;
    }
    public function unassignTask(string $taskId)
    {
        if(!$taskId)
		{
            return response()->json([
                "message"=> "Task ".$taskId." tidak ada"
			], 401);
		}
        $task = $this->taskRepository->unassign($taskId);
        return $task;
    }
    public function create_subTask(string $taskId, array $data)
    {
        if(!$taskId)
		{
			return response()->json([
				"message"=> "Task ".$taskId." tidak ada"
			], 401);
		}
        $task = $this->taskRepository->create_subtask($taskId, $data);
        return $task;
    }
    public function delete_subTask(string $taskId, string $subtaskId)
    {
        if(!$taskId)
		{
			return response()->json([
				"message"=> "Task ".$taskId." tidak ada"
			], 401);
		}

        $task = $this->taskRepository->delete_subtask($taskId, $subtaskId);
        return $task;
    }
}
