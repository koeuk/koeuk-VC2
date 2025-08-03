<template>
  <div class="login-page d-flex align-items-center justify-content-center vh-100">
    <transition name="fade">
      <div
        v-if="loading"
        class="spinner-container position-fixed w-100 h-100 d-flex align-items-center justify-content-center"
      >
        <div class="spinner-border text-warning" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </transition>

    <div class="login-container shadow-lg d-flex">
      <div class="image-container">
        <img src="@/assets/images/image.png" alt="Login Image" />
      </div>
      <div class="form-container p-5">
        <h2 class="mb-4">Login</h2>
        <div class="alert alert-warning text-center text-danger" role="alert" v-if="loginError !== ''" >{{ loginError }}</div>

        <form @submit.prevent="handleLogin">
          <div class="form-group mb-4">
            <label for="email">Email:</label>
            <input
              type="email"
              id="email"
              v-model="email"
              :class="{'is-invalid': emailError}"
              class="form-control"
              @input="emailError = ''"
              @blur="validateEmail"
              required
            />
          </div>
          <!--  -->
          <div class="input-box">
            <label for="password">Password:</label>
            <input
              :type="showPassword ? 'text' : 'password'"
              id="password"
              v-model="password"
              :class="{ 'is-invalid': passwordError }"
              class="form-control"
              @input="passwordError = ''"
              @blur="validatePassword"
              required
            />
            <span class="eye" @click="togglePasswordVisibility">
              <i :class="showPassword ? 'fa fa-eye' : 'fa fa-eye-slash'"></i>
            </span>
          </div>
          <!--  -->
          <button type="submit" class="btn btn-primary w-100 mb-4" style="background: orange; border: none">
            Login
          </button>
        </form>
        <div class="social-login">
          <p class="mt-3">
            Don't have an account? <router-link to="/signup">Sign Up</router-link>
          </p>
          <p>
            <router-link to="/">Back to Home</router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
import { GoogleLogin } from "vue3-google-login";
import { GOOGLE_CLIENT_ID } from "@/main";
import axios from "axios";
import { useAuthStore } from "@/stores/auth-store";
import { useRouter } from "vue-router";

export default {
  components: {
    GoogleLogin,
  },
  setup() {
    const loading = ref(true);
    setTimeout(() => {
      loading.value = false;
    }, 300);
// 
    const email = ref("");
    const password = ref("");
    const showPassword = ref(false);
    const { setAuthUser } = useAuthStore();
    const router = useRouter();
// 
    const emailError = ref("");
    const passwordError = ref("");
    const loginError = ref("");
// 
    const clientId = GOOGLE_CLIENT_ID;
    const scope = "profile email";
    const buttonText = "Login with Google";
// validation email format default email 
    function validateEmail() {
      if (email.value === "") {
        emailError.value = "Email is required.";
      } else {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@(([^<>()[\]\.,;:\s@"]+\.)+[^<>()[\]\.,;:\s@"]{2,})$/i;
        emailError.value = re.test(String(email.value).toLowerCase())
          ? ""
          : "Please enter a valid email address.";
      }
    }
// 
    function validatePassword() {
      passwordError.value = password.value === "" ? "Password is required." : "";
    }

    async function handleLogin() {
      validateEmail();
      validatePassword();

      if (emailError.value !== "" || passwordError.value !== "") {
        return;
      }

      try {
        const response = await axios.post("http://127.0.0.1:8000/api/login", {
          email: email.value,
          password: password.value,
        });
        const { user, access_token } = response.data;

        setAuthUser(user);

        if (user["role"] === "customer") {

          localStorage.setItem("user", JSON.stringify(user));
          localStorage.setItem("access_token", access_token);
          router.push("/");
        } else if (user["role"] === "fixer") {
          localStorage.setItem("user", JSON.stringify(user));
          localStorage.setItem("access_token", access_token);
          router.push("/HomeFixer");
        } else {
          alert("User not successfully");
          router.push("/login");
        }
      } catch (error) {
        if (error.response && error.response.status === 401) {
          emailError.value = "Email is incorrect!";
          passwordError.value = "Password is incorrect!";
          loginError.value = "Wrong your password or Wrong your Email!";
        } else { 
          console.error("Login failed:", error);
        }
      }
    }

    async function handleGoogleLogin(googleUser) {
      try {
        const idToken = googleUser.tokenId;
        const response = await axios.post("http://127.0.0.1:8000/api/google-login", {
          id_token: idToken,
        });
        console.log(response.data);
        await setAuthUser(response.data);
        router.push("/");
      } catch (error) {
        console.error("Google login failed:", error);
      }
    }

    function handleGoogleLoginError(error) {
      console.error("Google login error:", error);
    }
// 
    function togglePasswordVisibility() {
      showPassword.value = !showPassword.value;
    }
// 
    return {
      loading,
      email,
      password,
      showPassword,
      clientId,
      scope,
      buttonText,
      handleLogin,
      handleGoogleLogin,
      // add more options
      handleGoogleLoginError,
      emailError,
      passwordError,
      validateEmail,
      validatePassword,
      loginError,
      togglePasswordVisibility,
      // 
    };
  },
};
</script>

<style scoped>
@import "@fortawesome/fontawesome-free/css/all.css";

/* styel for validate */

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

/* styel for validate */

.login-page {
  background-color: #f0f2f5;
  display: flex;
  justify-content: center;
  align-items: center;
}

.login-container {
  display: flex;
  height: 100%;
  overflow: hidden;
  background-color: white;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  width: 100%;
}

.image-container {
  flex: 1.5;
  display: flex;
  justify-content: center;
  align-items: center;
  /* background-color: #007bff; */
}

.image-container img {
  width: 100%;
  height: auto;
  object-fit: cover;
}

.form-container {
  flex: 1;
  padding: 40px;
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
  margin-bottom: 1.5rem;
  padding: 12px;
  font-size: 1.1rem;
  border: 1px solid #ced4da;
  border-radius: 5px;
}

.btn-primary {
  background-color: #007bff;
  border-color: #007bff;
  font-size: 1.2rem;
  padding: 12px;
  border-radius: 5px;
}

.btn-facebook {
  background-color: #3b5998;
  color: white;
  font-size: 1.1rem;
  padding: 12px;
  border-radius: 5px;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.3s ease;
  text-align: center;
  cursor: pointer;
}

.btn-facebook:hover {
  background-color: #2d4373;
}

.btn-facebook i {
  margin-right: 10px;
}

.btn-google {
  background-color: #dd4b39;
  color: white;
  font-size: 1.1rem;
  padding: 12px;
  border-radius: 5px;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.3s ease;
  text-align: center;
  cursor: pointer;
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

.social-login p {
  font-size: 1rem;
  color: #555;
  text-align: center;
  margin-top: 1rem;
}

.social-login a {
  color: #007bff;
  text-decoration: none;
  font-weight: bold;
}

.social-login a:hover {
  text-decoration: underline;
}

.spinner-container {
  background-color: white;
  z-index: 999;
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
