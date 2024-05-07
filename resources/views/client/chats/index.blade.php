@extends('client.layouts.app')

@section('title', 'Chats')

@section('content')

    {{-- Define some javascript variables to be used in JS --}}
    <script>
        selectedNewChatUserID = 0;
    </script>

    {{-- HTML Section Start --}}
    <section class="section">
        <div class="container-fluid">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-0">Chats</h4>
                    <button type="button" class="btn btn-primary btn-sm" onclick="showCreateChatModal()">
                        New Chat
                    </button>
                </div>
            </div>
            <div class="section-body">
                <div id="clientTable">
                    @include('client.chats.partials.list')
                </div>
            </div>
        </div>
    </section>

    <!-- Create Chat Modal -->
    <div class="modal fade" id="createChatModal" tabindex="-1" role="dialog" aria-labelledby="createChatModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createChatModalLabel">Create Chat</h5>
                </div>
                <div class="modal-body">
                    {{-- Search Input Field --}}
                    <div class="d-flex">
                        <input id="searchInput" type="text" class="form-control" placeholder="Search...">
                    </div>
                    {{-- Search Results --}}
                    <div id="searchResults" class="searchResults mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeCreateChatModal()">Close</button>
                    <!-- Additional modal footer buttons if needed -->
                </div>
            </div>
        </div>
    </div>

    {{-- HTML Section End --}}

    <script>
        // Show Modal to Create Chat
        function showCreateChatModal() {
            $('#createChatModal').modal('show');
        }

        // Close Modal to Create Chat
        function closeCreateChatModal() {
            $('#createChatModal').modal('hide');
        }

        // Search Users
        function searchUsers() {
            // Get search input
            var search = $("#searchInput").val();

            if (search.length > 2) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.users.search') }}",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        search: search,
                    },
                    success: function(data) {
                        displaySearchResults(data);
                    },
                    error: function(xhr) {
                        // Handle errors
                        console.error(xhr.responseText);
                    }
                });
            } else {
                // Clear search results if search input is too short
                displaySearchResults([]);
            }
        }

        // Display search results
        function displaySearchResults(users) {
            var searchResultsDiv = $("#searchResults");
            searchResultsDiv.empty();

            if (users.length > 0) {
                // var userList = $("<ul>");
                users.forEach(function(user) {
                    const listItem = document.createElement('div');
                    listItem.textContent = `${user.first_name} ${user.last_name}`;
                    listItem.classList.add('result-item');

                    // Handle selection
                    listItem.addEventListener('click', function() {
                        // Handle selection
                        selectedNewChatUserID = user.user_id;
                        selectedData = user;
                        console.log('Selected:', selectedData);

                        // Call a function to close modal and start chat
                        createNewChat(selectedNewChatUserID);
                    });

                    searchResults.appendChild(listItem);
                });
                // searchResultsDiv.append(userList);
            } else {
                searchResultsDiv.html("<p class='text-center fs-xxs text-danger'>No results found</p>");
            }
        }

        // Trigger search on keyup event

        // Written inside the document ready function of chats/partials/list.blade.php page

        // $(document).ready(function() {

        // });


        // Create New Chat
        function createNewChat(id) {
            // Hide Modal
            $('#createChatModal').modal('hide');

            if (selectedNewChatUserID) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.chat.create') }}",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        user_id: selectedNewChatUserID,
                    },
                    success: function(data) {
                        if (data.success == true) {
                            console.log(data);
                            document.getElementById('message-to-send').value = '';

                            // Append new chat to chat list
                            addChatToChatList(data.chat_id, data.userInfo);

                            // Open Chat Window with Selected User
                            loadMessages(data.chat_id);


                        } else {
                            alert(data.message);
                        }
                    },
                    error: function(xhr) {
                        // Handle errors
                        console.error(xhr.responseText);
                    }
                });
            }
        }

        // Append chat to chat list
        function addChatToChatList(chat_id, userInfo) {
            // Append new chat to chat list
            let listItem = `<li class="clearfix">
                    <div id="user_chat_${chat_id}" class="d-flex align-items-center p-2 item"
                        onclick="loadChat(${chat_id})">
                        <img src="${userInfo.profile_image || '{{ asset('images/users/default.png') }}'}"
                            alt="avatar" />
                        <div class="about">
                            <div class="name">
                                ${userInfo.first_name} ${userInfo.last_name}</div>
                            <p class="mb-0">Send A Message</p>
                        </div>
                    </div>
                </li>`;
            $("#chat-list-wrapper").append(listItem);

            // remove active class from all items
            $('.item').removeClass('active');

            // add active class to current item
            $('#user_chat_' + chat_id).addClass('active');

        }
    </script>

@endsection
