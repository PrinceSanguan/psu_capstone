@extends('layouts.client')

@section('title', 'Messages')

@section('styles')
<style>
    .chat-container {
        height: 500px;
        display: flex;
        flex-direction: column;
    }

    .contacts-list {
        height: 100%;
        overflow-y: auto;
        border-right: 1px solid #e3e6f0;
    }

    .contacts-list .list-group-item {
        border-radius: 0;
        cursor: pointer;
    }

    .contacts-list .list-group-item.active {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .chat-messages {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
    }

    .message {
        margin-bottom: 15px;
        max-width: 80%;
    }

    .message.sent {
        margin-left: auto;
        background-color: #4e73df;
        color: white;
        border-radius: 15px 15px 0 15px;
    }

    .message.received {
        margin-right: auto;
        background-color: #f8f9fc;
        border: 1px solid #e3e6f0;
        border-radius: 15px 15px 15px 0;
    }

    .message-input {
        padding: 15px;
        border-top: 1px solid #e3e6f0;
    }

    .message-time {
        font-size: 0.7rem;
        margin-top: 5px;
        color: #858796;
    }

    .message.sent .message-time {
        color: #d1d3e2;
        text-align: right;
    }

    .no-messages {
        display: flex;
        height: 100%;
        justify-content: center;
        align-items: center;
        color: #858796;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Messages</h1>

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="row chat-container">
                <!-- Contacts List -->
                <div class="col-md-4 contacts-list">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item bg-light">
                            <h6 class="mb-0">Teachers</h6>
                        </div>

                        @if($teachers->isEmpty() && $conversationUsers->isEmpty())
                            <div class="list-group-item">
                                <p class="mb-0">No contacts available.</p>
                            </div>
                        @else
                            @foreach($teachers as $teacher)
                                <a href="#" class="list-group-item list-group-item-action contact-item" data-id="{{ $teacher->faculty_id }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $teacher->faculty_name }}</h6>
                                    </div>
                                    <small>{{ $teacher->subject_code }} - {{ $teacher->subject_name }}</small>
                                </a>
                            @endforeach

                            @foreach($conversationUsers as $user)
                                @if(!$teachers->contains('faculty_id', $user->id))
                                    <a href="#" class="list-group-item list-group-item-action contact-item" data-id="{{ $user->id }}">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $user->name }}</h6>
                                        </div>
                                        <small>{{ $user->user_role == 'faculty' ? 'Teacher' : 'Admin' }}</small>
                                    </a>
                                @endif
                            @endforeach
