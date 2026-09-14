<!-- AI Chatbot Floating Widget for Vật Tư Nông Nghiệp 58 -->
<div id="ai-chat-widget-root" style="font-family: inherit;">
    <!-- Floating Trigger Button -->
    <button id="ai-chat-launcher" type="button" aria-label="Mở Trợ Lý Nông Nghiệp AI" title="Trợ Lý Nông Nghiệp 58 (AI Tư Vấn 24/7)">
        <div class="ai-launcher-inner">
            <span class="ai-sparkle">✨</span>
            <svg class="ai-chat-icon" viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <svg class="ai-close-icon" viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </div>
        <span class="ai-badge-pulse">Tư vấn AI</span>
    </button>

    <!-- Chat Window Container -->
    <div id="ai-chat-window" class="ai-window-hidden" role="dialog" aria-modal="true" aria-labelledby="ai-chat-title">
        <!-- Header -->
        <div class="ai-chat-header">
            <div class="ai-header-info">
                <div class="ai-avatar">
                    🌱
                    <span class="ai-status-dot" title="Sẵn sàng tư vấn"></span>
                </div>
                <div>
                    <h3 id="ai-chat-title" class="ai-header-title">Trợ Lý Nông Nghiệp 58</h3>
                    <p class="ai-header-subtitle">Tư vấn kỹ thuật & Thuốc BVTV (Gemini AI)</p>
                </div>
            </div>
            <div class="ai-header-actions">
                <button type="button" id="ai-chat-reset-btn" class="ai-action-btn" title="Làm mới cuộc trò chuyện">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                        <path d="M3 3v5h5"></path>
                    </svg>
                </button>
                <button type="button" id="ai-chat-close-btn" class="ai-action-btn" title="Thu nhỏ">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Quick Prompts Chips -->
        <div class="ai-quick-chips">
            <span class="ai-chip" data-prompt="Lúa đang làm đòng bị đạo ôn và rầy nâu thì phun thuốc gì?">🌾 Đạo ôn & rầy lúa</span>
            <span class="ai-chip" data-prompt="Sầu riêng ra đọt non bị rầy nhảy chích hút xử lý sao?">🍈 Rầy sầu riêng</span>
            <span class="ai-chip" data-prompt="Cách phòng trị bệnh nấm thán thư trên cây ăn trái?">🍊 Bệnh thán thư</span>
            <span class="ai-chip" data-prompt="Quy trình bón phân NPK nuôi trái lớn đều bóng vỏ?">🧪 Bón phân nuôi trái</span>
        </div>

        <!-- Message Body -->
        <div id="ai-chat-messages" class="ai-chat-body">
            <!-- Welcome Bot Message -->
            <div class="ai-msg ai-msg-bot">
                <div class="ai-msg-avatar">🌱</div>
                <div class="ai-msg-bubble">
                    <p>Chào bà con! Em là <strong>Trợ Lý Nông Nghiệp 58</strong> 🌾.</p>
                    <p>Bà con đang cần tư vấn về <strong>sâu bệnh hại</strong> (rầy nâu, đạo ôn, thán thư...) hay <strong>kỹ thuật bón phân</strong> cho cây trồng nào cứ đặt câu hỏi bên dưới nhé!</p>
                </div>
            </div>
        </div>

        <!-- Typing Indicator -->
        <div id="ai-chat-typing" class="ai-typing-indicator ai-typing-hidden">
            <div class="ai-msg-avatar">🌱</div>
            <div class="ai-typing-bubble">
                <span class="ai-dot"></span>
                <span class="ai-dot"></span>
                <span class="ai-dot"></span>
                <span class="ai-typing-text">Trợ lý đang suy nghĩ...</span>
            </div>
        </div>

        <!-- Footer / Input Box -->
        <form id="ai-chat-form" class="ai-chat-footer" autocomplete="off">
            <div class="ai-input-wrap">
                <textarea id="ai-chat-input" rows="1" placeholder="Nhập câu hỏi của bà con... (Enter để gửi)" required></textarea>
                <button type="submit" id="ai-chat-send-btn" aria-label="Gửi tin nhắn" disabled>
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
                    </svg>
                </button>
            </div>
            <div class="ai-footer-note">
                AI hỗ trợ tư vấn tham khảo • Tuân thủ nguyên tắc 4 đúng khi dùng thuốc BVTV
            </div>
        </form>
    </div>
</div>

<style>
/* Scoped styles for AI Chatbot */
#ai-chat-widget-root {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 999999;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

#ai-chat-launcher {
    position: relative;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: #ffffff;
    border: 2px solid rgba(255, 255, 255, 0.4);
    box-shadow: 0 8px 24px rgba(46, 125, 50, 0.45);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    outline: none;
    padding: 0;
}

#ai-chat-launcher:hover {
    transform: scale(1.08);
    box-shadow: 0 12px 30px rgba(46, 125, 50, 0.6);
}

.ai-launcher-inner {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ai-sparkle {
    position: absolute;
    top: -8px;
    right: -8px;
    font-size: 14px;
    animation: aiSparklePulse 2s infinite ease-in-out;
}

.ai-close-icon {
    display: none;
}

#ai-chat-launcher.ai-active .ai-chat-icon {
    display: none;
}

#ai-chat-launcher.ai-active .ai-close-icon {
    display: block;
}

.ai-badge-pulse {
    position: absolute;
    top: -6px;
    left: -14px;
    background: #ff9800;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 12px;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(255, 152, 0, 0.5);
    letter-spacing: 0.3px;
    animation: aiBadgeFloat 3s infinite ease-in-out;
}

/* Chat Window */
#ai-chat-window {
    position: fixed;
    bottom: 96px;
    right: 24px;
    width: 390px;
    max-width: calc(100vw - 32px);
    height: 580px;
    max-height: calc(100vh - 120px);
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 16px 48px rgba(0, 0, 0, 0.18), 0 0 0 1px rgba(0, 0, 0, 0.06);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    transform-origin: bottom right;
    z-index: 999999;
}

#ai-chat-window.ai-window-hidden {
    opacity: 0;
    visibility: hidden;
    transform: scale(0.85) translateY(20px);
    pointer-events: none;
}

/* Header */
.ai-chat-header {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: #ffffff;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.ai-header-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.ai-avatar {
    position: relative;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.ai-status-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    background: #4caf50;
    border: 2px solid #ffffff;
    border-radius: 50%;
}

.ai-header-title {
    margin: 0;
    font-size: 15px;
    font-weight: 700;
    color: #ffffff;
    line-height: 1.2;
}

.ai-header-subtitle {
    margin: 2px 0 0;
    font-size: 11px;
    color: rgba(255, 255, 255, 0.85);
    line-height: 1.2;
}

.ai-header-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}

.ai-action-btn {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: #ffffff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s ease;
    padding: 0;
}

.ai-action-btn:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Quick Chips */
.ai-quick-chips {
    display: flex;
    gap: 6px;
    padding: 10px 14px;
    background: #f4fbf4;
    border-bottom: 1px solid #e8f5e9;
    overflow-x: auto;
    white-space: nowrap;
    scrollbar-width: none;
}

.ai-quick-chips::-webkit-scrollbar {
    display: none;
}

.ai-chip {
    display: inline-block;
    background: #ffffff;
    color: #2e7d32;
    font-size: 11.5px;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 14px;
    border: 1px solid #c8e6c9;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.ai-chip:hover {
    background: #2e7d32;
    color: #ffffff;
    border-color: #2e7d32;
    transform: translateY(-1px);
}

/* Body */
.ai-chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    background: #fafbfa;
    display: flex;
    flex-direction: column;
    gap: 14px;
    scroll-behavior: smooth;
}

.ai-msg {
    display: flex;
    gap: 10px;
    max-width: 90%;
    animation: aiMsgFadeIn 0.25s ease-out;
}

.ai-msg-user {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.ai-msg-bot {
    align-self: flex-start;
}

.ai-msg-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #e8f5e9;
    color: #2e7d32;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
    margin-top: 4px;
}

.ai-msg-bubble {
    padding: 11px 15px;
    border-radius: 16px;
    font-size: 13.5px;
    line-height: 1.5;
    word-break: break-word;
}

.ai-msg-user .ai-msg-bubble {
    background: #2e7d32;
    color: #ffffff;
    border-bottom-right-radius: 4px;
    box-shadow: 0 2px 6px rgba(46, 125, 50, 0.25);
}

.ai-msg-bot .ai-msg-bubble {
    background: #ffffff;
    color: #263238;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    border: 1px solid #edf2ee;
}

.ai-msg-bubble p {
    margin: 0 0 8px 0;
}

.ai-msg-bubble p:last-child {
    margin-bottom: 0;
}

.ai-msg-bubble ul, .ai-msg-bubble ol {
    margin: 6px 0 8px 18px;
    padding: 0;
}

.ai-msg-bubble li {
    margin-bottom: 4px;
}

.ai-msg-bubble strong {
    font-weight: 700;
    color: inherit;
}

/* Typing Indicator */
.ai-typing-indicator {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 16px 12px 16px;
    background: #fafbfa;
}

.ai-typing-indicator.ai-typing-hidden {
    display: none;
}

.ai-typing-bubble {
    background: #ffffff;
    padding: 8px 14px;
    border-radius: 14px;
    border-bottom-left-radius: 4px;
    border: 1px solid #edf2ee;
    display: flex;
    align-items: center;
    gap: 4px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}

.ai-dot {
    width: 6px;
    height: 6px;
    background: #4caf50;
    border-radius: 50%;
    animation: aiDotBounce 1.2s infinite ease-in-out;
}

.ai-dot:nth-child(2) { animation-delay: 0.2s; }
.ai-dot:nth-child(3) { animation-delay: 0.4s; }

.ai-typing-text {
    font-size: 11px;
    color: #78909c;
    margin-left: 6px;
    font-style: italic;
}

/* Footer / Input */
.ai-chat-footer {
    padding: 10px 14px 12px;
    background: #ffffff;
    border-top: 1px solid #eef2ef;
}

.ai-input-wrap {
    display: flex;
    align-items: center;
    background: #f1f5f2;
    border-radius: 24px;
    padding: 4px 6px 4px 14px;
    border: 1px solid transparent;
    transition: all 0.2s ease;
}

.ai-input-wrap:focus-within {
    background: #ffffff;
    border-color: #4caf50;
    box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.15);
}

#ai-chat-input {
    flex: 1;
    border: none;
    background: transparent;
    outline: none;
    font-size: 13.5px;
    resize: none;
    max-height: 80px;
    line-height: 1.4;
    color: #263238;
    padding: 6px 0;
    font-family: inherit;
}

#ai-chat-send-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: #2e7d32;
    color: #ffffff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    flex-shrink: 0;
    padding: 0;
}

#ai-chat-send-btn:disabled {
    background: #b0bec5;
    cursor: not-allowed;
    opacity: 0.6;
}

#ai-chat-send-btn:not(:disabled):hover {
    background: #1b5e20;
    transform: scale(1.05);
}

.ai-footer-note {
    text-align: center;
    font-size: 10px;
    color: #90a4ae;
    margin-top: 6px;
}

/* Animations */
@keyframes aiSparklePulse {
    0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.9; }
    50% { transform: scale(1.25) rotate(15deg); opacity: 1; }
}

@keyframes aiBadgeFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
}

@keyframes aiMsgFadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes aiDotBounce {
    0%, 80%, 100% { transform: scale(0); opacity: 0.5; }
    40% { transform: scale(1); opacity: 1; }
}

/* Mobile Responsiveness */
@media (max-width: 480px) {
    #ai-chat-widget-root {
        bottom: 16px;
        right: 16px;
    }
    #ai-chat-window {
        right: 12px;
        bottom: 84px;
        width: calc(100vw - 24px);
        height: calc(100vh - 100px);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const launcher = document.getElementById('ai-chat-launcher');
    const chatWindow = document.getElementById('ai-chat-window');
    const closeBtn = document.getElementById('ai-chat-close-btn');
    const resetBtn = document.getElementById('ai-chat-reset-btn');
    const form = document.getElementById('ai-chat-form');
    const input = document.getElementById('ai-chat-input');
    const sendBtn = document.getElementById('ai-chat-send-btn');
    const messagesContainer = document.getElementById('ai-chat-messages');
    const typingIndicator = document.getElementById('ai-chat-typing');
    const quickChips = document.querySelectorAll('.ai-chip');

    // Storage Configuration (2 hours TTL matching Laravel session)
    const STORAGE_KEY = 'vt58_ai_chat_data';
    const STORAGE_TTL = 2 * 60 * 60 * 1000; // 2 hours in ms

    const defaultWelcomeMessage = {
        role: 'bot',
        text: 'Chào bà con! Em là **Trợ Lý Nông Nghiệp 58** 🌾.\n\nBà con đang cần tư vấn về **sâu bệnh hại** (rầy nâu, đạo ôn, thán thư...) hay **kỹ thuật bón phân** cho cây trồng nào cứ đặt câu hỏi bên dưới nhé!'
    };

    let chatHistory = [defaultWelcomeMessage];
    let isWindowOpen = false;

    // Load state from localStorage
    function loadChatState() {
        try {
            const rawData = localStorage.getItem(STORAGE_KEY);
            if (!rawData) {
                renderAllMessages();
                return;
            }

            const parsed = JSON.parse(rawData);
            const now = Date.now();

            // Check if stored data has expired (older than 2 hours)
            if (!parsed.updatedAt || (now - parsed.updatedAt) > STORAGE_TTL) {
                localStorage.removeItem(STORAGE_KEY);
                chatHistory = [defaultWelcomeMessage];
                renderAllMessages();
                return;
            }

            if (Array.isArray(parsed.messages) && parsed.messages.length > 0) {
                chatHistory = parsed.messages;
            } else {
                chatHistory = [defaultWelcomeMessage];
            }

            renderAllMessages();

            // Restore open state
            if (parsed.isOpen === true) {
                toggleChat(true, false);
            }
        } catch (e) {
            console.error('Error loading chat state from localStorage:', e);
            chatHistory = [defaultWelcomeMessage];
            renderAllMessages();
        }
    }

    // Save state to localStorage
    function saveChatState() {
        try {
            const dataToSave = {
                isOpen: isWindowOpen,
                updatedAt: Date.now(),
                messages: chatHistory
            };
            localStorage.setItem(STORAGE_KEY, JSON.stringify(dataToSave));
        } catch (e) {
            console.error('Error saving chat state to localStorage:', e);
        }
    }

    // Toggle Chat Window
    function toggleChat(show, autoSave = true) {
        const isHidden = chatWindow.classList.contains('ai-window-hidden');
        const shouldShow = typeof show === 'boolean' ? show : isHidden;

        isWindowOpen = shouldShow;

        if (shouldShow) {
            chatWindow.classList.remove('ai-window-hidden');
            launcher.classList.add('ai-active');
            input.focus();
            scrollToBottom();
        } else {
            chatWindow.classList.add('ai-window-hidden');
            launcher.classList.remove('ai-active');
        }

        if (autoSave) {
            saveChatState();
        }
    }

    launcher.addEventListener('click', () => toggleChat());
    closeBtn.addEventListener('click', () => toggleChat(false));

    // Enable/Disable Send button based on input
    input.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 80) + 'px';
        sendBtn.disabled = !this.value.trim();
    });

    // Enter to submit (Shift+Enter for new line)
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (!sendBtn.disabled) {
                form.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        }
    });

    // Quick Chips click
    quickChips.forEach(chip => {
        chip.addEventListener('click', function () {
            const prompt = this.getAttribute('data-prompt');
            if (prompt) {
                input.value = prompt;
                input.dispatchEvent(new Event('input'));
                form.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        });
    });

    // Reset Chat Conversation
    resetBtn.addEventListener('click', function () {
        if (!confirm('Bà con có muốn làm mới cuộc trò chuyện để hỏi chủ đề mới không?')) {
            return;
        }

        fetch('{{ route('ai.chat.reset') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(() => {
            localStorage.removeItem(STORAGE_KEY);
            chatHistory = [{
                role: 'bot',
                text: 'Đã làm mới cuộc trò chuyện! Em sẵn sàng lắng nghe câu hỏi mới của bà con ạ 🌾.'
            }];
            saveChatState();
            renderAllMessages();
            scrollToBottom();
        }).catch(err => console.error('Reset error:', err));
    });

    // Scroll chat to bottom
    function scrollToBottom() {
        requestAnimationFrame(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });
    }

    // Format simple Markdown (bold, lists, newlines) to safe HTML
    function formatMessageText(text) {
        let safe = text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");

        // Convert bold **text**
        safe = safe.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        // Convert *italic*
        safe = safe.replace(/\*(.*?)\*/g, '<em>$1</em>');

        // Convert bullet points
        const lines = safe.split('\n');
        let html = '';
        let inList = false;

        for (let i = 0; i < lines.length; i++) {
            let line = lines[i].trim();
            if (line.startsWith('* ') || line.startsWith('- ')) {
                if (!inList) {
                    html += '<ul>';
                    inList = true;
                }
                html += '<li>' + line.substring(2) + '</li>';
            } else {
                if (inList) {
                    html += '</ul>';
                    inList = false;
                }
                if (line) {
                    html += '<p>' + line + '</p>';
                }
            }
        }
        if (inList) html += '</ul>';

        return html;
    }

    // Create a message DOM node
    function createMessageElement(role, text) {
        const msgDiv = document.createElement('div');
        msgDiv.className = `ai-msg ai-msg-${role === 'user' ? 'user' : 'bot'}`;

        if (role === 'user') {
            msgDiv.innerHTML = `
                <div class="ai-msg-bubble">
                    <p>${text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\n/g, "<br>")}</p>
                </div>
            `;
        } else {
            msgDiv.innerHTML = `
                <div class="ai-msg-avatar">🌱</div>
                <div class="ai-msg-bubble">
                    ${formatMessageText(text)}
                </div>
            `;
        }
        return msgDiv;
    }

    // Render entire history
    function renderAllMessages() {
        messagesContainer.innerHTML = '';
        chatHistory.forEach(msg => {
            messagesContainer.appendChild(createMessageElement(msg.role, msg.text));
        });
    }

    // Append single Message to UI and memory
    function appendMessage(role, text, shouldSave = true) {
        chatHistory.push({ role, text });
        messagesContainer.appendChild(createMessageElement(role, text));
        scrollToBottom();

        if (shouldSave) {
            saveChatState();
        }
    }

    // Multi-tab synchronization
    window.addEventListener('storage', function (e) {
        if (e.key === STORAGE_KEY && e.newValue) {
            try {
                const updatedData = JSON.parse(e.newValue);
                if (Array.isArray(updatedData.messages)) {
                    chatHistory = updatedData.messages;
                    renderAllMessages();
                    scrollToBottom();
                }
            } catch (err) {
                console.error('Multi-tab sync parse error:', err);
            }
        }
    });

    // Handle Form Submit
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const userText = input.value.trim();
        if (!userText) return;

        // Clear input & reset height
        input.value = '';
        input.style.height = 'auto';
        sendBtn.disabled = true;

        // Render user message & save state
        appendMessage('user', userText, true);

        // Show typing indicator
        typingIndicator.classList.remove('ai-typing-hidden');
        scrollToBottom();

        // Send AJAX to server
        fetch('{{ route('ai.chat') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ message: userText })
        })
        .then(res => res.json())
        .then(data => {
            typingIndicator.classList.add('ai-typing-hidden');

            if (data.status === 'success' && data.reply) {
                appendMessage('bot', data.reply, true);
            } else {
                appendMessage('bot', data.message || 'Dạ hiện tại đường truyền đang bận, bà con vui lòng thử hỏi lại sau giây lát nhé!', true);
            }
        })
        .catch(err => {
            console.error('Chat error:', err);
            typingIndicator.classList.add('ai-typing-hidden');
            appendMessage('bot', 'Lỗi kết nối máy chủ, bà con vui lòng kiểm tra kết nối mạng và thử lại giúp em nhé!', true);
        });
    });

    // Initialize on page load
    loadChatState();
});
</script>
