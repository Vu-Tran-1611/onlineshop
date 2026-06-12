@push('styles')
    <style>
        .open-chat-pannel {
            background: #0f172a;
            color: #ffffff;
            border: 1px solid #334155;
            box-shadow: 0 18px 40px -16px rgba(15, 23, 42, 0.75);
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            mix-blend-mode: normal;
            isolation: isolate;
            z-index: 10001;
        }

        .chat-pannel {
            background: #f8fafc;
            border-left: 1px solid #cbd5e1;
            width: min(1100px, 96vw);
            opacity: 1;
        }

        .chat-surface {
            height: calc(100% - 92px);
            background: linear-gradient(145deg, #f8fafc, #f1f5f9);
        }

        .receivers {
            background: #f1f5f9;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
        }

        .receivers .receiver {
            border-bottom: 1px solid #cbd5e1;
            background: #ffffff;
        }

        .receivers .receiver:hover {
            background: #e2e8f0;
        }

        .message {
            background:
                #f8fafc;
        }

        .message-header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }

        .message-input-wrap {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
        }

        .message-area {
            min-height: calc(100vh - 255px);
            max-height: calc(100vh - 255px);
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        #message_content {
            background: #ffffff;
            color: #1e293b;
        }

        #message_content::placeholder {
            color: #64748b;
            opacity: 1;
        }

        #send_message {
            background: #0f172a;
            color: #ffffff;
            box-shadow: 0 10px 24px -14px rgba(15, 23, 42, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        #send_message:hover {
            background: #1e293b;
        }

        #message_content:disabled,
        #send_message:disabled {
            cursor: not-allowed;
            opacity: 0.85;
        }

        #send_message:disabled {
            background: #94a3b8;
            border-color: #e2e8f0;
            box-shadow: none;
        }

        .chat-header-title {
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.18);
        }

        .chat-header-dot {
            background: rgba(255, 255, 255, 0.45);
        }

        .close-chat-pannel {
            background: #0f172a;
            color: #ffffff;
            border: 1px solid rgba(148, 163, 184, 0.6);
            box-shadow: 0 10px 24px -16px rgba(15, 23, 42, 0.8);
        }

        .close-chat-pannel:hover {
            background: #1e293b;
            color: #ffffff;
        }

        @media (max-width: 1024px) {
            .chat-pannel {
                width: 100%;
            }

            .chat-surface {
                grid-template-columns: 1fr;
            }

            .receivers {
                max-height: 220px;
                border-right: none;
                border-bottom: 1px solid #cbd5e1;
            }

            .message-input-wrap {
                width: 100%;
            }

            .message-area {
                min-height: calc(100vh - 430px);
                max-height: calc(100vh - 430px);
            }
        }
    </style>
@endpush

<div
        class="open-chat-pannel text-md fixed bottom-6 right-6 px-8 py-2 rounded-full text-white
    cursor-pointer transition-all duration-300 hover:scale-105 bg-slate-900 hover:bg-slate-800 border border-slate-600 shadow-2xl z-[10001]">
    <div class="flex items-center space-x-2">
        <i class="fa-regular fa-comment text-xl"></i>
        <span class="font-medium">Chat</span>
    </div>
</div>

{{-- Chat Panel --}}
<div class="chat-pannel z-[10000] hidden shadow-2xl backdrop-blur-xl w-[880px] fixed top-0 h-full right-0">

    <div class="p-5 md:p-6 flex justify-between items-center bg-slate-800 text-white">
        <div class="flex items-center gap-3 md:gap-4 min-w-0">
            <div class="flex items-center gap-2">
                <div class="chat-header-dot w-3 h-3 rounded-full animate-pulse"></div>
                <div class="chat-header-dot w-2 h-2 rounded-full animate-pulse delay-100"></div>
            </div>
            <h2 class="chat-header-title text-3xl md:text-5xl font-extrabold tracking-tight leading-none">Messages</h2>
        </div>
        <button class="close-chat-pannel cursor-pointer text-2xl transition-all duration-300 hover:rotate-90 transform w-10 h-10 rounded-full flex items-center justify-center shrink-0">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>

    <div class="chat-surface grid grid-cols-[300px_auto]">
        {{-- Vendors List --}}
        <div class="receivers overflow-y-auto border-r border-gray-200/50 backdrop-blur-sm"
            style="background: rgba(251, 252, 255, 0.79);height:fit-content"
        >
            {{-- Receiver - Vendor --}}
            <div class="receiver"></div>
            @foreach (getReceivers() as $receiver)
                <div data-id="{{ $receiver->user_id }}"
                    class="receiver cursor-pointer flex items-center p-4 hover:bg-gradient-to-r hover:from-slate-100 hover:to-slate-200 transition-all duration-300 border-b border-gray-100/50 group">
                    <div class="relative">
                        <img class="rounded-full w-12 h-12 object-cover ring-2 ring-transparent group-hover:ring-slate-400 transition-all duration-300 shadow-md"
                             src="{{ asset($receiver->banner) }}" alt="{{ $receiver->name }}" />
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white shadow-sm"></div>
                    </div>
                    <div class="flex flex-col ml-3 flex-1 min-w-0">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-800 receiver-name truncate text-sm group-hover:text-slate-900 transition-colors">{{ $receiver->name }}</span>
                            <span class="text-xs text-gray-500 font-medium">4/2/2024</span>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <span class="last-message text-xs text-gray-600 truncate flex-1"></span>
                            <span class="hidden unseen-{{ $receiver->user_id }} bg-slate-900 text-white text-xs px-2 py-1 rounded-full ml-2">
                                <i class="fa-solid fa-circle text-xs"></i>
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Message Area --}}
        <div class="message overflow-y-auto relative">
            <!-- Message Header -->
            <div class="bg-white/90 backdrop-blur-md p-4 sticky top-0 z-10 border-b border-gray-200/50 shadow-sm">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <h3 class="text-lg font-semibold text-gray-800 message-receiver-name">
                        {{-- Receiver Name --}}
                        @if (isset($receiver))
                            {{ $receiver->name }}
                        @else
                            <span class="text-gray-500">Select a contact to start chatting</span>
                        @endif
                    </h3>
                </div>
            </div>
            <!-- Messages Container -->
            <div class="message-area flex flex-col gap-y-4 p-6 min-h-[400px]">
                <!-- Messages will be populated here -->
            </div>
        </div>
    </div>


    <div class="message-input-wrap absolute bottom-0 right-0 w-[580px] bg-white/95 backdrop-blur-md border-t border-gray-200/50">
        <form action="" class="send-message">
            <input type="hidden" name="sender_id" value="{{ Auth::user()->id }}" />
            <input type="hidden" name="receiver_id" />
            <div class="flex items-center p-4 space-x-3">
                <div class="flex-1 relative">
                        <input required name="message_content" id="message_content"
                           placeholder="Type your message..."
                                                                    class="w-full px-4 py-3 pr-20 border border-slate-300 rounded-full focus:outline-none focus:ring-2 focus:ring-slate-800 focus:border-transparent transition-all duration-300 placeholder-gray-500" />
                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <button type="submit" id="send_message"
                                                                        class="w-10 h-10 bg-slate-900 hover:bg-slate-800 text-white rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg">
                                <i class="fa-solid fa-paper-plane text-base"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

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
                $(v).removeClass("bg-gradient-to-r").removeClass("from-blue-50").removeClass("to-indigo-50").removeClass("border-l-4").removeClass("border-blue-500").removeClass("from-teal-50").removeClass("to-cyan-50").removeClass("border-teal-500").removeClass("from-slate-100").removeClass("to-slate-200").removeClass("border-slate-900");
            });
            const messagePatternHTML = `
                        <div class="message overflow-y-auto relative">
                            <div class="bg-white/90 backdrop-blur-md p-4 sticky top-0 z-10 border-b border-gray-200/50 shadow-sm">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                    <h3 class="text-lg font-semibold text-gray-800 message-receiver-name">Select a contact to start chatting</h3>
                                </div>
                            </div>
                            <div class="message-area flex flex-col gap-y-4 p-6 min-h-[400px]">
                            </div>
                        </div>
                    `
            $(".message").replaceWith(messagePatternHTML);
        }

        $(document).on('bot-chat:open', function() {
            if ($('.chat-pannel').is(':visible')) {
                init();
                disableChat(true);
                $('.chat-pannel').hide();
                $('.open-chat-pannel').show();
                document.dispatchEvent(new CustomEvent('vendor-chat:close'));
            }
        });

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
                url: "{{ route('user.message.get-message') }}",
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
                                <div class="sender flex items-end flex-col">
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-3 max-w-[75%] rounded-2xl rounded-br-md shadow-lg">
                                        <p class="message-content text-sm leading-relaxed">${e.message}</p>
                                        <p class="text-end message-time text-xs font-light opacity-80 mt-1">${getCurrentTime(e.created_at)}</p>
                                    </div>
                                </div>  `;
                                $(".message-area").append(senderHTML);
                            } else {
                                receiverHTML = `
                                <div class="receiver flex items-start flex-col">
                                    <div class="bg-white border border-gray-200 text-gray-800 p-3 max-w-[75%] rounded-2xl rounded-bl-md shadow-lg">
                                        <p class="message-content text-sm leading-relaxed">${e.message}</p>
                                        <p class="text-end message-time text-xs font-light text-gray-500 mt-1">${getCurrentTime(e.created_at)}</p>
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
                url: "{{ route('user.message.send-message') }}",
                data: data,
                dataType: "JSON",
                success: function(response) {
                    const receiver = response.receiver;
                    console.log(receiver.id);
                    if (response.status == "success") {
                        if (response.isNewConversation) {
                            const receiverHTML = `
                                <div data-id="${receiver.id}" class="receiver cursor-pointer flex items-center p-4 hover:bg-gradient-to-r hover:from-slate-100 hover:to-slate-200 transition-all duration-300 border-b border-gray-100/50 group bg-gradient-to-r from-slate-100 to-slate-200 border-l-4 border-slate-900">
                                        <div class="relative">
                                            <img class="rounded-full w-12 h-12 object-cover ring-2 ring-transparent group-hover:ring-slate-400 transition-all duration-300 shadow-md"
                                                src="{{ url('/') }}/${receiver.banner}" alt="${receiver.name}" />
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white shadow-sm"></div>
                                        </div>
                                        <div class="flex flex-col ml-3 flex-1 min-w-0">
                                            <div class="flex justify-between items-center">
                                                <span class="font-semibold text-gray-800 receiver-name truncate text-sm group-hover:text-slate-900 transition-colors">${receiver.name}</span>
                                                <span class="text-xs text-gray-500 font-medium">4/2/2024</span>
                                            </div>
                                        </div>
                                </div>
                            `
                            $(".receiver").each(function(i, v) {
                                $(v).removeClass("bg-gradient-to-r").removeClass("from-blue-50").removeClass("to-indigo-50").removeClass("border-l-4").removeClass("border-blue-500").removeClass("from-teal-50").removeClass("to-cyan-50").removeClass("border-teal-500").removeClass("from-slate-100").removeClass("to-slate-200").removeClass("border-slate-900");
                            });
                            $(".receivers").prepend(receiverHTML);
                        }
                        $("#message_content").val("");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const message = jqXHR?.responseJSON?.message || "Unable to send message. Please select a vendor and try again.";
                    Toastify({
                        text: message,
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #ef4444, #f97316)",
                        }
                    }).showToast();
                },
                complete: function() {
                    console.log(12);
                }
            });
        }

        // Change Message Receiver
        $("body").on("click", ".receivers .receiver", function() {
            disableChat(false);
            init();
            const receiverID = $(this).data('id');
            getMessage(senderId, receiverID);
            setInputReceiverID(receiverID);
            $(this).addClass("bg-gradient-to-r from-slate-100 to-slate-200 border-l-4 border-slate-900");
            const receiverName = $(this).find(".receiver-name").html();
            $(".message-receiver-name").html(receiverName);
        })

        // Show chat with shop
        $(".show-chat-pannel").on("click", function() {
            document.dispatchEvent(new CustomEvent('vendor-chat:open'));
            disableChat(false);
            const name = $(this).data("name");
            const banner = $(this).data("banner");
            const receiverID = $(this).data("id");
            $(".receivers .receiver").each(function(i, v) {
                if ($(v).data('id') == receiverID) {
                    init();
                    $(v).addClass("bg-gradient-to-r from-slate-100 to-slate-200 border-l-4 border-slate-900");
                    getMessage(senderId, receiverID);
                    return
                };
            })
            setInputReceiverID(receiverID);
            $(".message-receiver-name").html(name);
            $(".chat-pannel").show(500);
            $(".open-chat-pannel").hide();
        });

        $(".close-chat-pannel").on("click", function() {
            init();
            disableChat(true);
            $(".chat-pannel").hide(500);
            $(".open-chat-pannel").show();
            document.dispatchEvent(new CustomEvent('vendor-chat:close'));
        });

        $(".open-chat-pannel").on("click", function() {
            document.dispatchEvent(new CustomEvent('vendor-chat:open'));
            init();
            disableChat(true);
            $(".chat-pannel").show(500);
            $(this).hide();
        });

        $(".send-message").on("submit", function(e) {
            e.preventDefault();
            const id = $("input[name = 'receiver_id']").val();
            if (!id) {
                disableChat(true);
                Toastify({
                    text: "Please select a vendor before sending a message.",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #ef4444, #f97316)",
                    }
                }).showToast();
                return;
            }

            const data = $(this).serialize();
            const messageContent = $("#message_content").val().trim();
            if (!messageContent) {
                Toastify({
                    text: "Please type a message before sending.",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #ef4444, #f97316)",
                    }
                }).showToast();
                return;
            }
            const currentTime = getCurrentTime(new Date());
            const messageAreaHTML = `
                <div class="sender flex items-end flex-col">
                    <div class="bg-slate-900 text-white p-3 max-w-[75%] rounded-2xl rounded-br-md shadow-lg">
                                <p class="message-content text-sm leading-relaxed">${messageContent}</p>
                                <p class="text-end message-time text-xs opacity-80 mt-1">${currentTime}</p>
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
