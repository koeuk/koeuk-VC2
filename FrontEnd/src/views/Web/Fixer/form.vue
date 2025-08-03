
<template>
  <div class="container">
    <div id="btn-back">
      <router-link to="/homePage">
        <button @click="goBack" class="btn btn-secondary back-button">Back</button>
      </router-link>
    </div>
    <div class="overlay">
      <div class="card shadow">
        <div class="card-body">
          <h2 class="card-title text-center mb-4 title-highlight">Fixer Register</h2>
          <form @submit.prevent="submitForm">
            <div id="profile-fixer" class="text-center mb-4">
              <div class="profile-fixxer">
                <label for="profileInput" class="profile-upload">
                  <input
                    type="file"
                    id="profileInput"
                    class="visually-hidden"
                    @change="handleFileUpload"
                    accept="image/*"
                  />
                  <div id="profile-input" class="position-relative">
                    <i class="bi bi-camera profile-icon"></i>
                    <img
                      :src="profilePicture ? profilePicture : defaultProfilePicture"
                      alt="Profile"
                      class="profile-image"
                    />
                  </div>
                </label>
              </div>
              <div class="name-fixer">
                <p>{{ name }}</p>
              </div>
            </div>

            <div class="form-group">
              <div class="mb-3">
                <label for="nameInput" class="form-label">Name</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person"></i></span>
                  <input
                    type="text"
                    id="nameInput"
                    class="form-control"
                    v-model="name"
                    placeholder="Enter your name"
                    required
                    :class="{ 'is-invalid': !nameValid && nameTouched }"
                    @blur="nameTouched = true"
                    autocomplete="name"
                  />
                  <div v-if="!nameValid && nameTouched" class="invalid-feedback">
                    Name is required
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="emailInput" class="form-label">Email</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                  <input
                    type="email"
                    id="emailInput"
                    class="form-control"
                    v-model="email"
                    placeholder="Please enter your email"
                    required
                    :class="{ 'is-invalid': !emailValid && emailTouched }"
                    @blur="emailTouched = true"
                    autocomplete="email"
                  />
                  <div v-if="!emailValid && emailTouched" class="invalid-feedback">
                    Valid email is required
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="mb-3">
                <label for="passwordInput" class="form-label">Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-key"></i></span>
                  <input
                    type="password"
                    id="passwordInput"
                    class="form-control"
                    v-model="password"
                    placeholder="Please enter your password"
                    required
                    :class="{ 'is-invalid': !passwordValid && passwordTouched }"
                    @blur="passwordTouched = true"
                    autocomplete="new-password"
                  />
                  <div v-if="!passwordValid && passwordTouched" class="invalid-feedback">
                    Password is required
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="locationInput" class="form-label">Location</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-geo"></i></span>
                  <input
                    type="text"
                    id="locationInput"
                    class="form-control"
                    v-model="location"
                    placeholder="Enter your location"
                    required
                    :class="{ 'is-invalid': !locationValid && locationTouched }"
                    @blur="locationTouched = true"
                    autocomplete="location"
                  />
                  <div v-if="!locationValid && locationTouched" class="invalid-feedback">
                    Location is required
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="mb-3">
                <label for="phoneInput" class="form-label">Phone</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                  <input
                    type="text"
                    id="phoneInput"
                    class="form-control"
                    v-model="phone"
                    placeholder="Enter your phone"
                    required
                    :class="{ 'is-invalid': !phoneValid && phoneTouched }"
                    @blur="phoneTouched = true"
                    autocomplete="phone"
                  />
                  <div v-if="!phoneValid && phoneTouched" class="invalid-feedback">
                    Phone is required
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="careerSelect" class="form-label">Career</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-bag-plus"></i></span>
                  <select
                    class="form-select"
                    id="careerSelect"
                    v-model="career"
                    required
                    :class="{ 'is-invalid': !careerValid && careerTouched }"
                    @blur="careerTouched = true"
                  >
                    <option value="" disabled>Choose...</option>
                    <option value="Mechanic">Mechanic</option>
                    <option value="Electrician">Electrician</option>
                    <option value="Plumber">Plumber</option>
                  </select>
                  <div v-if="!careerValid && careerTouched" class="invalid-feedback">
                    Career is required
                  </div>
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import axios from 'axios'

export default {
  name: 'fixerRegister',
  setup() {
    const name = ref('')
    const email = ref('')
    const password = ref('')
    const location = ref('')
    const phone = ref('')
    const profilePicture = ref('')
    const career = ref('')

    const nameTouched = ref(false)
    const emailTouched = ref(false)
    const passwordTouched = ref(false)
    const locationTouched = ref(false)
    const phoneTouched = ref(false)
    const careerTouched = ref(false)

    const defaultProfilePicture = 'https://via.placeholder.com/150'

    const validEmail = (email) => {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      return re.test(email)
    }

    const nameValid = computed(() => name.value.trim() !== '')
    const emailValid = computed(() => validEmail(email.value))
    const passwordValid = computed(() => password.value.trim() !== '')
    const locationValid = computed(() => location.value.trim() !== '')
    const phoneValid = computed(() => phone.value.trim() !== '')
    const careerValid = computed(() => career.value.trim() !== '')
    const profilePictureValid = computed(() => profilePicture.value !== '')

    const validateForm = computed(() => {
      return (
        nameValid.value &&
        emailValid.value &&
        passwordValid.value &&
        locationValid.value &&
        phoneValid.value &&
        careerValid.value &&
        profilePictureValid.value
      )
    })

    const handleFileUpload = (event) => {
      const file = event.target.files[0]
      if (file) {
        const reader = new FileReader()
        reader.onload = (e) => {
          profilePicture.value = e.target.result
        }
        reader.readAsDataURL(file)
      }
    }

    const submitForm = async () => {
      nameTouched.value = true
      emailTouched.value = true
      passwordTouched.value = true
      locationTouched.value = true
      phoneTouched.value = true
      careerTouched.value = true

      if (!validateForm.value) {
        alert('Please fill out all fields correctly.')
        return
      }

      const formData = {
        name: name.value,
        email: email.value,
        password: password.value,
        location: location.value,
        phone: phone.value,
        career: career.value,
        profilePicture: profilePicture.value
      }
      try {
        const response = await axios.post('http://127.0.0.1:8000/api/fixer/register', formData)
        console.log(response.data)
        resetForm()
      } catch (error) {
        console.log(error)
      }
    }

    const resetForm = () => {
      name.value = ''
      email.value = ''
      password.value = ''
      location.value = ''
      phone.value = ''
      career.value = ''
      profilePicture.value = ''

      nameTouched.value = false
      emailTouched.value = false
      passwordTouched.value = false
      locationTouched.value = false
      phoneTouched.value = false
    }

    const goBack = () => {
      // Assuming you are using Vue Router
      this.$router.push('/homePage')
    }

    return {
      name,
      email,
      password,
      location,
      phone,
      profilePicture,
      career,
      defaultProfilePicture,
      handleFileUpload,
      submitForm,
      goBack,
      nameValid,
      emailValid,
      passwordValid,
      locationValid,
      phoneValid,
      careerValid,
      profilePictureValid,
      validateForm
    }
  }
}
</script>



<style scoped>
#profile-fixer {
  justify-content: center;
  align-items: center;
  margin-left: 35%;
  margin-bottom: 20px;
}
.position-relative i {
  font-size: 180%;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  opacity: 0.7;
  color: #fff;
}

.name-fixer {
  font-size: 1.5rem;
  margin-left: -59%;
}

.profile-fixxer {
  position: relative;
  display: flex;
  justify-content: center;
  width: 150px;
  height: 150px;
  border-radius: 50%;
  overflow: hidden;
  border: 3px solid #ff8c00;
}

#profile-input {
  position: relative;
  width: 100%;
  height: 100%;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: rgba(0, 0, 0, 0.5);
  transition: background-color 0.3s;
}

#profile-input:hover {
  background-color: rgba(0, 0, 0, 0.7);
}

.profile-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.title-highlight {
  display: inline-block;
  margin-left: 30%;
  padding: 0.25rem 0.5rem;
  background-color: #ff8c00;
  border-radius: 0.25rem;
  color: white;
  transition: background-color 0.3s, color 0.3s;
}

.title-highlight:hover {
  background-color: #e67e00;
  color: #fff;
}

.input-group-text {
  background-color: #ff8c00;
  border: none;
  color: white;
  transition: background-color 0.3s, color 0.3s;
}

.input-group-text:hover {
  background-color: #e67e00;
  color: #fff;
}

.container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: url('https://roadguardinterlock.com/wp-content/uploads/2019/09/car-repair-with-an-interlock-device.jpg')
    no-repeat center center;
  background-size: cover;
}

.overlay {
  padding: 2rem;
  border-radius: 10px;
  width: 100%;
  max-width: 700px;
  background-color: rgba(255, 255, 255, 0.85);
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.card {
  border: none;
  border-radius: 10px;
}

.card-body {
  padding: 2rem;
}

.btn-primary {
  background-color: #ff8c00;
  border-color: #ff8c00;
  transition: background-color 0.3s, border-color 0.3s;
}

.btn-primary:hover {
  background-color: #e67e00;
  border-color: #e67e00;
}

.btn-primary:focus {
  box-shadow: 0 0 0 0.2rem rgba(255, 140, 0, 0.5);
}

.invalid-feedback {
  color: #dc3545;
  display: none; /* initially hide feedback */
}

.is-invalid ~ .invalid-feedback {
  display: block; /* show when input is invalid */
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}

.form-group {
  display: flex;
  gap: 16%;
}

.form-group .mb-3 {
  flex: 1;
}

.form-group .form-label {
  width: 100%;
}

input,
select {
  width: 100%;
}

#btn-back {
  width: 50%;
  margin-bottom: 20%;
  margin-right: -25%;
  margin-left: -24%;
}

.btn-secondary {
  background-color: #ff8c00;
  width: 10%;
  border: none;
  margin-bottom: 1rem;
  margin-bottom: 42%;
  margin-right: 44%;
}

.btn-secondary:hover {
  background-color: #6c757d;
}
</style>
