@extends('layouts.master')

@section('title', 'Chat')

@section('content')
<div class="container mt-4">
    <h1>Chat for Job #{{ $jobId }}</h1>

    <div class="chat-view">
        <div class="chat-header">
            <a href="{{ url('admin/complaints') }}" class="btn btn-secondary">Back</a>
        </div>
        <div class="chat-messages" id="chatMessages">
            @foreach($messages as $message)
                <div class="chat-message {{ $message->user_id == 1 ? 'admin' : 'user' }}">
                    <div class="message-content">
                        @if($message->user_id != 1)
                            <strong>{{ $message->first_name }}:</strong>
                        @endif
                        <div class="message-text">
                            {{ $message->message }}
                        </div>
                        @if($message->img_url)
                            @php
                                $imgUrl = $message->img_url;
                                $fileExtension = pathinfo($message->img_url, PATHINFO_EXTENSION);
                            @endphp
                            @if($fileExtension == 'pdf')
                                <a href="{{ asset($imgUrl) }}" class="message-file download-link" download>
                                    <i class="fas fa-file-pdf"></i> Download PDF
                                </a>
                            @else
                                <img src="{{ asset( $imgUrl) }}" alt="attachment" class="message-img"/>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Message Input Form -->
        @if($complaintStatus == 1)
            <div class="chat-input">
                <form id="chatForm" method="POST" action="{{ route('send-message') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="job_id" value="{{ $jobId }}">
                    <input type="hidden" name="user_id" value="1"> <!-- Admin ID -->
                    <div class="input-group">
                        <textarea name="message" rows="3" placeholder="Type your message here..." required class="form-control"></textarea>
                        <div class="input-group-append">
                            <label for="attachments" class="attachment-label input-group-text">
                                <i class="fas fa-paperclip"></i>
                                <input type="file" name="attachments[]" id="attachments" multiple hidden> <!-- File input for attachments -->
                            </label>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary" style="margin-left: 8px;">Send</button>
                        </div>
                    </div>
                    <div id="fileList" class="mt-2"></div> <!-- Container to show the file names -->
                </form>
            </div>
        @else
            <div class="alert alert-info mt-4">
                This complaint has been resolved. You cannot reply to this conversation.
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Your custom styles here */
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var chatMessages = document.getElementById('chatMessages');
        chatMessages.scrollTop = chatMessages.scrollHeight;

        document.getElementById('attachments').addEventListener('change', function() {
            var fileList = document.getElementById('fileList');
            fileList.innerHTML = '';
            for (var i = 0; i < this.files.length; i++) {
                var fileDiv = document.createElement('div');
                fileDiv.textContent = this.files[i].name;
                fileList.appendChild(fileDiv);
            }
        });

        document.getElementById('chatForm').addEventListener('submit', function() {
            var chatMessages = document.getElementById('chatMessages');
            setTimeout(function() {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 100);
        });
    });
</script>
@endsection
