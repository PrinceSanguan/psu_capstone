@extends('admin.layout.app')

@section('content')
<div class="container mt-3">
    <h1>Teacher Syllabi Uploads</h1>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Faculty Name</th>
                <th>Subject</th>
                <th>File</th>
                <th>Uploaded At</th>
            </tr>
        </thead>
        <tbody>
        @forelse($syllabi as $syl)
            <tr>
                <td>{{ $syl->faculty_name }}</td>
                <td>{{ $syl->subject_code ?? 'N/A' }} - {{ $syl->subject_name }}</td>
                <td>{{ $syl->original_filename ?? 'File' }}</td>
                <td>{{ $syl->upload_timestamp }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No syllabus uploads found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
