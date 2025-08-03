<template>
  <div class="profile-right">
    <router-link to="/">
      <button class="btn btn-back"><i class="bi bi-arrow-left"></i> Back</button>
    </router-link>
  </div>
  <div class="profile-container">
    <div class="profile-left">
      <div class="profile-image">
        <div class="edit-profile">
          <div class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            Edit Profile
          </div>
          <div
            class="modal fade"
            id="editProfileModal"
            tabindex="-1"
            aria-labelledby="editProfileModalLabel"
            aria-hidden="true"
          >
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editProfileModalLabel">Edit Information</h5>
                  <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                  ></button>
                </div>
                <div class="modal-body">
                  <form @submit.prevent="update">
                    <div class="group-input">
                      <label for="username" class="form-label">User Name:</label>
                      <input
                        type="text"
                        class="form-control"
                        id="username"
                        v-model="authStore.user.name"
                      />
                    </div>
                    <div class="group-input">
                      <label for="phone" class="form-label">Phone Number:</label>
                      <input
                        type="tel"
                        class="form-control"
                        id="phone"
                        v-model="authStore.user.phone"
                      />
                    </div>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" style="margin-top:10px;" >
                      Update
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <img
          :src="authStore.user.profile || defaultProfileImage"
          alt="Profile Image"
          class="center-image"
        />
        <i
          class="camera bi bi-camera"
          data-bs-toggle="modal"
          data-bs-target="#uploadImageModal"
          style="cursor: pointer"
        ></i>
      </div>
      <div class="profile-info">
        <div class="info">
          <label>Role:</label>
          <span class="info-value">{{ authStore.user.role }}</span>
        </div>
        <div class="info">
          <label>User Name:</label>
          <span class="info-value">{{ authStore.user.name }}</span>
        </div>
        <div class="info">
          <label>Email:</label>
          <span class="info-value">{{ authStore.user.email }}</span>
        </div>
        <div class="info">
          <label>Phone Number:</label>
          <span class="info-value">{{ authStore.user.phone }}</span>
        </div>
        <div class="info">
          <label>Create Date:</label>
          <span class="info-value">{{ formatDate(authStore.user.created_at) }}</span>
        </div>
        <div class="info">
          <label>Create Time:</label>
          <span class="info-value">{{ formatTime(authStore.user.created_at) }}</span>
        </div>
      </div>
    </div>
  </div>
  <div
    class="modal fade"
    id="uploadImageModal"
    tabindex="-1"
    aria-labelledby="uploadImageModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div
            id="image-drop-box"
            @dragover.prevent
            @drop.prevent="handleDrop"
            @click="triggerFileInput"
            style="border: 2px dashed #ccc; padding: 50px; height: 50vh; text-align: center"
          >
            <label for="image-upload" style="cursor: pointer; font-size: 18px; margin-top: 25%">
              Drop an image or click here!
            </label>
            <input
              type="file"
              id="image-upload"
              @change="handleFiles"
              ref="fileInput"
              style="display: none"
              enctype="multipart/form-data"
              accept="image/*"
            />
            <img v-if="image" :src="image" alt="Uploaded Image" class="uploaded-image" />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="resetForm">Reset</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="updateProfile">
            Save
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth-store';
import axios from 'axios';

const image = ref(null);
const fileInput = ref(null);
const authStore = useAuthStore();

const defaultProfileImage = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQGU75F10hHYzumh3r2HkDve9wTh2GLZ57ENYSMl4G5SC3lKc_3ZuZmnzC-RfYJoxThvMA&usqp=CAU';

function triggerFileInput() {
  fileInput.value.click();
}

function handleFiles(event) {
  const files = event.target.files || event.dataTransfer.files;
  if (files.length > 0) {
    const file = files[files.length - 1];
    previewImage(file);
  }
}

function handleDrop(event) {
  const files = event.dataTransfer.files;
  if (files.length > 0) {
    const file = files[files.length - 1];
    previewImage(file);
  }
}

function previewImage(file) {
  const reader = new FileReader();
  reader.onload = (e) => {
    image.value = e.target.result;
  };
  reader.readAsDataURL(file);
}

async function update() {
  try {
    const formValues = {
      name: authStore.user.name,
      phone: authStore.user.phone,
    };

    const accessToken = localStorage.getItem('access_token');
    const response = await axios.put(
      `http://127.0.0.1:8000/api/update/${authStore.user.id}`,
      formValues,
      {
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${accessToken}`,
        },
      }
    );

    localStorage.setItem('user', JSON.stringify(response.data.user));
    console.log('Update successful:', response.data.user);
  } catch (error) {
    console.error('Backend error:', error.response.data);
  }
}

const updateProfile = async () => {
  try {
    const formData = new FormData();
    formData.append('profile', fileInput.value.files[0]);

    const accessToken = localStorage.getItem('access_token');

    const response = await axios.post(
      `http://127.0.0.1:8000/api/update/profile/${authStore.user.id}`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
          Authorization: `Bearer ${accessToken}`,
        },
      }
    );
    location.reload();
    localStorage.removeItem('user');
    localStorage.setItem('user', JSON.stringify(response.data.user));
  } catch (error) {
    console.error('Backend error:', error.response.data);
  }
};

function resetForm() {
  // Clear the uploaded image and reset any other form states
  image.value = null;
  fileInput.value.value = '';
}

function formatDate(date) {
  return new Date(date).toLocaleDateString();
}

function formatTime(date) {
  return new Date(date).toLocaleTimeString();
}
</script>

<style scoped>
.profile-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 80vh;
  margin-top: 30px;
  background-color: #b6b3b3;
  border-radius: 5px;
  padding: 20px;
}
#image-drop-box img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}
.uploaded-image {
  position: absolute;
  right: 24px;
  width: 90%;
  bottom: 15px;
  height: 90%;
  object-fit: contain;
}
.profile-left {
  width: 70%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-self: center;
}

.profile-right {
  width: 10%;
  margin-left: 20px;
}

.profile-image {
  position: relative;
  text-align: center;
  justify-content: center;
  margin-bottom: 20px;
}

.camera {
  position: absolute;
  left: 47%;
  bottom: 8px;
  display: flex;
  justify-content: center;
  color: #fff;
  padding: 5px;
  font-size: 40px;
}

.profile-right a {
  list-style: none;
  text-decoration: none;
}

.profile-image img {
  width: 250px;
  height: 250px;
  border-radius: 50%;
  object-fit: cover;
}

.edit-profile {
  position: absolute;
  bottom: 10px; /* Adjust the positioning as needed */
  right: 10px; /* Adjust the positioning as needed */
}
.group-input {
  /* margin-right: 60%; */
  text-align: start;
}
.edit-profile .btn {
  background-color: orange;
  color: white;
  border: 1px solid orange;
  padding: 8px 16px;
  border-radius: 5px;
  text-decoration: none;
  transition: background-color 0.3s ease;
  display: flex;
  align-items: center;
}

.edit-profile .btn:hover {
  background-color: #ff7f50; /* Lighter shade of orange */
}

.btn-back {
  background-color: #6c757d;
  color: white;
  border: 1px solid #6c757d;
  padding: 8px 16px;
  border-radius: 5px;
  text-decoration: none;
  transition: background-color 0.3s ease;
  display: flex;
  align-items: center;
}

.btn-back:hover {
  background-color: orange; /* Darker shade of gray */
}

.profile-info {
  padding: 20px;
  background-color: white;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.info {
  margin-bottom: 10px;
  display: flex;
  align-items: center;
}

.info label {
  width: 120px;
  font-weight: bold;
}

.info-value {
  flex: 1;
  margin-left: 20px;
}

@media (max-width: 768px) {
  .profile-container {
    flex-direction: column;
    height: auto;
  }

  .profile-left {
    width: 100%;
    margin-bottom: 20px;
  }

  .profile-info {
    padding: 10px;
  }
}
</style>