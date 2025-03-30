@extends('admin.layout.app')

@section('content')
<div class="container mt-3">
    <h1>Subjects</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
            Add Subject
        </a>
    </div>

    @if($subjects->count() > 0)
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Units</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($subjects as $subj)
                <tr>
                    <td>{{ $subj->id }}</td>
                    <td>{{ $subj->code }}</td>
                    <td>{{ $subj->name }}</td>
                    <td>{{ $subj->units }}</td>
                    <td>{{ $subj->description }}</td>
                    <td>
                        <a href="{{ route('admin.subjects.edit', $subj->id) }}"
                           class="btn btn-sm btn-warning">
                            Edit
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No subjects found.</p>
    @endif
</div>
@endsection
