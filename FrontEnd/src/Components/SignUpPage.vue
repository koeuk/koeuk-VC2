<template>
  <div class="auth-page d-flex align-items-center justify-content-center">
    <transition name="fade">
      <div
        v-if="showSpinner"
        class="spinner-container position-fixed w-100 h-100 d-flex align-items-center justify-content-center"
      >
        <div class="spinner-border text-warning" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </transition>

    <transition name="fade">
      <div class="auth-container shadow-lg d-flex flex-column flex-lg-row w-100">
        <div class="image-container">
          <img src="@/assets/images/image.png" alt="Auth Image" />
        </div>
        <div class="form-container p-5">
          <h2 class="mb-4">Sign Up</h2>
          <form @submit.prevent="registerUser">
            <!--  -->
            <div class="form-group mb-4">
              <label for="name">Username:</label>
              <input
                type="text"
                id="name"
                v-model="formData.name"
                class="form-control"
                :class="{ 'is-invalid': formSubmitted && nameError }"
                required
                autocomplete="username"
              />
              <!--  -->
              <p v-if="formSubmitted && nameError" class="text-danger">{{ nameError }}</p>
            </div>
            <!--  -->
            <div class="form-group mb-4">
              <label for="email">Email:</label>
              <input
                type="email"
                id="email"
                v-model="formData.email"
                class="form-control"
                required
                autocomplete="email"
                :class="{ 'is-invalid': formSubmitted && emailError }"
              />
              <!--  -->
              <p v-if="formSubmitted && emailError" class="text-danger">
                {{ emailError }}
              </p>
            </div>
            <!--  -->
            <div class="input-box mb-4">
              <label for="password">Password:</label>
              <input
                :type="showPassword ? 'text' : 'password'"
                id="password"
                v-model="formData.password"
                class="form-control"
                :class="{ 'is-invalid': formSubmitted && passwordError }"
                @input="passwordError = ''"
                @blur="validatePassword"
                required
                autocomplete="new-password"
              />
              <span class="eye" @click="togglePasswordVisibility">
                <i :class="showPassword ? 'fa fa-eye' : 'fa fa-eye-slash'"></i>
              </span>
            </div>
            <p v-if="formSubmitted && passwordError" class="text-danger">
              {{ passwordError }}
            </p>
            <!--  -->
            <button type="submit" class="btn btn-primary w-100 mb-4">Sign Up</button>
            <!--  -->
            <p v-if="generalError" class="text-danger">{{ generalError }}</p>
            <p v-if="accountExists" class="text-danger">{{ accountExists }}</p>
            <!--  -->
          </form>

          <div class="toggle-auth mt-4">
            <p>
              Already Have an Account?
              <router-link to="/login">Login</router-link>
            </p>
            <p>
            <router-link to="/">Back to Home</router-link>
          </p>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      formData: {
        name: "",
        email: "",
        password: "",
      },
      showSpinner: false,
      // 
      showPassword: false,
      formSubmitted: false,
      generalError: "",
      accountExists: "",
      // 
    };
  },
// 
  methods: {
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword;
    },
    async registerUser() {
      this.formSubmitted = true;

      if (this.emailError || this.passwordError || this.nameError) {
        return;
      }
// 
      try {
        this.showSpinner = true;
        console.log(this.formData);
        const response = await axios.post(
          "http://127.0.0.1:8000/api/register",
          this.formData
        );
        // const { user, access_token } = response.data;
        this.$router.push("/login");
      } catch (error) {
        this.showSpinner = false;
        if (error.response && error.response.status === 422) {
          this.generalError = Object.values(error.response.data.errors).flat().join(" ");
        } else if (error.response && error.response.status === 409) {
          this.accountExists = "Account already exists with this email.";
        } else {
          console.error("Error registering user:", error.message);
          this.generalError = "Registration failed. Please try again.";
        }
      }
    },
    loginWithGoogle() {
      // Implement Google login functionality if needed
    },
    // 
    checkIfUserExists() {
      const user = JSON.parse(localStorage.getItem("user"));
      if (user) {
        this.accountExists = "You already have an account.";
        this.$router.push("/");
        return true;
      }
    },
    // 
  },
  // 
  computed: {
    emailError() {
      if (!this.formData.email) return "";
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return !emailRegex.test(this.formData.email)
        ? "Email is not valid! Please use the correct format."
        : "";
    },
    passwordError() {
      if (this.formData.password.length < 8) {
        return "Password must be at least 8 characters!";
      }
      return "";
    },
    nameError() {
      return !this.formData.name ? "Username is required." : "";
    },
  },
  mounted() {
    this.checkIfUserExists();
  },
  // 
};
</script>

<style scoped>
@import "@fortawesome/fontawesome-free/css/all.css";

/* style form validate */

.input-box {
  position: relative;
}
.input-box .eye {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
}
.input-box {
  position: relative;
}
.input-box .eye {
  position: absolute;
  right: 10px;
  top: 70%;
  right: 7%;
  transform: translateY(-50%);
  cursor: pointer;
}
#hiddenShow {
  display: none;
}
.is-invalid {
  border-color: red;
}
input {
  padding: 12px;
}
/* style form validate */

.auth-page {
  background-color: #f0f2f5;
  display: flex;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}

.auth-container {
  display: flex;
  flex-direction: column;
  border-radius: 10px;
  overflow: hidden;
  background-color: white;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  /* width: 90%; */
  /* max-width: 900px; */
}

@media (min-width: 992px) {
  .auth-container {
    flex-direction: row;
  }
}

.image-container {
  flex: 1.5;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #007bff;
}

.image-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.form-container {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

h2 {
  font-size: 2.5rem;
  font-weight: bold;
  margin-bottom: 1.5rem;
  color: #333;
}

label {
  font-weight: bold;
  margin-bottom: 0.5rem;
  color: #555;
}

.form-control {
  /* margin-bottom: 1.5rem; */
  /* padding: 12px; */
  font-size: 1.1rem;
  border: 1px solid #ced4da;
  border-radius: 5px;
}

.btn-primary {
  background-color: orange;
  border-color: #007bff;
  font-size: 1.2rem;
  padding: 8px;
  border-radius: 5px;
  border: none;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
  border-color: #6c757d;
  font-size: 1.2rem;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.btn-secondary:hover {
  background-color: #5a6268;
}

.btn-google {
  background-color: #dd4b39;
  color: white;
  font-size: 1.1rem;
  padding: 8px;
  border-radius: 5px;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.3s ease;
  text-align: center;
  cursor: pointer;
  width: 100%;
}

.btn-google:hover {
  background-color: #c23321;
}

.btn-google i {
  margin-right: 10px;
}

.social-login h3 {
  font-size: 1.3rem;
  font-weight: bold;
  margin-bottom: 1.5rem;
  text-align: center;
  color: #333;
}

.toggle-auth {
  text-align: center;
}

.toggle-auth a {
  color: #007bff;
  text-decoration: none;
  font-weight: bold;
}

.toggle-auth a:hover {
  text-decoration: underline;
}

.back-home {
  text-align: center;
  margin-top: 1rem;
}

.back-home .btn-secondary {
  background-color: #6c757d;
  color: white;
  border-color: #6c757d;
  font-size: 1.2rem;
  padding: 12px;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.back-home .btn-secondary:hover {
  background-color: #5a6268;
}

.spinner-container {
  background-color: white; /* Semi-transparent background */
  z-index: 999; /* Ensure it's above other content */
  position: fixed;
  top: 0;
  left: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
}

.spinner-border {
  width: 3rem;
  height: 3rem;
  color: #ffc107; /* Yellow color */
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>

please correct transition
