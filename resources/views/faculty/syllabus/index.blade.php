@extends('admin.layout.app')
{{-- If you have a dedicated faculty layout, use that instead of admin.layout.app --}}

@section('content')
<div class="container mt-3">
    <h1 class="mb-3">Uploaded Syllabi</h1>

    {{-- Upload Syllabus button --}}
    @if(isset($section, $subject, $schoolYear, $semester))
        <div class="mb-3">
            <a href="{{ route('faculty.syllabus.upload', [
                'sectionId' => $section->id,
                'subjectId' => $subject->id,
                'schoolYear' => $schoolYear,
                'semester' => $semester
            ]) }}" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload Syllabus
            </a>
        </div>
    @endif

    {{-- Success and error messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Syllabi table --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Syllabi</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Code</th>
                        <th>School Year</th>
                        <th>Semester</th>
                        <th>Filename</th>
                        <th>Upload Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($syllabi) && $syllabi->count() > 0)
                        @foreach($syllabi as $syllabus)
                            <tr>
                                <td>{{ $syllabus->subject_name }}</td>
                                <td>{{ $syllabus->subject_code }}</td>
                                <td>{{ $syllabus->school_year }}</td>
                                <td>{{ $syllabus->semester }}</td>
                                <td>{{ $syllabus->original_filename }}</td>
                                <td>{{ date('M d, Y h:i A', strtotime($syllabus->upload_timestamp)) }}</td>
                                <td>
                                    <a href="{{ route('faculty.syllabus.download', ['id' => $syllabus->id]) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">No syllabi uploaded yet</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Include custom file input library (if needed for your upload form) -->
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input@1.3.4/dist/bs-custom-file-input.min.js"></script>
<script>
    $(function() {
        bsCustomFileInput.init();
        $('.table').addClass('table-hover');
    });
</script>
@endsection
