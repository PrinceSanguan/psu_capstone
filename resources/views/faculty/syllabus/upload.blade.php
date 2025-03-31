@extends('faculty.layout.app')
{{-- If you use a dedicated faculty layout, use that instead of admin.layout.app --}}

@section('content')
<div class="container mt-3">
    <h1 class="mb-3">Upload Syllabus</h1>

    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('faculty.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('faculty.classes.index') }}">My Classes</a></li>
        <li class="breadcrumb-item">
            <a href="{{ route('faculty.classes.details', [
                'sectionId' => $section->id,
                'subjectId' => $subject->id,
                'schoolYear' => $schoolYear,
                'semester' => $semester
            ]) }}">{{ $subject->code }}</a>
        </li>
        <li class="breadcrumb-item active">Upload Syllabus</li>
    </ol>

    <!-- Success & Error Alerts -->
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

    <div class="row">
        <!-- Left Column: Class Info & Existing Syllabus -->
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Class Information</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <strong>Section:</strong>
                            <span class="float-right">{{ $section->name }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Subject:</strong>
                            <span class="float-right">{{ $subject->name }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Code:</strong>
                            <span class="float-right">{{ $subject->code }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>School Year:</strong>
                            <span class="float-right">{{ $schoolYear }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Semester:</strong>
                            <span class="float-right">{{ $semester }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            @if(isset($existingSyllabus))
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Existing Syllabus</h3>
                </div>
                <div class="card-body">
                    <p><strong>Filename:</strong> {{ $existingSyllabus->original_filename }}</p>
                    <p>
                        <strong>Uploaded:</strong>
                        {{ date('F d, Y h:i A', strtotime($existingSyllabus->upload_timestamp)) }}
                    </p>
                    <div class="text-center">
                        <a href="{{ route('faculty.syllabus.download', ['id' => $existingSyllabus->id]) }}"
                           class="btn btn-info btn-sm">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                    <div class="mt-3">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Uploading a new syllabus will replace the existing one.
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Upload Form -->
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Upload Syllabus</h3>
                </div>
                <form action="{{ route('faculty.syllabus.store', [
                    'sectionId' => $section->id,
                    'subjectId' => $subject->id,
                    'schoolYear' => $schoolYear,
                    'semester' => $semester
                ]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="syllabus_file">Syllabus File (PDF, DOC, DOCX)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="syllabus_file" name="syllabus_file" required>
                                    <label class="custom-file-label" for="syllabus_file">Choose file</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">Maximum file size: 10MB</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Upload Syllabus</button>
                        <a href="{{ route('faculty.classes.details', [
                            'sectionId' => $section->id,
                            'subjectId' => $subject->id,
                            'schoolYear' => $schoolYear,
                            'semester' => $semester
                        ]) }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>

            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Guidelines for Syllabus Upload</h3>
                </div>
                <div class="card-body">
                    <p>Please ensure your syllabus includes the following:</p>
                    <ul>
                        <li>Course description and objectives</li>
                        <li>Grading system</li>
                        <li>Weekly schedule of topics</li>
                        <li>Required textbooks and references</li>
                        <li>Assessment methods</li>
                        <li>Classroom policies</li>
                        <li>Contact information</li>
                    </ul>
                    <p>Accepted file formats: PDF, DOC, DOCX</p>
                    <p>The uploaded syllabus will be accessible to students enrolled in this class.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Include bs-custom-file-input for custom file input labels -->
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input@1.3.4/dist/bs-custom-file-input.min.js"></script>
<script>
    $(function() {
        bsCustomFileInput.init();
    });
</script>
@endsection
