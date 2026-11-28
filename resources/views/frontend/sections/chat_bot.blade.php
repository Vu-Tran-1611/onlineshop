<section id="bot-chat-widget" class="fixed bottom-24 right-6 z-[10002]" aria-label="AI shopping assistant">
    <button
        id="bot-chat-launcher"
        type="button"
        aria-controls="bot-chat-panel"
        aria-expanded="false"
        aria-label="Open AI assistant chat"
        class="inline-flex h-12 items-center gap-2 rounded-full bg-white px-4 text-slate-700 shadow-lg ring-1 ring-slate-300 transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2"
    >
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h6m6 5.5L17.5 17H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v12.5Z" />

        </svg>
        <span class="text-xs font-semibold uppercase tracking-wide">assistant</span>
    </button>

    <article
        id="bot-chat-panel"
        role="dialog"
        aria-modal="false"
        aria-label="Ecommerce AI assistant"
        class="fixed inset-y-0 right-0 hidden h-dvh w-full max-w-[880px] flex-col overflow-hidden bg-white shadow-2xl sm:rounded-l-2xl"
    >
        <header class="flex items-center justify-between bg-slate-800 px-4 py-3">
            <div class="min-w-0">
                <h2 class="truncate text-lg font-semibold text-white">Shopping Assistant</h2>
                <p class="text-sm text-slate-200">Ready to help</p>
            </div>
            <div class="flex items-center gap-2">
            <button
                id="bot-chat-new"
                type="button"
                aria-label="Start a new chat"
                class="inline-flex h-9 items-center gap-1 rounded-md px-3 text-sm font-medium text-white ring-1 ring-white/40 transition hover:bg-white hover:text-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600"
            >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                </svg>
                New chat
            </button>
            <button
                id="bot-chat-close"
                type="button"
                aria-label="Close chat"
                class="inline-flex h-9 w-9 cursor- items-center justify-center rounded-md text-white transition hover:bg-white hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600"
            >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="h-5 w-5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
            </div>
        </header>

        <main class="relative min-h-0 flex-1 overflow-hidden bg-white px-4 py-3">
            <ul id="bot-chat-messages" class="flex h-full min-h-64 flex-col gap-4 overflow-y-auto" aria-live="polite"></ul>
        </main>

        <footer class="border-t border-[#D5D9D9] bg-white p-3">
            <form id="bot-chat-form" class="flex items-center gap-2" autocomplete="off">
                <label for="bot-chat-input" class="sr-only">Type your message</label>
                <input
                    id="bot-chat-input"
                    name="message"
                    type="text"
                    maxlength="500"
                    required
                    placeholder="Type your message..."
                    class="min-w-0 flex-1 rounded-xl border border-slate-300 bg-white px-3 py-2 text-md text-slate-900 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-600"
                >
                <button
                    id="bot-chat-send"
                    type="submit"
                    aria-label="Send message"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-700 text-white transition hover:bg-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-700 focus-visible:ring-offset-2"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h16m0 0-6-6m6 6-6 6" />
                    </svg>
                </button>
            </form>
        </footer>
    </article>
</section>

@push('scripts')
    <script>
    (() => {
        const API_URL = @json(route('frontend.bot-chat'));
        const HISTORY_URL = @json(route('frontend.bot-chat.history'));
        const RESET_URL = @json(route('frontend.bot-chat.reset'));
        const BROWSE_URL = @json(route('frontend.bot-chat.browse'));
        const LARAVEL_ASSET_BASE_URL = @json(rtrim(asset('/'), '/'));

        const $launcher = $('#bot-chat-launcher');
        const $panel = $('#bot-chat-panel');
        const $closeButton = $('#bot-chat-close');
        const $form = $('#bot-chat-form');
        const $input = $('#bot-chat-input');
        const $messagesList = $('#bot-chat-messages');
        const $sendButton = $('#bot-chat-send');
        const $newChatButton = $('#bot-chat-new');

        if (
            !$launcher.length ||
            !$panel.length ||
            !$closeButton.length ||
            !$form.length ||
            !$input.length ||
            !$messagesList.length ||
            !$sendButton.length
        ) {
            return;
        }

        const initialGreeting =
            'How can I help you today? I can help with finding products based on your description or answering any questions/concerns about our ecommerce platform like return policy, how cancellation work....';

        const state = {
            open: false,
            // History is loaded once per page, the first time the panel opens
            loaded: false,
            // Block a second request while one is running
            busy: false,
        };

        // Loading indicator stages (ms after the message is sent)
        const TYPING_STAGES = [
            { at: 0, text: 'Assistant is reading your message' },
            { at: 2000, text: 'Assistant is searching' },
            { at: 4000, text: 'Assistant is thinking' },
            { at: 6000, text: 'Assistant is typing' },
        ];
        // Match the label's duration-300 fade
        const STAGE_FADE_MS = 300;
        let typingStageTimers = [];

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function formatPlainText(text) {
            return escapeHtml(text).replace(/\n/g, '<br>');
        }


        function appendMessage(text, sender) {
            const alignmentClass =
                sender === 'customer' ? 'flex justify-end' : 'flex justify-start';

            const bubbleClass =
                sender === 'customer'
                    ? 'max-w-[85%] rounded-2xl rounded-br-md bg-[#F0F2F2] px-3 py-2 text-base leading-relaxed text-[#0F1111]'
                    : 'max-w-[90%] py-1 text-base leading-relaxed text-[#0F1111]';

            const $item = $('<li>').addClass(alignmentClass);
            const $bubble = $('<div>').addClass(bubbleClass);

            if (sender === 'customer') {
                $bubble.text(text);
            } else {
                $bubble.html(formatPlainText(String(text || '')));
            }

            $item.append($bubble);
            $messagesList.append($item);
            $messagesList.scrollTop($messagesList.prop('scrollHeight'));
        }

        function appendBotHtmlMessage(html) {
            const $item = $('<li>').addClass('flex justify-start');

            const $bubble = $('<div>').addClass(
                'max-w-[90%] py-1 text-base leading-relaxed text-[#0F1111]'
            );

            $bubble.html(html || '');

            $bubble.find('p').addClass('mb-2 last:mb-0');
            $bubble.find('ul, ol').addClass('my-2 pl-5');
            $bubble.find('ul').addClass('list-disc');
            $bubble.find('ol').addClass('list-decimal');
            $bubble.find('li').addClass('mb-1');

            $bubble
                .find('a')
                .addClass('break-all text-blue-600 underline hover:text-blue-700')
                .attr('target', '_blank')
                .attr('rel', 'noopener noreferrer');

            $bubble
                .find('img')
                .addClass('my-2 max-w-full rounded-lg');

            $item.append($bubble);
            $messagesList.append($item);
            $messagesList.scrollTop($messagesList.prop('scrollHeight'));
        }

        // Append server-rendered product cards
        function appendProductCards(html) {
            if (!html) {
                return;
            }

            const $item = $('<li>').addClass('w-full').html(html);
            $messagesList.append($item);
            $messagesList.scrollTop($messagesList.prop('scrollHeight'));
        }

        // Append server-rendered subcategory badges
        function appendBadges(html) {
            if (!html) {
                return;
            }

            const $item = $('<li>').addClass('w-full').html(html);
            $messagesList.append($item);
            $messagesList.scrollTop($messagesList.prop('scrollHeight'));
        }

        // Show an assistant reply with its product cards and badges
        function appendBotReply(reply) {
            appendBotHtmlMessage(reply.answer_html);
            appendProductCards(reply.products_html);
            appendBadges(reply.badges_html);
        }

        function appendTypingIndicator() {
            // Keep a single indicator
            removeTypingIndicator();

            const $item = $('<li>', {
                class: 'flex justify-start',
                'data-role': 'bot-loading',
            });

            const $bubble = $('<div>', {
                class: 'max-w-[85%] py-2 text-base leading-relaxed text-slate-500',
            });

            const $typing = $('<div>').addClass('flex items-center gap-2');

            const $label = $('<span>')
                .addClass('whitespace-nowrap text-sm font-medium text-slate-600 transition-opacity duration-300')
                .text(TYPING_STAGES[0].text);

            $typing.append($label);

            // Step through the stages while waiting for the reply; stays on the last one.
            TYPING_STAGES.slice(1).forEach((stage) => {
                typingStageTimers.push(
                    setTimeout(() => {
                        $label.addClass('opacity-0');
                        // Swap text after the fade-out ends
                        typingStageTimers.push(
                            setTimeout(() => {
                                $label.text(stage.text).removeClass('opacity-0');
                            }, STAGE_FADE_MS)
                        );
                    }, stage.at)
                );
            });

            const $dots = $('<span>').addClass('inline-flex items-center gap-1');

            ['0', '100', '200'].forEach((delay) => {
                $dots.append(
                    $('<span>')
                        .addClass('h-2.5 w-2.5 rounded-full bg-slate-400 animate-bounce')
                        .css('animation-delay', delay + 'ms')
                );
            });

            $typing.append($dots);
            $bubble.append($typing);

            $item.append($bubble);
            $messagesList.append($item);
            $messagesList.scrollTop($messagesList.prop('scrollHeight'));
        }

        function removeTypingIndicator() {
            typingStageTimers.forEach(clearTimeout);
            typingStageTimers = [];
            $messagesList.find('[data-role="bot-loading"]').remove();
        }

        function setRequestState(isLoading) {
            state.busy = isLoading;
            $messagesList.find('[data-bot-badge]').prop('disabled', isLoading);
            $input.prop('disabled', isLoading);
            $sendButton.prop('disabled', isLoading);
            $sendButton.toggleClass('opacity-60 cursor-not-allowed', isLoading);
        }

        function extractResponseText(response) {
            if (typeof response === 'string') {
                try {
                    const parsed = JSON.parse(response);

                    return (
                        parsed?.answer ||
                        parsed?.response ||
                        parsed?.message ||
                        response
                    );
                } catch (error) {
                    return response;
                }
            }

            return (
                response?.answer ||
                response?.response ||
                response?.message ||
                response?.data?.answer ||
                response?.data?.response ||
                response?.data?.message ||
                'The assistant returned an empty response.'
            );
        }

        function openPanel() {
            state.open = true;

            $panel.removeClass('hidden').addClass('flex');
            $launcher.addClass('hidden').attr('aria-expanded', 'true');

            $(document).trigger('bot-chat:open');

            if (!state.loaded) {
                state.loaded = true;
                loadHistory();
            }

            $input.trigger('focus');
        }

        // Redraw the saved conversation, or greet when there is none
        function loadHistory() {
            $.ajax({
                url: HISTORY_URL,
                method: 'GET',
                success: function (response) {
                    const messages = response?.messages || [];
                    if (!messages.length) {
                        appendMessage(initialGreeting, 'bot');
                        return;
                    }
                    messages.forEach(function (message) {
                        if (message.role === 'user') {
                            appendMessage(message.content, 'customer');
                            return;
                        }
                        appendBotReply(message);
                    });
                },
                error: function () {
                    appendMessage(initialGreeting, 'bot');
                },
            });
        }

        // Delete the conversation and start over
        function startNewChat() {
            $.ajax({
                url: RESET_URL,
                method: 'POST',
                complete: function () {
                    $messagesList.empty();
                    appendMessage(initialGreeting, 'bot');
                    $input.trigger('focus');
                },
            });
        }

        function closePanel(options = {}) {
            const restoreLauncher = options.restoreLauncher !== false;

            state.open = false;

            $panel.removeClass('flex').addClass('hidden');

            if (restoreLauncher) {
                $launcher.removeClass('hidden');
            }

            $launcher.attr('aria-expanded', 'false');
        }

        $(document).on('vendor-chat:open', function () {
            if (state.open) {
                closePanel({ restoreLauncher: false });
                return;
            }

            $launcher.addClass('hidden');
        });

        $(document).on('vendor-chat:close', function () {
            if (!state.open) {
                $launcher.removeClass('hidden');
            }
        });

        $launcher.on('click', function () {
            if (state.open) {
                closePanel();
                return;
            }

            openPanel();
        });

        $newChatButton.on('click', function () {
            startNewChat();
        });

        $closeButton.on('click', function () {
            closePanel();
        });

        // Show a clicked subcategory directly, without asking the AI
        $messagesList.on('click', '[data-bot-badge]', function () {
            if (state.busy) {
                return;
            }

            const $badge = $(this);
            appendMessage('Show me ' + $badge.text().trim(), 'customer');
            setRequestState(true);
            appendTypingIndicator();

            $.ajax({
                url: BROWSE_URL,
                method: 'POST',
                data: {
                    category_id: $badge.data('category-id'),
                    sub_category_id: $badge.data('sub-category-id'),
                },
                success: function (response) {
                    appendBotReply(response);
                },
                error: function () {
                    appendMessage('Sorry, I could not load these products. Please try again.', 'bot');
                },
                complete: function () {
                    removeTypingIndicator();
                    setRequestState(false);
                    $messagesList.scrollTop($messagesList.prop('scrollHeight'));
                },
            });
        });

        $form.on('submit', function (event) {
            event.preventDefault();

            const message = $input.val().trim();

            if (!message) {
                $input.trigger('focus');
                return;
            }

            appendMessage(message, 'customer');

            $input.val('');
            setRequestState(true);
            appendTypingIndicator();

            $.ajax({
                url: API_URL,
                method: 'POST',
                data: {
                    message: message,
                },
                success: function (response) {
                    if (response?.answer_html) {
                        appendBotReply(response);
                        return;
                    }

                    const botReply = extractResponseText(response);
                    appendMessage(botReply, 'bot');
                },
                error: function (jqXHR) {
                    const errorDetails = jqXHR?.responseJSON?.details;
                    const detailsText =
                        typeof errorDetails === 'string'
                            ? errorDetails
                            : JSON.stringify(errorDetails);

                    const errorMessage =
                        detailsText ||
                        jqXHR?.responseJSON?.message ||
                        'The assistant is unavailable right now. Please try again in a moment.';

                    appendMessage(errorMessage, 'bot');
                },
                complete: function () {
                    removeTypingIndicator();
                    setRequestState(false);
                    $input.trigger('focus');
                    $messagesList.scrollTop($messagesList.prop('scrollHeight'));
                },
            });
        });

        $('body').on('click', '.show-bot-chat', function () {
            openPanel();
        });
    })();
</script>
@endpush
