@extends('admin.layout.app')

@section('content')
<div class="container mt-3">
    <h1>Add New Subject</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.subjects.store') }}" method="POST">
        @csrf

        <!-- Code -->
        <div class="form-group">
            <label for="code">Subject Code</label>
            <input type="text"
                   name="code"
                   id="code"
                   class="form-control"
                   value="{{ old('code') }}">
        </div>

        <!-- Name -->
        <div class="form-group">
            <label for="name">Subject Name</label>
            <input type="text"
                   name="name"
                   id="name"
                   class="form-control"
                   value="{{ old('name') }}"
                   required>
        </div>

        <!-- Units -->
        <div class="form-group">
            <label for="units">Units</label>
            <input type="number"
                   name="units"
                   id="units"
                   class="form-control"
                   value="{{ old('units') }}">
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description"
                      id="description"
                      class="form-control"
                      rows="4">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Save Subject
        </button>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">
            Cancel
        </a>
    </form>
</div>
@endsection
