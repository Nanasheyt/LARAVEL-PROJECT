@extends('layouts.app')

@section('content')
    <div class="card">
        <h2>Edit Task</h2>

        @if ($errors->any())
            <div class="alert-success" style="background:#fed7d7;color:#742a2a;">
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Task Name</label>
            <input type="text" name="task_name" value="{{ old('task_name', $task->task_name) }}" required>

            <label>Description</label>
            <textarea name="description" rows="4">{{ old('description', $task->description) }}</textarea>

            <label>Due Date</label>
            <input type="date" name="due_date"
                   value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">

            <label>Status</label>
            <select name="status">
                <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Completed" {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>

            <button type="submit" class="btn btn-primary">Update Task</button>
            <a href="{{ route('tasks.index') }}" class="btn">Cancel</a>
        </form>
    </div>
@endsection
