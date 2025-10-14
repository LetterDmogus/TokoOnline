<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Chat Card -->
            <div class="card bg-secondary border-0 shadow-lg">
                <div class="card-header bg-dark text-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">💬 Chat Room</h5>
                    <small>Welcome, {{ Session('username') ?? 'Guest' }}</small>
                </div>

                <div class="card-body p-2" style="height: 400px; overflow-y: auto;">
                    <div class="chat-messages d-flex flex-column gap-2">
                        @foreach ($messages as $msg)
                            <div
                                class="d-flex {{ $msg->sender_id == $amigoId ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                                <div class="p-2 px-3 rounded-4 shadow-sm 
        {{ $msg->sender_id == $amigoId ? 'bg-success text-white' : 'bg-secondary text-light' }}"
                                    style="max-width: 70%;">

                                    <small class="d-block mb-0 text-white-50 {{ $msg->sender_id == $amigoId ? 'text-end text-white-50' : 'text-start text-light-emphasis' }}">
                                        {{ $msg->sender_name }}
                                    </small>

                                    <span class="d-block">
                                        {{ $msg->message }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card-footer bg-dark">
                    <form id="chat-form" class="d-flex gap-2" action="/chat" method="post">
                        @csrf
                        <input type="text" name="message" id="chat-input"
                            class="form-control bg-dark text-light border-secondary" placeholder="Type a message..."
                            required>
                        <button type="submit" class="btn btn-success px-4">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatContainer = document.querySelector('.chat-messages');

    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const message = chatInput.value.trim();
        if (!message) return;

        // Send message via AJAX
        await fetch("{{ route('chat.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content')
            },
            body: JSON.stringify({
                message
            })
        });

        chatInput.value = ""; // clear input
        loadMessages(); // refresh chat right away
    });

    // Polling for new messages every 5s
    setInterval(loadMessages, 5000);
    async function loadMessages() {
        const res = await fetch("{{ route('chat.index') }}");
        const html = await res.text();
        const parser = new DOMParser();
        const newDoc = parser.parseFromString(html, "text/html");
        const newMessages = newDoc.querySelectorAll('.chat-messages > div');

        chatContainer.innerHTML = "";
        newMessages.forEach(msg => chatContainer.appendChild(msg));

        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Scroll to bottom on page load
    chatContainer.scrollTop = chatContainer.scrollHeight;
</script>

</body>

</html>
