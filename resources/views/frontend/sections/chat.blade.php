<div
    class="open-chat-pannel text-lg bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white fixed bottom-6 right-6 px-6 py-4 rounded-full shadow-2xl cursor-pointer transition-all duration-300 hover:scale-105 hover:shadow-xl backdrop-blur-sm border border-white/20">
    <div class="flex items-center space-x-2">
        <i class="fa-regular fa-comment text-xl"></i>
        <span class="font-medium">Chat</span>
    </div>
</div>

{{-- Chat Panel --}}
<div class="chat-pannel z-[100] hidden shadow-2xl bg-white/95 backdrop-blur-xl w-[750px] fixed top-0 h-full right-0 border-l border-gray-200/50">
    <!-- Modern Header -->
    <div class="p-6 flex justify-between items-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
        <div class="flex items-center space-x-3">
            <div class="w-3 h-3 bg-white/30 rounded-full animate-pulse"></div>
            <div class="w-2 h-2 bg-white/50 rounded-full animate-pulse delay-100"></div>
            <h2 class="text-2xl font-bold tracking-wide">Messages</h2>
        </div>
        <button class="close-chat-pannel text-white hover:text-blue-200 cursor-pointer text-2xl transition-colors duration-300 hover:rotate-180 transform">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>

    <div class="grid grid-cols-[280px_auto] h-full bg-gradient-to-br from-gray-50 to-blue-50/30">
        {{-- Vendors List --}}
        <div class="receivers overflow-y-auto border-r border-gray-200/50 bg-white/70 backdrop-blur-sm">
            {{-- Receiver - Vendor --}}
            <div class="receiver"></div>
            @foreach (getReceivers() as $receiver)
                <div data-id="{{ $receiver->user_id }}"
                    class="receiver cursor-pointer flex items-center p-4 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 border-b border-gray-100/50 group">
                    <div class="relative">
                        <img class="rounded-full w-12 h-12 object-cover ring-2 ring-transparent group-hover:ring-blue-300 transition-all duration-300 shadow-md"
                             src="{{ asset($receiver->banner) }}" alt="{{ $receiver->name }}" />
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white shadow-sm"></div>
                    </div>
                    <div class="flex flex-col ml-3 flex-1 min-w-0">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-800 receiver-name truncate text-sm group-hover:text-blue-700 transition-colors">{{ $receiver->name }}</span>
                            <span class="text-xs text-gray-500 font-medium">4/2/2024</span>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <span class="last-message text-xs text-gray-600 truncate flex-1"></span>
                            <span class="hidden unseen-{{ $receiver->user_id }} bg-blue-500 text-white text-xs px-2 py-1 rounded-full ml-2">
                                <i class="fa-solid fa-circle text-xs"></i>
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Message Area --}}
        <div class="message overflow-y-auto bg-gradient-to-br from-gray-50 to-white max-h-[550px] relative">
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

    <!-- Modern Message Input -->
    <div class="absolute bottom-0 right-0 w-[470px] bg-white/95 backdrop-blur-md border-t border-gray-200/50">
        <form action="" class="send-message">
            <input type="hidden" name="sender_id" value="{{ Auth::user()->id }}" />
            <input type="hidden" name="receiver_id" />
            <div class="flex items-center p-4 space-x-3">
                <div class="flex-1 relative">
                    <input required name="message_content" id="message_content"
                           placeholder="Type your message..."
                           class="w-full px-4 py-3 pr-12 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 placeholder-gray-500" />
                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <button type="submit" id="send_message"
                                class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg">
                            <i class="fa-solid fa-paper-plane text-sm"></i>
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
                $(v).removeClass("bg-gradient-to-r").removeClass("from-blue-50").removeClass("to-indigo-50").removeClass("border-l-4").removeClass("border-blue-500");
            });
            const messagePatternHTML = `
                        <div class="message overflow-y-auto bg-gradient-to-br from-gray-50 to-white max-h-[550px] relative">
                            <div class="bg-white/90 backdrop-blur-md p-4 sticky top-0 z-10 border-b border-gray-200/50 shadow-sm">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                    <h3 class="text-lg font-semibold text-gray-800 message-receiver-name">${name}</h3>
                                </div>
                            </div>
                            <div class="message-area flex flex-col gap-y-4 p-6 min-h-[400px]">
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
                                <div data-id="${receiver.id}" class="receiver cursor-pointer flex items-center p-4 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 border-b border-gray-100/50 group bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500">
                                        <div class="relative">
                                            <img class="rounded-full w-12 h-12 object-cover ring-2 ring-transparent group-hover:ring-blue-300 transition-all duration-300 shadow-md"
                                                src="{{ asset('${receiver.banner}') }}" alt="${receiver.name}" />
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white shadow-sm"></div>
                                        </div>
                                        <div class="flex flex-col ml-3 flex-1 min-w-0">
                                            <div class="flex justify-between items-center">
                                                <span class="font-semibold text-gray-800 receiver-name truncate text-sm group-hover:text-blue-700 transition-colors">${receiver.name}</span>
                                                <span class="text-xs text-gray-500 font-medium">4/2/2024</span>
                                            </div>
                                        </div>
                                </div>
                            `
                            $(".receiver").each(function(i, v) {
                                $(v).removeClass("bg-gradient-to-r").removeClass("from-blue-50").removeClass("to-indigo-50").removeClass("border-l-4").removeClass("border-blue-500");
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
            disableChat(false);
            init();
            const receiverID = $(this).data('id');
            getMessage(senderId, receiverID);
            setInputReceiverID(receiverID);
            $(this).addClass("bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500");
            const receiverName = $(this).find(".receiver-name").html();
            $(".message-receiver-name").html(receiverName);
        })

        // Show chat with shop
        $(".show-chat-pannel").on("click", function() {
            disableChat(false);
            const name = $(this).data("name");
            const banner = $(this).data("banner");
            const receiverID = $(this).data("id");
            $(".receivers .receiver").each(function(i, v) {
                if ($(v).data('id') == receiverID) {
                    init();
                    $(v).addClass("bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500");
                    getMessage(senderId, receiverID);
                    return
                };
            })
            setInputReceiverID(receiverID);
            $(".message-receiver-name").html(name);
            $(".chat-pannel").show(500);
        });

        $(".close-chat-pannel").on("click", function() {
            init();
            disableChat(true);
            $(".chat-pannel").hide(500);
        });

        $(".open-chat-pannel").on("click", function() {
            init();
            disableChat();
            $(".chat-pannel").show(500);
        });

        $(".send-message").on("submit", function(e) {
            e.preventDefault();
            const id = $("input[name = 'receiver_id']").val();
            const data = $(this).serialize();
            const messageContent = $("#message_content").val();
            const currentTime = getCurrentTime(new Date());
            const messageAreaHTML = `
                <div class="sender flex items-end flex-col">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-3 max-w-[75%] rounded-2xl rounded-br-md shadow-lg">
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
