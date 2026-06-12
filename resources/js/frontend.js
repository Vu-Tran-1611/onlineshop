import MarkdownIt from 'markdown-it';

window.ChatMarkdown = new MarkdownIt({
    html: false,
    breaks: true,
    linkify: true,
});

window.Echo.private('message.' + USER.id).listen(
    "MessageEvent",
    (e) => {
        console.log(e);
        function scrollBottom() {
            let messageArea = $(".message ");
            messageArea.scrollTop(messageArea.prop("scrollHeight"));
        }
        const receiverHTML = `
        <div class="receiver flex items-start flex-col gap-y-3">
            <div class="bg-white text-black p-2 max-w-[70%] text-sm rounded-md">
                <p class="message-content">${e.message}</p>
                <p class="text-end message-time text-xs font-light">${getCurrentTime(e.created_at)}</p>
            </div>
        </div>  `
        $(".message-area-" + e.sender_id).append(receiverHTML);
        $(".unseen-" + e.sender_id).show();
        scrollBottom();
    }
)
