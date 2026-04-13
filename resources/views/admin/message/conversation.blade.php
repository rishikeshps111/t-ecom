@extends('admin.layouts.app')
@section('title')
    Message Management
@endsection
@section('style')
    @include('admin.scripts.css')
@endsection
@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Message Conversation</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="main-table-container">
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="chat-header d-flex align-items-center justify-content-between">
                                <div class="chat-title">
                                    @role('Super Admin')
                                    <h5 class="mb-1">
                                        Chat with <span class="fw-semibold">{{ $message->user->name ?? 'N/A' }}</span>
                                    </h5>
                                    @endrole

                                    @role('Corp User')
                                    <h5 class="mb-1">
                                        Chat with <span class="fw-semibold">Admin</span>
                                    </h5>
                                    @endrole

                                    {{-- <small class="text-muted">
                                        Subject: {{ $message->subject ?? 'N/A' }}
                                    </small> --}}
                                </div>

                                <div class="chat-meta text-end">
                                    <span class="badge bg-light text-dark">
                                        Chat
                                    </span>
                                </div>
                            </div>
                            <div class="chat-box" id="chatBox">
                                <!-- Messages will load here -->
                            </div>

                            <div class="chat-input mt-3">
                                <form id="chatForm">
                                    @csrf
                                    <div class="input-group">
                                        <textarea class="form-control" name="message" rows="2"
                                            placeholder="Type your message..."></textarea>
                                        <button class="btn btn-primary" type="submit">
                                            Send
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @include('admin.scripts.script')
    <script>
        $(document).ready(function () {
            const messageId = "{{ $message->id }}";
            const fetchUrl = "{{ route('admin.messages.conversations', $message->id) }}";
            const sendUrl = "{{ route('admin.messages.conversations.send', $message->id) }}";
            const currentUserRole = "{{ Auth::user()->getRoleNames()->first() }}";

            function loadConversations() {
                $.get(fetchUrl, function (data) {
                    let html = '';
                    data.forEach(msg => {
                        let align = 'chat-left';
                        if (currentUserRole === 'Super Admin') {
                            align = msg.send_by === 'admin' ? 'chat-right' : 'chat-left';
                        } else {
                            align = msg.send_by === 'user' ? 'chat-right' : 'chat-left';
                        }

                        html += `
                                                                                            <div class="chat-message ${align}" data-id="${msg.id}">
                                                                                                <div class="chat-bubble ${msg.is_read ? '' : 'unread'}">
                                                                                                    ${msg.message}
                                                                                                   <div class="chat-meta d-flex align-items-center justify-content-end" style="font-size: 11px; opacity: 0.6; gap: 5px;">
        <small>${formatDate(msg.created_at)}</small>
        <span class="read-tick">${msg.is_read ? '✓✓' : '✓'}</span>
    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        `;
                    });

                    $('#chatBox').html(html);
                    $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
                    markMessagesAsRead();
                });
            }

            function formatDate(dateString) {
                const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                ];
                const date = new Date(dateString);
                const day = date.getDate();
                const month = months[date.getMonth()];
                const year = date.getFullYear();

                let hours = date.getHours();
                const minutes = date.getMinutes().toString().padStart(2, '0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12 || 12;

                return `${day} ${month} ${year}, ${hours}:${minutes} ${ampm}`;
            }



            loadConversations();

            $('#chatForm').on('submit', function (e) {
                e.preventDefault();

                $.post(sendUrl, $(this).serialize(), function () {
                    $('#chatForm textarea').val('');
                    loadConversations();
                });
            });

            // Optional: auto refresh every 5 sec
            setInterval(loadConversations, 5000);

            function markMessagesAsRead() {
                $('#chatBox .chat-message').each(function () {
                    const conversationId = $(this).data('id');
                    const bubble = $(this).find('.chat-bubble');
                    // Only mark messages as read if they are unread and from the other side
                    if (bubble.hasClass('unread')) {
                        $.ajax({
                            url: "/admin/messages/conversations/read/" + conversationId,
                            type: "POST",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function () {
                                bubble.removeClass('unread');
                            },
                            error: function (xhr) {
                                console.error('Failed to mark as read:', xhr.responseText);
                            }
                        });
                    }
                });
            }

        });
    </script>
@endsection