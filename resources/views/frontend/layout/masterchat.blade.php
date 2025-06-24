<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    {{-- Toastify --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @stack('styles')
    <script>
        const USER = {
            id: "{{ auth()->user()->id }}"
        }
    </script>
    @vite(['resources/js/app.js', 'resources/js/frontend.js'])
    <title>OShop Chat System</title>
</head>

<body>

    <div style="font-family:Roboto, sans-serif;">

        <!-- Main Content -->
        <div class=" bg-slate-100">
            <div class="main-content  mx-auto">
                @yield('content')
            </div>
        </div>


    </div>

    {{-- Sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Jquery UI --}}
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    {{-- Font Awesome --}}
    <script src="https://kit.fontawesome.com/1027857984.js" crossorigin="anonymous"></script>
    {{-- Swiper --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    {{-- Toastify --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $err)

                Toastify({
                    text: "{{ $err }}",
                    duration: 3000,
                    className: "info",
                    style: {
                        background: "linear-gradient(to right, #00b09b, #96c93d)",
                    }
                }).showToast();
            @endforeach
        @endif
        @if (Session::has('status'))
            Toastify({
                text: "{{ session('status') }}",
                duration: 3000,
                className: "info",
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                }
            }).showToast();
        @endif
    </script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        // Chat -----------------------------------
        const senderId = "{{ Auth::check() ? auth()->user()->id : ' ' }}";

        function init() {
            $(".receiver").each(function(i, v) {
                $(v).removeClass("bg-slate-100");
            });
            const messagePatternHTML = `
                        <div class="message overflow-y-scroll  bg-slate-200 max-h-[550px]  gap-y-3">
                            <div class="bg-white p-3 text-xl text-sky-600 cursor-pointer message-receiver-name">${name}
                            </div>
                            <div class="message-area flex flex-col gap-y-3  p-5">
                            </div>
                        </div>
                    `
            $(".message").replaceWith(messagePatternHTML);
        }
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
                        $(".message-area").html('');
                        $(".message-area").addClass("message-area-" + receiverID);
                        const chat = response.chat;
                        $.each(chat, function(i, e) {
                            let senderHTML, receiverHTML;
                            if (e.sender_id == senderID) {
                                senderHTML = `
                                <div class="sender flex items-end flex-col gap-y-3">
                                    <div class=" bg-sky-600 text-white p-2 max-w-[70%] text-sm rounded-md ">
                                        <p class="message-content">${e.message}</p>
                                        <p class="text-end message-time text-xs font-light">${getCurrentTime(e.created_at)}</p>
                                    </div>
                                </div>  `;
                                $(".message-area").append(senderHTML);
                            } else {
                                receiverHTML = `
                                <div class="receiver flex items-start flex-col gap-y-3">
                                    <div class="bg-white text-black p-2 max-w-[70%] text-sm rounded-md">
                                        <p class="message-content">${e.message}</p>
                                        <p class="text-end message-time text-xs font-light">${getCurrentTime(e.created_at)}</p>
                                    </div>
                                </div>  `
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
                            <div data-id="${receiver.id}" class="receiver cursor-pointer flex items-center p-2 max-w-[250px] max-h-[100px] bg-slate-100  ">
                                    <div><img class="rounded-full" width="50"
                                            src="{{ asset('${receiver.banner}') }}" />
                                    </div>
                                    <div class="flex flex-col p-1">
                                        <p class="flex justify-between"><span class="font-semibold text-sm receiver-name">${receiver.name}</span>
                                            <span class='text-xs'>4/2/2024</span>
                                        </p>
                                        <p class="last-chat">Lorem, ipsum dolor sit
                                        </p>
                                    </div>
                            </div>
                        `
                            $(".receiver").each(function(i, v) {
                                $(v).removeClass("bg-slate-100");
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
            const receiverID = $(this).data('id');
            getMessage(senderId, receiverID);
            setInputReceiverID(receiverID);
            $(this).addClass("bg-slate-100");
            const receiverName = $(this).find(".receiver-name").html();
            $(".message-receiver-name").html(receiverName);
        })
        $(".show-chat-pannel").on("click", function() {

            const name = $(this).data("name");
            const banner = $(this).data("banner");
            const receiverID = $(this).data("id");

            $(".receiver").each(function(i, v) {
                if ($(v).data('id') == receiverID) {
                    init();
                    $(v).addClass("bg-slate-100");
                    getMessage(senderId, receiverID);
                }
            })

            setInputReceiverID(receiverID);

            $(".chat-pannel").show(500);

        });
        $(".close-chat-pannel").on("click", function() {
            init();
            $(".chat-pannel").hide(500);
        });
        $(".send-message").on("submit", function(e) {
            e.preventDefault();
            const id = $("input[name = 'receiver_id']").val();
            const data = $(this).serialize();
            const messageContent = $("#message_content").val();
            const currentTime = getCurrentTime(new Date());
            const messageAreaHTML = `
                <div class="sender flex items-end flex-col gap-y-3">
                        <div class=" bg-sky-600 text-white p-2 max-w-[70%] text-sm rounded-md ">
                                <p class="message-content">${messageContent}</p>
                                <p class="text-end message-time text-xs">${currentTime}</p>
                        </div>
                </div>
            `
            $(".message-area").append(messageAreaHTML);
            sendMessage(data);
            $("#message_content").val("");
            $(".unseen-" + id).html("").hide();
            scrollBottom()

        })
        // Chat -----------------------------------
    </script>
    @stack('scripts')
</body>

</html>
