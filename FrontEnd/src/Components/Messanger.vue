<template>
  <div class="messenger">
    <div class="chat-window rounded shadow-sm">
      <!-- Chat Header -->
      <div class="chat-header bg-warning text-white py-3 px-4">
        <div class="d-flex align-items-center justify-content-between">
          <div class="user-info d-flex align-items-center">
            <img
              class="avatar rounded-circle me-3"
              src="data:image/webp;base64,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"
              alt="User Avatar"
            />
            <div class="user-details">
              <p class="user-name mb-0 fw-bold">Jung Kook</p>
              <p class="user-status mb-0 small">Online</p>
            </div>
          </div>
          <!-- Header Icons -->
          <div class="header-icons d-flex align-items-center">
            <button class="btn btn-call text-white me-3" aria-label="Call" onclick="makeCall()">
              <i class="bi bi-telephone-fill call-icon" title="Call"></i>
            </button>
            <button class="btn btn-close text-white p-0" aria-label="Close" @click="closeChat">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Chat Messages -->
      <div class="chat-messages p-4" ref="chatMessages">
        <div
          v-for="message in messages"
          :key="message.id"
          :class="{
            'message-sent': message.sender_id === user.id,
            'message-received': message.receiver_id === user.id
          }"
        >
          <div class="message-bubble rounded p-3 mb-3">
            <p class="message-text mb-0">{{ message.message }}</p>
            <small class="text-muted text-10px">{{ formatTime(message.timestamp || message.created_at) }}</small>
          </div>
        </div>
      </div>

      <div class="chat-input d-flex align-items-center bg-light p-3">
        <input
          type="text"
          v-model="newMessage"
          @keyup.enter="sendMessage"
          class="form-control me-3"
          placeholder="Type your message here..."
        />
        <button @click="sendMessage" class="btn btn-orange">Send</button>
        <div class="d-flex align-items-center">
        <label for="file-upload" class="ms-3 mb-0" style="cursor: pointer">
          <i class="bi bi-paperclip"></i>
        </label>
        <input
          type="file"
          id="file-upload"
          style="display: none"
          @change="handleFileUpload"
        />
      </div>
        <div id="upload-message" class="mt-3"></div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'FileUploadComponent',
  data() {
    return {
      uploadedFile: null,
      messages: [],
      image: null,
      newMessage: '',
      user: JSON.parse(localStorage.getItem('user'))
    }
  },
  mounted() {
    this.fetchMessages();
  },
  methods: {
    async fetchMessages() {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/chat/show/${this.user.id}`);
        this.messages = response.data.data; 
        console.log('Fetched messages:', this.messages);
      } catch (error) {
        console.error('Error fetching messages:', error);
      }
    },
    async sendMessage() {
      if (this.newMessage.trim() !== '') {
        try {
          const timestamp = new Date().toISOString();
          await axios.post('http://127.0.0.1:8000/api/chat/create', {
            sender_id: this.user.id,
            receiver_id: 3,
            message: this.newMessage,
            image: this.image, 
            timestamp: timestamp 
          });
          this.messages.push({
            sender_id: this.user.id,
            receiver_id: 3,
            message: this.newMessage,
            timestamp: timestamp
          });
          this.newMessage = '';
        } catch (error) {
          console.error('Error sending message:', error);
        }
      }
    },
    handleFileUpload(event) {
      this.uploadedFile = event.target.files[0];
      console.log('Uploaded file:', this.uploadedFile);
    },
    closeChat(event) {
      event.preventDefault();
      window.location.href = '/';
    },
    formatTime(timestamp) {
      const date = new Date(timestamp);
      return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }
  }
}
</script>


<style scoped>
.messenger {
  height: 100vh;
  display: flex;
  width: 50%;
  flex-direction: column;
}

.chat-window {
  flex: 1;
  display: flex;
  flex-direction: column;
  background-color: white;
  border-radius: 0.5rem;
  overflow: hidden;
}

.chat-header {
  border-top-left-radius: 0.5rem;
  border-top-right-radius: 0.5rem;
  background-color: #ffa500;
  color: white;
  padding: 0.5rem 1rem;
}

.avatar {
  width: 40px;
  height: 40px;
}

.chat-messages {
  flex: 1;
  overflow-x: hidden;
  padding: 1rem;
  width: 100%;

}

.message-bubble {
  background-color: #eae7e7;
  width: 40%;
  flex-wrap: wrap;
}

.message-sent {
  display: flex;
  justify-content: flex-end;
}

.message-sent .message-bubble {
  background-color: #e6f2ff;
  width: 40%;
}

.chat-input {
  display: flex;
  align-items: center;
  padding: 0.5rem 1rem;
  background-color: #f8f9fa;
  border-top: 1px solid #e9ecef;
}

.btn-orange {
  background-color: #ffa500 !important;
  border-color: #ffa500 !important;
  color: #fff !important;
}

.btn-orange:hover {
  background-color: #e69500 !important;
  border-color: #e69500 !important;
}
</style>