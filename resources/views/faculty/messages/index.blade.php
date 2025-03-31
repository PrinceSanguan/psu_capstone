@extends('layouts.faculty')

@section('title', 'Messages')

@include('faculty.layout.sidebar')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Messages</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('faculty.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Messages</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card direct-chat direct-chat-primary">
            <div class="card-header">
                <h3 class="card-title">Student Conversations</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Contacts list -->
                    <div class="col-md-4 border-right">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="searchStudent" placeholder="Search student...">
                        </div>

                        <ul class="nav nav-tabs" id="messagesTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="recent-tab" data-toggle="tab" href="#recent" role="tab">Recent</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="all-tab" data-toggle="tab" href="#all" role="tab">All Students</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="messagesTabContent">
                            <!-- Recent conversations -->
                            <div class="tab-pane fade show active" id="recent" role="tabpanel">
                                <div class="list-group" id="recent-conversations">
                                    @forelse($conversationUsers as $user)
                                        <a href="#"
                                           class="list-group-item list-group-item-action user-conversation"
                                           data-user-id="{{ $user->id }}"
                                           data-user-name="{{ $user->name }}">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">{{ $user->name }}</h5>
                                            </div>
                                            <small>{{ $user->student_number ?? 'Student' }}</small>
                                        </a>
                                    @empty
                                        <div class="text-center py-3">
                                            <p class="text-muted">No recent conversations</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- All students -->
                            <div class="tab-pane fade" id="all" role="tabpanel">
                                <div class="list-group" id="all-students">
                                    @forelse($students as $student)
                                        <a href="#"
                                           class="list-group-item list-group-item-action user-conversation"
                                           data-user-id="{{ $student->student_id }}"
                                           data-user-name="{{ $student->student_name }}">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">{{ $student->student_name }}</h5>
                                            </div>
                                            <small>{{ $student->section_name }} - {{ $student->subject_code }}</small>
                                        </a>
                                    @empty
                                        <div class="text-center py-3">
                                            <p class="text-muted">No students found</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chat area -->
                    <div class="col-md-8">
                        <div id="conversation-area">
                            <div class="text-center py-5" id="no-conversation-selected">
                                <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                                <h4>Select a student to start a conversation</h4>
                                <p class="text-muted">Your messages with students will appear here</p>
                            </div>

                            <div id="active-conversation" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center p-3 bg-light">
                                    <h5 class="mb-0" id="conversation-title">Student Name</h5>
                                    <div>
                                        <button class="btn btn-sm btn-outline-secondary" id="view-student-info">
                                            <i class="fas fa-info-circle"></i> Student Info
                                        </button>
                                    </div>
                                </div>

                                <!-- Student info modal -->
                                <div class="modal fade" id="studentInfoModal" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Student Information</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" id="student-info-content">
                                                <!-- Student info will be loaded here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="direct-chat-messages p-3" id="conversation-messages" style="height: 400px; overflow-y: auto;">
                                    <!-- Messages will be loaded here -->
                                </div>

                                <div class="p-3 border-top">
                                    <form id="message-form">
                                        @csrf
                                        <input type="hidden" id="recipient-id" value="">
                                        <div class="input-group">
                                            <input type="text" id="message-input" class="form-control" placeholder="Type message...">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-paper-plane"></i> Send
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(function() {
        // Search functionality
        $('#searchStudent').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $('#all-students a').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Load conversation when user is clicked
        $('.user-conversation').on('click', function(e) {
            e.preventDefault();
            const userId = $(this).data('user-id');
            const userName = $(this).data('user-name');
            loadConversation(userId, userName);
        });

        // Load user information
        $('#view-student-info').on('click', function() {
            const userId = $('#recipient-id').val();
            $.ajax({
                url: `{{ route('faculty.messages.conversation', '') }}/${userId}`,
                method: 'GET',
                success: function(response) {
                    let infoHtml = `
                        <h6>${response.user.name}</h6>
                        <p><strong>Student Number:</strong> ${response.user.student_number || 'N/A'}</p>
                        <p><strong>Email:</strong> ${response.user.email || 'N/A'}</p>
                        <hr>
                        <h6>Enrolled Subjects:</h6>
                        <ul>
                    `;

                    if (response.enrolledSubjects && response.enrolledSubjects.length > 0) {
                        response.enrolledSubjects.forEach(subject => {
                            infoHtml += `<li>${subject.subject_code} - ${subject.subject_name} (${subject.school_year}, ${subject.semester})</li>`;
                        });
                    } else {
                        infoHtml += `<li>No enrolled subjects found</li>`;
                    }

                    infoHtml += `</ul>`;

                    $('#student-info-content').html(infoHtml);
                    $('#studentInfoModal').modal('show');
                },
                error: function(error) {
                    console.error(error);
                    toastr.error('Failed to load student information');
                }
            });
        });

        // Send message
        $('#message-form').on('submit', function(e) {
            e.preventDefault();
            const recipientId = $('#recipient-id').val();
            const message = $('#message-input').val().trim();

            if (!recipientId || !message) {
                return;
            }

            $.ajax({
                url: '{{ route("faculty.messages.send") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    recipient_id: recipientId,
                    message: message
                },
                success: function(response) {
                    if (response.success) {
                        $('#message-input').val('');
                        appendMessage(response.message, true);
                        scrollToBottom();
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error);
                    } else {
                        toastr.error('Failed to send message. Please try again.');
                    }
                }
            });
        });

        // Function to load conversation
        function loadConversation(userId, userName) {
            $.ajax({
                url: `{{ route('faculty.messages.conversation', '') }}/${userId}`,
                method: 'GET',
                success: function(response) {
                    $('#no-conversation-selected').hide();
                    $('#active-conversation').show();
                    $('#conversation-title').text(userName);
                    $('#recipient-id').val(userId);

                    // Clear previous messages
                    $('#conversation-messages').empty();

                    // Add messages
                    if (response.messages && response.messages.length > 0) {
                        response.messages.forEach(message => {
                            appendMessage(message, message.sender_id == {{ Auth::id() }});
                        });
                    } else {
                        $('#conversation-messages').html(`
                            <div class="text-center py-4">
                                <p class="text-muted">No messages yet. Start the conversation!</p>
                            </div>
                        `);
                    }

                    scrollToBottom();
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error);
                    } else {
                        toastr.error('Failed to load conversation. Please try again.');
                    }
                }
            });
        }

        // Function to append message to conversation
        function appendMessage(message, isOwn) {
            const timestamp = new Date(message.created_at).toLocaleString();
            let html = '';

            if (isOwn) {
                html = `
                    <div class="direct-chat-msg right mb-3">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-right">You</span>
                            <span class="direct-chat-timestamp float-left">${timestamp}</span>
                        </div>
                        <div class="direct-chat-text bg-primary text-white">
                            ${message.message}
                        </div>
                    </div>
                `;
            } else {
                html = `
                    <div class="direct-chat-msg mb-3">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-left">Student</span>
                            <span class="direct-chat-timestamp float-right">${timestamp}</span>
                        </div>
                        <div class="direct-chat-text">
                            ${message.message}
                        </div>
                    </div>
                `;
            }

            $('#conversation-messages').append(html);
        }

        // Function to scroll to bottom of conversation
        function scrollToBottom() {
            const conversationMessages = document.getElementById('conversation-messages');
            conversationMessages.scrollTop = conversationMessages.scrollHeight;
        }

        // Periodically check for new messages (every 30 seconds)
        setInterval(function() {
            $.ajax({
                url: '{{ route("faculty.messages.check") }}',
                method: 'GET',
                success: function(response) {
                    if (response.count > 0) {
                        // Reload current conversation if active
                        const currentRecipient = $('#recipient-id').val();
                        if (currentRecipient) {
                            const userName = $('#conversation-title').text();
                            loadConversation(currentRecipient, userName);
                        }

                        // Update notification in layout (if implemented)
                        $('.message-count').text(response.count);

                        if (response.count > 0) {
                            toastr.info('You have new messages from students');
                        }
                    }
                }
            });
        }, 30000);
    });
</script>
@endsection
