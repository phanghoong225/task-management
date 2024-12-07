<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskCreationRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //

    public function index(Request $request)
    {
        $taskQuery = Task::query();

        $this->applySearch($taskQuery, $request->search);
        $this->applySort($taskQuery, $request->sortType, $request->sortDirection);

        $tasks = TaskResource::collection($taskQuery->paginate(10));

        return inertia('Tasks/Index', [
            'tasks' => $tasks,
            'search' => $request->search ?? '',
            'sortType' => $request->sortType ?? '',
            'sortDirection' => $request->sortDirection ?? '',
            'page' => $request->sortDirection ?? '1',
        ]);
    }

    public function applySearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public function applySort($query, $sortType, $sortDirection)
    {
        return $query->when($sortType && $sortDirection, function ($query) use ($sortType, $sortDirection) {
            $query->orderBy($sortType, $sortDirection);
        });
    }

    public function create()
    {
        $users = UserResource::collection(User::all());

        return inertia('Tasks/Create', [
            'users' => $users
        ]);
    }

    public function store(TaskCreationRequest $request)
    {
        $validatedData = $request->validated();

        $dueDate = new \DateTime($validatedData['dueDate']);
        $status = $this->setstatus($dueDate);

        $validatedData['dueDate'] = $dueDate->format('Y-m-d');
        $validatedData['status'] = $status;
        $validatedData['workflow'] = 'New';


        Task::create($validatedData);

        return redirect()->route(
            'tasks.index'
        );
    }

    public function edit(Task $task)
    {
        $users = UserResource::collection(User::all());

        return inertia('Tasks/Edit', [
            'users' => $users,
            'task' => TaskResource::make($task)
        ]);
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {
        $validatedData = $request->validated();

        $dueDate = new \DateTime($validatedData['dueDate']);
        $status = $this->setstatus($dueDate);

        $validatedData['dueDate'] = $dueDate->format('Y-m-d');
        $validatedData['status'] = $status;

        $task->update($validatedData);

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index');
    }


    private function setstatus($dueDate)
    {
        $today = now()->settime(0, 0);
        $diff = date_diff($today, $dueDate, false);
        $isNegative = $diff->invert;

        $diffDay = $diff->d;

        if ($isNegative == 1) {
            $status = "Overdue";
        } else if ($diffDay >= 7) {
            $status = "Not Urgent";
        } else {
            $status = "Due soon";
        }
        return $status;
    }
}
