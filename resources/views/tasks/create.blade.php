@extends('layouts.app')

@section('content')
    <div class="card">
        <h2>Add Task</h2>

        {{-- Show validation errors, if any --}}
        @if ($errors->any())
            <div class="alert-success" style="background:#fed7d7;color:#742a2a;">
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf

            <label>Task Name</label>
            <input type="text" name="task_name" value="{{ old('task_name') }}" required>

            <label>Description</label>
            <textarea name="description" rows="4">{{ old('description') }}</textarea>

            <label>Due Date</label>
            <input type="date" name="due_date" value="{{ old('due_date') }}">

            <label>Status</label>
            <select name="status">
                <option value="Pending" selected>Pending</option>
                <option value="Completed">Completed</option>
            </select>

            <button type="submit" class="btn btn-primary">Save Task</button>
            <a href="{{ route('tasks.index') }}" class="btn">Cancel</a>
        </form>
    </div>
@endsection
