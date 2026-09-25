@extends('layouts.app')

@section('content')
    <div class="card">
        <h2>My Tasks</h2>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">+ Add Task</a>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Task Name</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- Loop through every task and render a row --}}
                @forelse ($tasks as $task)
                    <tr>
                        <td>{{ $task->task_name }}</td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}</td>
                        <td>
                            <span class="{{ $task->status === 'Pending' ? 'status-pending' : 'status-completed' }}">
                                {{ $task->status }}
                            </span>
                        </td>
                        <td>
                            {{-- Toggle Pending/Completed --}}
                            <form class="inline" action="{{ route('tasks.updateStatus', $task) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-status">
                                    Mark {{ $task->status === 'Pending' ? 'Completed' : 'Pending' }}
                                </button>
                            </form>

                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-edit">Edit</a>

                            {{-- Delete task, with a confirmation prompt --}}
                            <form class="inline" action="{{ route('tasks.destroy', $task) }}" method="POST"
                                  onsubmit="return confirm('Delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No tasks yet. Click "Add Task" to create one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
