<?php
namespace App\ContohBootcamp\Repositories;

use App\Helpers\MongoModel;

class TaskRepository
{
	private MongoModel $tasks;
	public function __construct()
	{
		$this->tasks = new MongoModel('tasks');
	}

	/**
	 * Untuk mengambil semua tasks
	 */
	public function getAll()
	{
		$tasks = $this->tasks->get([]);
		return $tasks;
	}

	/**
	 * Untuk mendapatkan task bedasarkan id
	 *  */
	public function getById(string $id)
	{
		$task = $this->tasks->find(['_id'=>$id]);
		return $task;
	}

	/**
	 * Untuk membuat task
	 */
	public function create(array $data)
	{
		$dataSaved = [
			'title'=>$data['title'],
			'description'=>$data['description'],
			'assigned'=>null,
			'subtasks'=> [],
			'created_at'=>time()
		];

		$id = $this->tasks->save($dataSaved);
		return $id;
	}

	/**
	 * Untuk menyimpan task baik untuk membuat baru atau menyimpan dengan struktur bson secara bebas
	 *  */
	public function save(array $editedData)
	{
		$id = $this->tasks->save($editedData);
		return $id;
	}
	public function delete(string $taskId)
	{
        $id = $this->tasks->deleteQuery(['_id'=>$taskId]);
		return $id;
	}
    public function assign(string $taskId, array $data)
    {
        $existTask = $this->tasks->find(['_id'=>$taskId]);
        $existTask['assigned'] = $data;
        $id = $this->tasks->save($existTask);
        return $id;
    }
    public function unassign(string $taskId)
    {
        $existTask = $this->tasks->find(['_id'=>$taskId]);
        $existTask['assigned'] = 'null';
        $id = $this->tasks->save($existTask);
        return $id;
    }
    public function create_subtask(string $taskId, array $data)
    {
        $title = $data['title'];
        $description = $data['description'];
        $existTask = $this->tasks->find(['_id'=>$taskId]);
        $subtasks = isset($existTask['subtasks']) ? $existTask['subtasks'] : [];
		$subtasks[] = [
			'_id'=> (string) new \MongoDB\BSON\ObjectId(),
			'title'=>$title,
			'description'=>$description
		];

		$existTask['subtasks'] = $subtasks;
        $id = $this->tasks->save($existTask);
        return $id;
    }
    public function delete_subtask(string $taskId, string $subtaskId)
    {
        $existTask = $this->tasks->find(['_id'=>$taskId]);
        $subtasks = isset($existTask['subtasks']) ? $existTask['subtasks'] : [];

		// Pencarian dan penghapusan subtask
		$subtasks = array_filter($subtasks, function($subtask) use($subtaskId) {
			if($subtask['_id'] == $subtaskId)
			{
				return false;
			} else {
				return true;
			}
		});
		$subtasks = array_values($subtasks);
		$existTask['subtasks'] = $subtasks;
        $id = $this->tasks->save($existTask);
        return $id;
    }
}
