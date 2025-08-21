/**
 * 🚀 EZYSKILLS CHAT WIDGET
 * A beautiful, floating chat widget with real-time messaging
 */

class ChatWidget {
    constructor() {
        this.isOpen = false;
        this.currentTab = 'conversations';
        this.currentConversation = null;
        this.conversations = [];
        this.messages = [];
        this.unreadCount = 0;
        this.isTyping = false;
        this.typingTimeout = null;
        this.echo = null;
        
        this.init();
    }

    init() {
        this.createWidget();
        this.bindEvents();
        this.loadConversations();
        this.initializeEcho();
        this.startUnreadCheck();
    }

    createWidget() {
        // Create floating button
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'chat-widget-toggle';
        toggleBtn.innerHTML = `
            <span class="chat-icon">💬</span>
            <div class="chat-notification-badge" style="display: none;">0</div>
        `;
        document.body.appendChild(toggleBtn);

        // Create chat window
        const chatWindow = document.createElement('div');
        chatWindow.className = 'chat-widget-window';
        chatWindow.innerHTML = `
            <div class="chat-header">
                <h3>
                    <span>💬</span>
                    EZYSKILLS Chat
                    <div class="chat-status">
                        <span class="chat-status-dot"></span>
                        <span>Online</span>
                    </div>
                </h3>
                <button class="chat-close-btn" title="Close chat">×</button>
            </div>
            
            <div class="chat-tabs">
                <button class="chat-tab active" data-tab="conversations">💭 Chats</button>
                <button class="chat-tab" data-tab="announcements">📢 Announcements</button>
            </div>
            
            <div class="chat-content">
                <div class="chat-messages" id="chat-messages">
                    <div class="chat-loading">
                        <div class="chat-loading-spinner"></div>
                        <span>Loading conversations...</span>
                    </div>
                </div>
                
                <div class="chat-input-area" id="chat-input-area" style="display: none;">
                    <button class="chat-file-btn" title="Attach file" id="chat-file-btn">
                        📎
                    </button>
                    <div class="chat-input-wrapper">
                        <textarea 
                            class="chat-input" 
                            id="chat-input" 
                            placeholder="Type your message..."
                            rows="1"
                        ></textarea>
                    </div>
                    <button class="chat-send-btn" title="Send message" id="chat-send-btn">
                        ➤
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(chatWindow);

        // Store references
        this.toggleBtn = toggleBtn;
        this.chatWindow = chatWindow;
        this.messagesContainer = chatWindow.querySelector('#chat-messages');
        this.inputArea = chatWindow.querySelector('#chat-input-area');
        this.chatInput = chatWindow.querySelector('#chat-input');
        this.sendBtn = chatWindow.querySelector('#chat-send-btn');
        this.fileBtn = chatWindow.querySelector('#chat-file-btn');
        this.closeBtn = chatWindow.querySelector('.chat-close-btn');
        this.tabs = chatWindow.querySelectorAll('.chat-tab');
        this.notificationBadge = toggleBtn.querySelector('.chat-notification-badge');
    }

    bindEvents() {
        // Toggle button
        this.toggleBtn.addEventListener('click', () => this.toggleChat());
        
        // Close button
        this.closeBtn.addEventListener('click', () => this.closeChat());
        
        // Tab switching
        this.tabs.forEach(tab => {
            tab.addEventListener('click', () => this.switchTab(tab.dataset.tab));
        });
        
        // Send message
        this.sendBtn.addEventListener('click', () => this.sendMessage());
        
        // Enter key to send
        this.chatInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });
        
        // Auto-resize textarea
        this.chatInput.addEventListener('input', () => this.autoResizeTextarea());
        
        // File upload
        this.fileBtn.addEventListener('click', () => this.openFileUpload());
        
        // Click outside to close - with improved handling
        document.addEventListener('click', (e) => {
            // Only close if chat is open and click is outside both chat window and toggle button
            if (this.isOpen && 
                !this.chatWindow.contains(e.target) && 
                !this.toggleBtn.contains(e.target)) {
                this.closeChat();
            }
        });
        
        // Prevent chat window clicks from bubbling up
        this.chatWindow.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }

    toggleChat() {
        if (this.isOpen) {
            this.closeChat();
        } else {
            this.openChat();
        }
    }

    openChat() {
        this.isOpen = true;
        this.chatWindow.classList.add('active');
        this.toggleBtn.style.transform = 'scale(0.9)';
        
        // Focus input if on conversations tab
        if (this.currentTab === 'conversations' && this.currentConversation) {
            setTimeout(() => this.chatInput.focus(), 300);
        }
        
        // Mark as read
        if (this.currentConversation) {
            this.markAsRead(this.currentConversation.id);
        }
        
        // Hide notification badge
        this.hideNotificationBadge();
    }

    closeChat() {
        this.isOpen = false;
        this.chatWindow.classList.remove('active');
        this.toggleBtn.style.transform = '';
        this.chatInput.blur();
    }

    switchTab(tabName) {
        this.currentTab = tabName;
        
        // Update tab styles
        this.tabs.forEach(tab => {
            tab.classList.toggle('active', tab.dataset.tab === tabName);
        });
        
        // Show/hide input area
        this.inputArea.style.display = tabName === 'conversations' ? 'flex' : 'none';
        
        // Load content based on tab
        if (tabName === 'conversations') {
            this.loadConversations();
        } else {
            this.loadAnnouncements();
        }
    }

    async loadConversations() {
        try {
            this.showLoading('Loading conversations...');
            
            const response = await fetch('/chat/conversations', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) throw new Error('Failed to load conversations');
            
                         const data = await response.json();
             this.conversations = data.conversations || [];
             
             // Sort conversations by most recent message
             this.conversations.sort((a, b) => {
                 const aTime = a.last_message?.created_at ? new Date(a.last_message.created_at) : new Date(0);
                 const bTime = b.last_message?.created_at ? new Date(b.last_message.created_at) : new Date(0);
                 return bTime - aTime;
             });
             
             this.renderConversations();
        } catch (error) {
            console.error('Error loading conversations:', error);
            this.showError('Failed to load conversations');
        }
    }

    renderConversations() {
        if (this.conversations.length === 0) {
            this.messagesContainer.innerHTML = `
                <div class="chat-empty-state">
                    <div class="chat-empty-state-icon">💬</div>
                    <div class="chat-empty-state-text">No conversations yet</div>
                    <div class="chat-empty-state-subtext">Start chatting with your course mates!</div>
                </div>
            `;
            return;
        }

        const conversationsHtml = this.conversations.map(conv => `
            <div class="chat-conversation-item" data-conversation-id="${conv.id}">
                <div class="chat-conversation-avatar">
                    ${conv.type === 'course' && conv.course && conv.course.image ? 
                        `<img src="/storage/${conv.course.image}" alt="${conv.title}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">` :
                        (conv.type === 'course' ? '📚' : (conv.type === 'private' ? '👥' : '🆘'))
                    }
                </div>
                <div class="chat-conversation-content">
                    <div class="chat-conversation-title">${conv.title}</div>
                    <div class="chat-conversation-preview">
                        ${conv.last_message && conv.last_message.content ? 
                            `<span class="chat-sender-name">${conv.last_message.sender || 'Someone'}:</span> ` +
                            (conv.last_message.content.length > 40 ? conv.last_message.content.substring(0, 40) + '...' : conv.last_message.content) : 
                            'Start a conversation'
                        }
                    </div>
                </div>
                <div class="chat-conversation-meta">
                    <div class="chat-conversation-time">
                        ${conv.last_message && conv.last_message.created_at ? this.formatTime(conv.last_message.created_at) : ''}
                    </div>
                    ${conv.unread_count > 0 ? `<div class="chat-unread-badge">${conv.unread_count}</div>` : ''}
                </div>
            </div>
        `).join('');

        this.messagesContainer.innerHTML = conversationsHtml;
        
        // Add click events to conversations
        this.messagesContainer.querySelectorAll('.chat-conversation-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent event bubbling
                const conversationId = item.dataset.conversationId;
                this.openConversation(conversationId);
            });
        });
    }

    async openConversation(conversationId) {
        this.currentConversation = this.conversations.find(c => c.id == conversationId);
        if (!this.currentConversation) return;

        // Update UI to show conversation
        this.messagesContainer.innerHTML = `
            <div class="chat-conversation-header">
                <button class="chat-back-btn" id="chat-back-btn">← Back</button>
                <h4>${this.currentConversation.title}</h4>
            </div>
            <div class="chat-messages-list" id="chat-messages-list">
                <div class="chat-loading">
                    <div class="chat-loading-spinner"></div>
                    <span>Loading messages...</span>
                </div>
            </div>
        `;

        // Add back button event
        document.getElementById('chat-back-btn').addEventListener('click', (e) => {
            e.stopPropagation();
            this.renderConversations();
        });

        // Load messages
        await this.loadMessages(conversationId);
        
        // Mark as read
        this.markAsRead(conversationId);
    }

    async loadMessages(conversationId) {
        try {
            const response = await fetch(`/chat/conversations/${conversationId}/messages`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) throw new Error('Failed to load messages');
            
            const data = await response.json();
            this.messages = data.messages || [];
            
            this.renderMessages();
        } catch (error) {
            console.error('Error loading messages:', error);
            this.showError('Failed to load messages');
        }
    }

    renderMessages() {
        const messagesList = document.getElementById('chat-messages-list');
        if (!messagesList) return;

        if (this.messages.length === 0) {
            messagesList.innerHTML = `
                <div class="chat-empty-state">
                    <div class="chat-empty-state-icon">💬</div>
                    <div class="chat-empty-state-text">No messages yet</div>
                    <div class="chat-empty-state-subtext">Start the conversation!</div>
                </div>
            `;
            return;
        }

        const messagesHtml = this.messages.map(message => this.renderMessage(message)).join('');
        messagesList.innerHTML = messagesHtml;
        
        // Scroll to bottom
        messagesList.scrollTop = messagesList.scrollHeight;
    }

    renderMessage(message) {
        const isOwn = message.sender.id == window.currentUserId;
        const messageClass = isOwn ? 'chat-message own' : 'chat-message';
        
        let contentHtml = '';
        if (message.type === 'text') {
            contentHtml = `<div class="chat-message-text">${this.escapeHtml(message.content)}</div>`;
        } else if (message.type === 'image') {
            contentHtml = `
                <div class="chat-message-file">
                    <img src="${message.file_url}" alt="Image" onclick="window.open('${message.file_url}', '_blank')">
                    <div class="file-info">Image</div>
                </div>
            `;
        } else if (message.type === 'file') {
            contentHtml = `
                <div class="chat-message-file" onclick="window.open('${message.file_url}', '_blank')">
                    <div class="file-info">📎 File</div>
                </div>
            `;
        }

        return `
            <div class="${messageClass}">
                <div class="chat-message-avatar">
                    ${message.sender.name.charAt(0).toUpperCase()}
                </div>
                <div class="chat-message-content">
                    <div class="chat-message-sender">
                        ${isOwn ? 'You' : this.escapeHtml(message.sender.name)}
                        ${message.sender.is_admin ? ' <span class="admin-badge">👑</span>' : ''}
                    </div>
                    <div class="chat-message-bubble">
                        ${contentHtml}
                        <span class="chat-message-time">${this.formatTime(message.created_at)}</span>
                    </div>
                </div>
            </div>
        `;
    }

    async sendMessage() {
        const content = this.chatInput.value.trim();
        if (!content || !this.currentConversation) return;

        // Disable input and button
        this.chatInput.disabled = true;
        this.sendBtn.disabled = true;

        try {
            const response = await fetch('/chat/send-message', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    conversation_id: this.currentConversation.id,
                    content: content,
                    type: 'text'
                })
            });

            if (!response.ok) throw new Error('Failed to send message');

            const data = await response.json();
            
            // Add message to UI
            this.messages.push(data.message);
            this.renderMessages();
            
            // Clear input
            this.chatInput.value = '';
            this.autoResizeTextarea();
            
        } catch (error) {
            console.error('Error sending message:', error);
            this.showError('Failed to send message');
        } finally {
            // Re-enable input and button
            this.chatInput.disabled = false;
            this.sendBtn.disabled = false;
            this.chatInput.focus();
        }
    }

    async openFileUpload() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*,video/*,.pdf,.doc,.docx,.txt';
        input.multiple = false;
        
        input.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;
            
            await this.uploadFile(file);
        });
        
        input.click();
    }

    async uploadFile(file) {
        if (!this.currentConversation) return;

        const formData = new FormData();
        formData.append('conversation_id', this.currentConversation.id);
        formData.append('file', file);
        formData.append('content', `Sent ${file.name}`);
        formData.append('type', this.getFileType(file.type));

        try {
            this.showLoading('Uploading file...');
            
            const response = await fetch('/chat/send-message', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (!response.ok) throw new Error('Failed to upload file');

            const data = await response.json();
            
            // Add message to UI
            this.messages.push(data.message);
            this.renderMessages();
            
        } catch (error) {
            console.error('Error uploading file:', error);
            this.showError('Failed to upload file');
        } finally {
            // Always clear loading state
            this.renderMessages();
        }
    }

    async loadAnnouncements() {
        try {
            this.showLoading('Loading announcements...');
            
            // Get current course ID if on course page
            const courseId = this.getCurrentCourseId();
            if (!courseId) {
                this.messagesContainer.innerHTML = `
                    <div class="chat-empty-state">
                        <div class="chat-empty-state-icon">📢</div>
                        <div class="chat-empty-state-text">No course selected</div>
                        <div class="chat-empty-state-subtext">Navigate to a course page to see announcements</div>
                    </div>
                `;
                return;
            }

            const response = await fetch(`/announcements/course/${courseId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) throw new Error('Failed to load announcements');
            
            const data = await response.json();
            const announcements = data.announcements || [];
            
            this.renderAnnouncements(announcements);
        } catch (error) {
            console.error('Error loading announcements:', error);
            this.showError('Failed to load announcements');
        }
    }

    renderAnnouncements(announcements) {
        if (announcements.length === 0) {
            this.messagesContainer.innerHTML = `
                <div class="chat-empty-state">
                    <div class="chat-empty-state-icon">📢</div>
                    <div class="chat-empty-state-text">No announcements</div>
                    <div class="chat-empty-state-subtext">Check back later for updates</div>
                </div>
            `;
            return;
        }

        const announcementsHtml = announcements.map(announcement => `
            <div class="chat-announcement ${announcement.priority}">
                <div class="chat-announcement-header">
                    <span class="chat-announcement-icon">
                        ${this.getPriorityIcon(announcement.priority)}
                    </span>
                    <h5 class="chat-announcement-title">${this.escapeHtml(announcement.title)}</h5>
                </div>
                <p class="chat-announcement-content">${this.escapeHtml(announcement.content)}</p>
                <span class="chat-announcement-time">
                    ${this.formatTime(announcement.created_at)} • By ${announcement.admin.name}
                </span>
            </div>
        `).join('');

        this.messagesContainer.innerHTML = announcementsHtml;
    }

    async markAsRead(conversationId) {
        try {
            await fetch(`/chat/conversations/${conversationId}/messages`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
        } catch (error) {
            console.error('Error marking as read:', error);
        }
    }

    initializeEcho() {
        // Initialize Laravel Echo for real-time updates
        if (window.Echo) {
            this.echo = window.Echo;
            
            // Listen for new messages
            this.echo.private(`conversation.${this.currentConversation?.id}`)
                .listen('MessageSent', (e) => {
                    if (e.message.sender_id != window.currentUserId) {
                        this.messages.push(e.message);
                        this.renderMessages();
                        this.incrementUnreadCount();
                    }
                });
        }
    }

    startUnreadCheck() {
        // Check for unread messages every 30 seconds
        setInterval(async () => {
            if (!this.isOpen) {
                await this.checkUnreadCount();
            }
        }, 30000);
    }

    async checkUnreadCount() {
        try {
            const response = await fetch('/chat/conversations', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                const totalUnread = data.conversations.reduce((sum, conv) => sum + (conv.unread_count || 0), 0);
                this.updateUnreadCount(totalUnread);
            }
        } catch (error) {
            console.error('Error checking unread count:', error);
        }
    }

    updateUnreadCount(count) {
        this.unreadCount = count;
        if (count > 0) {
            this.notificationBadge.textContent = count > 99 ? '99+' : count;
            this.notificationBadge.style.display = 'flex';
        } else {
            this.hideNotificationBadge();
        }
    }

    incrementUnreadCount() {
        this.updateUnreadCount(this.unreadCount + 1);
    }

    hideNotificationBadge() {
        this.notificationBadge.style.display = 'none';
    }

    showLoading(message) {
        this.messagesContainer.innerHTML = `
            <div class="chat-loading">
                <div class="chat-loading-spinner"></div>
                <span>${message}</span>
            </div>
        `;
    }

    showError(message) {
        this.messagesContainer.innerHTML = `
            <div class="chat-empty-state">
                <div class="chat-empty-state-icon">⚠️</div>
                <div class="chat-empty-state-text">Error</div>
                <div class="chat-empty-state-subtext">${message}</div>
            </div>
        `;
    }

    autoResizeTextarea() {
        const textarea = this.chatInput;
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 100) + 'px';
    }

    getCurrentCourseId() {
        // Try to get course ID from various sources
        const courseId = 
            window.courseId || 
            document.querySelector('[data-course-id]')?.dataset.courseId ||
            window.location.pathname.match(/\/course\/(\d+)/)?.[1];
        
        return courseId;
    }

    getFileType(mimeType) {
        if (mimeType.startsWith('image/')) return 'image';
        if (mimeType.startsWith('video/')) return 'video';
        return 'file';
    }

    getPriorityIcon(priority) {
        const icons = {
            urgent: '🚨',
            high: '⚠️',
            normal: 'ℹ️',
            low: '💡'
        };
        return icons[priority] || 'ℹ️';
    }

    formatTime(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;
        
        if (diff < 60000) return 'Just now';
        if (diff < 3600000) return Math.floor(diff / 60000) + 'm ago';
        if (diff < 86400000) return Math.floor(diff / 3600000) + 'h ago';
        if (diff < 604800000) return Math.floor(diff / 86400000) + 'd ago';
        
        return date.toLocaleDateString();
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize chat widget when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Check if user is authenticated
    if (window.isAuthenticated) {
        window.chatWidget = new ChatWidget();
    }
});

// Export for global access
window.ChatWidget = ChatWidget;
