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
        class="mt-3 hidden h-[min(78vh,720px)] w-[calc(100vw-2.5rem)] max-w-[640px] flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
    >
        <header class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-4 py-3">
            <div class="min-w-0">
                <h2 class="truncate text-lg font-semibold text-slate-900">Shopping Assistant</h2>
                <p class="text-sm text-slate-600">Ready to help</p>
            </div>
            <button
                id="bot-chat-close"
                type="button"
                aria-label="Close chat"
                class="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-600 transition hover:bg-slate-200 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600"
            >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </header>

        <main class="min-h-0 flex-1 bg-slate-50 px-4 py-3">
            <ul id="bot-chat-messages" class="flex h-full min-h-64 flex-col gap-3 overflow-y-auto" aria-live="polite"></ul>
        </main>

        <footer class="border-t border-slate-200 bg-white p-3">
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
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white transition hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-700 focus-visible:ring-offset-2"
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
        const LARAVEL_ASSET_BASE_URL = @json(rtrim(asset('/'), '/'));

        const $launcher = $('#bot-chat-launcher');
        const $panel = $('#bot-chat-panel');
        const $closeButton = $('#bot-chat-close');
        const $form = $('#bot-chat-form');
        const $input = $('#bot-chat-input');
        const $messagesList = $('#bot-chat-messages');
        const $sendButton = $('#bot-chat-send');

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
            greeted: false,
        };

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
                    ? 'max-w-[85%] rounded-2xl rounded-br-md bg-blue-600 px-3 py-2 text-base leading-relaxed text-white shadow-sm'
                    : 'max-w-[90%] rounded-2xl rounded-bl-md bg-gray-100 px-3 py-2 text-base leading-relaxed text-slate-800 shadow-sm';

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
                'max-w-[90%] rounded-2xl rounded-bl-md bg-gray-100 px-3 py-2 text-base leading-relaxed text-slate-800 shadow-sm'
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
                .addClass('my-2 max-w-full rounded-lg border border-slate-300');

            $item.append($bubble);
            $messagesList.append($item);
            $messagesList.scrollTop($messagesList.prop('scrollHeight'));
        }

        function appendTypingIndicator() {
            const $item = $('<li>', {
                class: 'flex justify-start',
                'data-role': 'bot-loading',
            });

            const $bubble = $('<div>', {
                class: 'max-w-[85%] rounded-2xl rounded-bl-md bg-gray-100 px-4 py-3 text-base leading-relaxed text-slate-500 shadow-sm',
            });

            const $typing = $('<div>').addClass('flex items-center gap-2');

            $typing.append(
                $('<span>')
                    .addClass('text-sm font-medium text-slate-600')
                    .text('The assistant is typing')
            );

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
            $messagesList.find('[data-role="bot-loading"]').remove();
        }

        function setRequestState(isLoading) {
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

            if (!state.greeted) {
                appendMessage(initialGreeting, 'bot');
                state.greeted = true;
            }

            $input.trigger('focus');
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

        $closeButton.on('click', function () {
            closePanel();
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
                        appendBotHtmlMessage(response.answer_html);
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
