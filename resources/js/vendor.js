window.Echo.private('message.' + USER.id).listen(
    "MessageEvent",
    (e) => {
        console.log(e);
        function scrollBottom() {
            let messageArea = $(".message ");
            messageArea.scrollTop(messageArea.prop("scrollHeight"));
        }
        const receiverHTML = `
        <div class="receiver d-flex flex-column align-items-start gap-3">
            <div class="bg-light text-dark p-2" style="max-width:70%; font-size:0.875rem; border-radius:0.375rem;">
                <p class="message-content">${e.message}</p>
                <p class="text-end message-time small fw-light">${getCurrentTime(e.created_at)}</p>
            </div>
        </div>  `
        $(".message-area-" + e.sender_id).append(receiverHTML);
        $(".unseen-" + e.sender_id).show();
        scrollBottom();
    }
)