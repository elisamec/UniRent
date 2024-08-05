document.addEventListener('DOMContentLoaded', function () {
    const messagesDropdown = document.getElementById('messagesDropdown');
    const messageCount = document.getElementById('messageCount');
    const messageList = document.getElementById('messageList');
    
    let contentLoaded = false;

    messagesDropdown.addEventListener('click', function () {
        if (!contentLoaded) {
            fetch('/UniRent/SupportRequest/getSupportReply')
                .then(response => response.json())
                .then(data => {
                    // Update the message count if greater than 0
                    if (data.countReply > 0) {
                        messageCount.textContent = data.countReply;
                        messageCount.style.display = 'inline'; // Show count
                    } else {
                        messageCount.textContent = ''; // Hide count
                        messageCount.style.display = 'none'; // Hide count
                    }
                    
                    // Build the list items
                    const replies = data.replies;
                    const limit = Math.min(replies.length, 4); // Show up to 4 replies
                    messageList.innerHTML = ''; // Clear previous messages
                    for (let i = 0; i < limit; i++) {
                        const reply = replies[i];
                        const isUnread = reply.statusRead === 0 || reply.statusRead === false;
                        const itemClass = isUnread ? 'font-weight-bold requestItem' : 'requestItem';
                        
                        const listItem = document.createElement('a');
                        listItem.className = 'dropdown-item d-flex align-items-center';
                        listItem.href = '#';
                        listItem.innerHTML = `
                            <div class="${itemClass}" data-request="${reply.message}" data-reply="${reply.supportReply}" data-topic="${reply.topic}" data-id="${reply.id}">
                                <div class="text-truncate">${reply.supportReply}</div>
                                <div class="smallMessages text-gray-500">${reply.topic}</div>
                            </div>
                        `;
                        messageList.appendChild(listItem);
                    }
                    
                    contentLoaded = true;
                })
                .catch(error => console.error('Error fetching support replies:', error));
        }
    });

    // Hide message count when the dropdown is closed
    document.addEventListener('click', function(event) {
        if (!messagesDropdown.contains(event.target) && !messageList.contains(event.target)) {
            messageCount.style.display = 'none'; // Hide count
        }
    });

    // Modal handling
    const replyModal = document.getElementById('replyModal'); // Ensure this ID matches your modal
    const closeReply = document.getElementById('closeReply');
    const requestContent = document.getElementById('requestContent');
    const replyContent = document.getElementById('replyContent');
    const requestTopic = document.getElementById('requestTopic');
    
    // Show modal when a requestItem is clicked
    document.addEventListener('click', function(event) {
        if (event.target.closest('.requestItem')) {
            const target = event.target.closest('.requestItem');
            replyModal.style.display = 'block';
            
            // Set modal content
            requestContent.textContent = target.dataset.request;
            replyContent.textContent = target.dataset.reply;
            requestTopic.textContent = target.dataset.topic;

            // Handle closing of the modal
            closeReply.addEventListener('click', function() {
                window.location.href = '/UniRent/SupportRequest/readSupportReply/' + target.dataset.id;
            });
        }
    });

    // Close modal if clicking outside of it
    document.addEventListener('click', function(event) {
        if (replyModal && !replyModal.contains(event.target) && !event.target.closest('.requestItem')) {
            replyModal.style.display = 'none';
        }
    });
});