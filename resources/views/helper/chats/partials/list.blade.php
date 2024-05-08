<script>
    var selectedChatID = 0;
</script>

<div class="row bg-light-gray">
    <div class="col-md-3 col-sm-2 people-list" id="people-list">
        {{-- <div class="search">
            <input type="text" placeholder="search" />
            <i class="fa fa-search"></i>
        </div> --}}
        <ul class="list">
            <h5>Users List</h5>
            {{-- Load List of Chats --}}
            @foreach ($chats as $chat)
                <li class="clearfix">
                    <div id="user_chat_{{ $chat->id }}" class="d-flex align-items-center p-2 item"
                        onclick="loadChat({{ $chat->id }})">
                        <img src="{{ $chat->otherUserInfo->profile_image ? asset('images/users/' . $chat->otherUserInfo->profile_image) : asset('images/users/default.png') }}"
                            alt="avatar" />
                        <div class="about">
                            <div class="name">
                                {{ $chat->otherUserInfo->first_name . ' ' . $chat->otherUserInfo->last_name }}</div>
                            <p class="mb-0">{{ $chat->last_message->message ?? 'Send A Message' }}</p>
                        </div>
                    </div>
                </li>
            @endforeach


        </ul>
    </div>

    <div class="col-md-9 col-sm-10 chat">
        <div class="chat-header">
            {{-- User Info --}}
            <div class="">
                <img id="chat-avatar" src="{{ asset('images/users/default.png') }}" width="50" alt="avatar" />
                <div class="chat-about">
                    <div id="chat-with" class="chat-with">User Name</div>
                    <div id="user-status" class="chat-num-messages">Status</div>
                </div>
            </div>
        </div> <!-- end chat-header -->

        <div>
            <ul id="chat-history" class="chat-history">

                {{-- Load Messages of Chat --}}

            </ul>

        </div> <!-- end chat-history -->

        <div class="chat-message clearfix">
            <textarea name="message-to-send" id="message-to-send" placeholder ="Type your message" rows="2"></textarea>

            <button id="send-message" class="btn btn-primary btn-sm float-right">Send</button>

        </div> <!-- end chat-message -->

    </div> <!-- end chat -->

</div> <!-- end container -->

<script>
    // Load Chat function
    function loadChat(id) {
        // remove active class from all items
        $('.item').removeClass('active');
        // add active class to current item
        $('#user_chat_' + id).addClass('active');

        // Load Messages of Chat
        loadMessages(id);
        // alert(id);
    }

    // Load Messages of Chat function
    function loadMessages(id) {
        // Base URL
        const base_url = "{{ url('/') }}";

        // AJAX get request to get messages on chat_id
        $.ajax({
            url: base_url + '/helper/chat/messages/' + id,
            type: 'GET',
            success: function(response) {
                // Handle the success response (received messages)

                if (response.success == true) {
                    // Set selectedChatID
                    selectedChatID = id;

                    // Update User Info at top
                    console.log(response);
                    $('#chat-with').html(response.otherUserInfo.first_name + ' ' + response.otherUserInfo
                        .last_name);
                    // $('#user-status').html(response.otherUserInfo.is_online ? 'Online' : 'Offline');


                    // Update Image
                    if (response.otherUserInfo.profile_image) {
                        var image_path = '{{ asset('images/users/') }}' + '/' + response.otherUserInfo
                            .profile_image;
                    } else {
                        var image_path = '{{ asset('images/users/default.png') }}';
                    }
                    $('#chat-avatar').attr('src', image_path);

                    // Clear items in chat history
                    $('#chat-history').html('');

                    // Check if there are messages
                    if (response.data.length) {
                        // Loop through messages to add it to the chat history
                        response.data.forEach(function(message) {
                            // Add message to chat history
                            addMessageToChatHistory(message);
                        })
                    } else {
                        console.log('No messages found');
                        $('#chat-history').html(
                            '<p class="text-center fs-xxs">Write a message to start chat</p>');
                    }

                } else {
                    console.log('No messages found');
                    $('#chat-history').html(
                        '<p class="text-center fs-xxs">Write a message to start chat</p>');
                }
            },
            error: function(xhr) {
                // Handle errors
                console.error(xhr.responseText);
            }
        });
    }

    // Add message to chat history function
    function addMessageToChatHistory(item) {
        // console.log(item);
        // Add message to chat history
        var messageLi = document.createElement('li');
        messageLi.classList.add('clearfix');

        var message = document.createElement('div');
        message.classList.add('message');
        if (item.sender_id == {{ auth()->user()->id }}) {
            message.classList.add('other-message');
            message.classList.add('float-right');
            message.classList.add('text-right');
        } else {

            message.classList.add('my-message');
        }

        var htmlContent = `<p class="message-text">${item.message}</p>`;
        htmlContent += `<p class="message-data-time">${timeAgo(item.created_at)}</p>`;

        message.innerHTML = htmlContent;

        messageLi.appendChild(message);


        document.querySelector('#chat-history').appendChild(messageLi);

        // Scroll chat-history to bottom
        const chatHistory = document.querySelector('#chat-history');
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    // Send Message Function
    document.getElementById('send-message').onclick = function() {
        sendMessage();
    }

    // Send Message Function
    function sendMessage() {
        // If no chat is selected then return false
        if (!selectedChatID) {
            alert('No chat selected. Please select a chat.');
            return false;
        }
        // Read message 
        const message = document.getElementById('message-to-send').value;

        // If message is empty then show error
        if (!message) {
            alert('Please type a message first');
            return false;
        }

        if (message) {
            $.ajax({
                type: "POST",
                url: "{{ route('helper.chat.messages.store') }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    chat_id: selectedChatID,
                    message: message,
                },
                success: function(data) {
                    if (data.success == true) {
                        // console.log(data);
                        document.getElementById('message-to-send').value = '';
                        addMessageToChatHistory(data.data);

                        // Scroll chat-history to bottom
                        const chatHistory = document.querySelector('#chat-history');
                        chatHistory.scrollTop = chatHistory.scrollHeight;

                    } else {
                        alert(data.data);
                    }
                },
                error: function(xhr) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });
        }
    }



    // Time ago function

    function timeAgo(timestamp) {
        // Convert the created date string to a Date object
        const createdDate = new Date(timestamp);

        // Get the current date and time
        const now = new Date();

        // Calculate the difference in milliseconds
        const diffMs = now - createdDate;

        // Calculate the difference in seconds
        const diffSeconds = Math.floor(diffMs / 1000);

        // Check if the difference is less than a minute
        if (diffSeconds < 60) {
            return 'just now';
        }

        // Calculate the difference in minutes
        const diffMinutes = Math.floor(diffSeconds / 60);

        // Check if the difference is less than an hour
        if (diffMinutes < 60) {
            return diffMinutes + ' mins ago';
        }

        // Calculate the difference in hours
        const diffHours = Math.floor(diffMinutes / 60);

        // Check if the difference is less than a day
        if (diffHours < 24) {
            return diffHours + ' hours ago';
        }

        // Calculate the difference in days
        const diffDays = Math.floor(diffHours / 24);

        // Check if the difference is less than a month
        if (diffDays < 30) {
            return diffDays + ' days ago';
        }

        // Calculate the difference in months
        const diffMonths = Math.floor(diffDays / 30);

        // Check if the difference is less than a year
        if (diffMonths < 12) {
            return diffMonths + ' months ago';
        }

        // Calculate the difference in years
        const diffYears = Math.floor(diffMonths / 12);

        // Return the year difference
        return diffYears + ' years ago';
    }
</script>

{{-- Load messsages script --}}

{{-- On Page Load -> Load chat of first user --}}
<script>
    window.onload = function() {
        @if (count($chats))
            var chats = @json($chats);
            // console.log(chats); // For testing
            loadChat(chats[0].id); // Example: Load chat with the ID of the first chat
        @endif

        // Loadin from Other Template File - chats/index.blade.php
        $("#searchInput").keyup(function() {
            console.log('Searching...');
            // Trigger search
            searchUsers();
        });
    };
</script>
