@extends('vendor.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Chat</h1>
            {{-- <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Vendor Order</div>
            </div> --}}
        </div>
        <div class="section-body">
            <h2 class="section-title">Chat Boxes</h2>

            <div class="row align-items-center justify-content-center">
                {{-- Receiver  --}}
                <div class="col-lg-4">
                    <div class="card" style="min-height: 600px">
                        <div class="card-header">
                            <h4>Your Customers</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-unstyled-border">
                                <div class="receivers overflow-y-scroll border-r-2  border-slate-100">
                                    <div class="receiver"></div>

                                    @foreach (getReceivers() as $receiver)
                                        <li data-id="{{ $receiver->id }}"
                                            class="receiver list-group-item d-flex align-items-center border-0 shadow-sm mb-3 rounded-3 cursor-pointer py-3 bg-white hover-bg-light">
                                            <div class="me-3 position-relative mr-2 ">
                                                <img class="rounded-circle border border- border-primary shadow"
                                                    width="60" height="60" src="{{ asset($receiver->image) }}"
                                                    alt="{{ $receiver->name }}">

                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span
                                                        class="fw-bold text-dark receiver-name fs-5">{{ $receiver->name }}</span>
                                                    <small
                                                        class="text-muted">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</small>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span></span>
                                                    <span class="ms-2 unseen-{{ $receiver->id }} text-info"
                                                        style="display: none">
                                                        <i class="fa-solid fa-circle"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Message --}}
                <div class=" col-lg-8">
                    <div class="card chat-box " style="min-height: 600px" id="mychatbox">
                        <div class="card-header">
                            <h4 class="selected-receiver-name">Select Receiver</h4>
                        </div>
                        <div class="card-body chat-content p-0">
                            <div class="message" style="height: 480px; overflow-y: auto;">
                                <div class="bg-white p-3 h5 fixed w-full text-primary cursor-pointer message-receiver-name">
                                </div>
                                <div class="message-area d-flex flex-column gap-3 p-4 mt-10">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer chat-form">
                            <form id="chat-form" class="send-message">
                                <input type="hidden" name="sender_id" value="{{ Auth::user()->id }}" />
                                <input type="hidden" name="receiver_id" />
                                <input required name="message_content" id="message_content"
                                    placeholder="Type Something ....." class=" form-control "type="text" />


                                <button id="send_message" class="btn btn-primary">
                                    <i class="far fa-paper-plane"></i>
                                </button>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Chat -----------------------------------
        const senderId = "{{ Auth::check() ? auth()->user()->id : ' ' }}";

        function disableChat(flag) {
            if (flag === true) {
                $("#message_content").attr("disabled", true);
                $("#send_message").attr("disabled", true);
            } else {
                $("#message_content").removeAttr("disabled");
                $("#send_message").removeAttr("disabled");
            }
        }

        function init() {
            $(".receiver").each(function(i, v) {
                $(v).removeClass("bg-light");
            });
            const messagePatternHTML = `
                <div class="message" style="height: 480px; overflow-y: auto;">
                    <div class="message-area d-flex flex-column gap-3 p-4 mt-10">
                    </div>
                </div>
            `
            $(".message").replaceWith(messagePatternHTML);
        }
        disableChat(true);
        init();

        // Scroll message to the bottom 
        function scrollBottom() {
            let messageArea = $(".message ");
            messageArea.scrollTop(messageArea.prop("scrollHeight"));
        }

        function setInputReceiverID(id) {
            $("input[name = 'receiver_id']").val(id);
        }

        function getCurrentTime(date) {
            var currentTime = new Date(date);

            return currentTime.toLocaleTimeString([], {
                hour: "2-digit",
                minute: "2-digit"
            })
        }
        // Get message
        function getMessage(senderID, receiverID) {
            $.ajax({
                type: "GET",
                url: "{{ route('vendor.message.get-message') }}",
                data: {
                    receiver_id: receiverID,
                    sender_id: senderID,
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.status == 'success') {
                        $(".selected-receiver-name").html(response.receiverName);
                        $(".message-area").html('');
                        $(".message-area").addClass("message-area-" + receiverID);
                        const chat = response.chat;
                        $.each(chat, function(i, e) {
                            let senderHTML, receiverHTML;
                            if (e.sender_id == senderID) {
                                senderHTML = `
                                <div class="d-flex flex-column align-items-end mb-3">
                                    <div class="bg-primary text-white p-2 rounded mb-1" style="max-width: 70%; font-size: 0.9rem;">
                                        <p class="mb-1 message-content">${e.message}</p>
                                        <p class="text-end message-time  small mb-0">${getCurrentTime(e.created_at)}</p>
                                    </div>
                                </div>`;
                                $(".message-area").append(senderHTML);
                            } else {
                                receiverHTML = `
                                <div class="d-flex flex-column align-items-start mb-3">
                                    <div class="bg-light text-dark p-2 rounded mb-1" style="max-width: 70%; font-size: 0.9rem;">
                                        <p class="mb-1 message-content">${e.message}</p>
                                        <p class="text-end message-time text-muted small mb-0">${getCurrentTime(e.created_at)}</p>
                                    </div>
                                </div>`;
                                $(".message-area").append(receiverHTML);
                            }
                            scrollBottom();
                        });
                        $(".unseen-" + receiverID).hide();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.table(jqXHR)
                },
                complete: function() {

                }
            });
        }
        // Send Message 
        function sendMessage(data) {
            $.ajax({
                type: "POST",
                url: "{{ route('vendor.message.send-message') }}",
                data: data,
                dataType: "JSON",
                success: function(response) {
                    const receiver = response.receiver;
                    if (response.status == "success") {
                        if (response.isNewConversation) {
                            const receiverHTML = `
                                <div data-id="${receiver.id}" class="receiver cursor-pointer d-flex align-items-center p-2" style="max-width:250px; max-height:100px; background-color: #f8f9fa;">
                                    <div>
                                        <img class="rounded-circle" width="50"
                                            src="{{ asset('${receiver.banner}') }}" />
                                    </div>
                                    <div class="d-flex flex-column p-1">
                                        <p class="d-flex justify-content-between mb-1">
                                            <span class="fw-semibold fs-6 receiver-name">${receiver.name}</span>
                                            <span class='text-xs'>4/2/2024</span>
                                        </p>
                                        <p class="last-chat mb-0">Lorem, ipsum dolor sit
                                        </p>
                                    </div>
                                </div>
                            `
                            $(".receiver").each(function(i, v) {
                                $(v).removeClass("bg-light");
                            });
                            $(".receivers").prepend(receiverHTML);
                        }
                        $("#message_content").val("");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.table(jqXHR)
                },
                complete: function() {
                    console.log(12);
                }
            });
        }
        // Change Message Receiver
        $("body").on("click", ".receivers .receiver", function() {
            init();
            disableChat(false);
            const receiverID = $(this).data('id');
            getMessage(senderId, receiverID);
            setInputReceiverID(receiverID);
            $(this).addClass("bg-light");
            const receiverName = $(this).find(".receiver-name").html();
            $(".message-receiver-name").html(receiverName);
        })
        // Change Message Receiver
        $(".send-message").on("submit", function(e) {
            e.preventDefault();
            const id = $("input[name = 'receiver_id']").val();
            const data = $(this).serialize();
            const messageContent = $("#message_content").val();
            const currentTime = getCurrentTime(new Date());
            const messageAreaHTML = `
                <div class="d-flex flex-column align-items-end mb-3">
                    <div class="bg-primary text-white p-2 rounded mb-1" style="max-width: 70%; font-size: 0.9rem;">
                        <p class="mb-1 message-content">${messageContent}</p>
                        <p class="text-end message-time small mb-0">${currentTime}</p>
                    </div>
                </div>
            `
            $(".message-area").append(messageAreaHTML);
            sendMessage(data);
            $("#message_content").val("");
            $(".unseen-" + id).hide();
            scrollBottom();
        })
        // Chat -----------------------------------
    </script>
@endpush
