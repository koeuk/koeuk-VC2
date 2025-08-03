<template>
  <div class="profile-edit-container">
    <div class="profile-image">
      <img :src="user.image" alt="Profile Image" />
      <div class="edit-icon">
        <img src="https://i.pinimg.com/originals/61/54/18/61541805b3069740ecd60d483741e5bb.jpg" alt="camera" />
      </div>
    </div>
    <div class="profile-form">
      <div class="form-fields">
        <div class="form-group">
          <label for="name">User Name:</label>
          <input type="text" id="name" v-model="user.name" class="form-control" />
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="user.email" class="form-control" />
        </div>
        <div class="form-group">
          <label for="phone">Phone Number:</label>
          <input type="text" id="phone" v-model="user.phone" class="form-control" />
        </div>
      </div>
      <div class="form-actions">
        <router-link to="/service/profile">
          <button type="button" class="btn btn-cancel">Cancel</button>
        </router-link>
        <router-link to="/service/profile">
          <button type="button" class="btn btn-update">Update</button>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {
        role: 'admin',
        name: 'Admin',
        email: 'admin@gmail.com',
        phone: '123-456-7890',
        createDate: '2024-06-24',
        createTime: '08:18:17',
        image: 'https://i.pinimg.com/564x/58/b6/52/58b6528f3b6c1b77a119f9efc2ef8f61.jpg'
      }
    };
  }
};
</script>

<style scoped>
.profile-edit-container {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 7rem;
  height: 100vh;
}

.profile-image {
  position: relative;
  width: 250px;
  height: 250px;
  border-radius: 50%;
  overflow: hidden;
  margin-right: 20px;
}

.profile-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.edit-icon {
  position: absolute;
  bottom: 10px;
  right: 50px;
}

.edit-icon img {
  width: 40px;
  height: 40px;
  cursor: pointer;
}

.profile-form {
  width: 60%;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  font-weight: bold;
}

.form-control {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 14px;
}

.form-actions {
  margin-top: 20px;
}

.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-cancel {
  background-color: #6c757d;
  color: white;
}

.btn-update {
  background-color: orange;
  color: white;
  margin-left: 20px;
}

.btn:hover {
  opacity: 0.8;
}
</style>
