<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid">
      <!-- Brand logo -->
      <router-link to="/" class="navbar-brand d-flex align-items-center">
        <img src="../assets/images/logo.png" alt="Logo" class="logo" />
      </router-link>

      <!-- Navbar Toggler -->
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar Items -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav navbar-nav-left me-auto mb-2 mb-lg-0">
          <!-- Home -->
          <li class="nav-item">
            <router-link to="/" class="nav-link">
              <i class="bi bi-house-door icon"></i>
            </router-link>
          </li>

          <!-- Fixer List -->
          <li class="nav-item">
            <router-link to="/fixer" class="nav-link">
              <img
                src="https://media.istockphoto.com/id/1445981943/vector/repair-service-man-worker-logo-mechanic-workshop-vector-illustration.jpg?s=612x612&w=0&k=20&c=vizyPckB7zfeO0HYpJaj6uSm1jiZ9ozqlFWwWeFPCy4="
                alt="Fixer Icon"
                style="width: 35px; border-radius: 50%"
              />
            </router-link>
          </li>

          <!-- Offer Button -->
        </ul>
        <div class="center">
          <button class="btn offer-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            OFFER
          </button>
        </div>
        <div class="center1" v-if="isLoggedIn"></div>
        <div
          class="modal fade custom-slide-modal"
          id="staticBackdrop"
          tabindex="-1"
          aria-labelledby="staticBackdropLabel"
          aria-hidden="true"
          data-bs-backdrop="false"
          style="margin-top: 40px"
        >
          <div class="modal-dialog modal-dialog-centered">
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
                <form>
                  <div class="mb-3 hello d-flex align-items-center"></div>
    <div v-if="promotions && promotions.length != 0">
      <div
        class="mb-3 card d-flex align-items-center"
        v-for="promotion in promotions"
        :key="promotion.id"
      >
        <div class="discount">Promotion {{ promotion.discount }}%</div>
        <div class="text-truncate" style="max-width: 200px">
          <!-- Adjust max-width as needed -->
          🎉{{ promotion.description }}💛
        </div>
        <div class="date">Start Date: {{ promotion.start_date }}</div>
        <div class="date">End Date: {{ promotion.end_date }}</div>
      </div>
    </div>
    <div v-else>
      <span>No promotions available.</span>
    </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Cart Modal -->
        <div
          class="modal fade bd-cart-modal"
          tabindex="-1"
          role="dialog"
          aria-labelledby="cartModalLabel"
          aria-hidden="true"
          data-bs-backdrop="false"
        >
          <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">Your Cart</h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-6">
                    <div class="raw">
                      <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th scope="col">Item</th>
                            <th scope="col">Date</th>
                            <th scope="col">Fixer</th>
                            <th scope="col">Booking</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody v-if="listBookings !== null && listBookings.length !== 0">
                          <tr v-for="(booking, index) in listBookings" :key="index">
                            <td>
                              {{ booking[0].booking.service ? booking[0].booking.service : 'N/A' }}
                            </td>
                            <td>{{ booking[0].booking.date }}</td>
                            <td>{{ booking[0].fixer ? booking[0].fixer.name : 'N/A' }}</td>
                            <td>{{booking[0].action}}</td>
                            <td>
                              <button class="btn btn-danger" @click="cancelBooking(booking[0].id)">
                                Cancel
                              </button>
                            </td>
                          </tr>
                        </tbody>
                        <tbody v-else>
                          <tr>
                            <td colspan="3">No bookings found.</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="col col-1"></div>
                  <div class="col-4">
                    <!-- <div class="row"></div> -->
                    <div class="card row w-100">
                      <div class="w-100">
                        <div class="card-header chat-header" style="background: orange">
                          <h5 class="card-title mb-0">Live Chat</h5>
                        </div>
                        <div class="card-body chat-body">
                          <div class="messages">
                            <div class="message received">
                              <div class="message-content"></div>
                            </div>
                            <div class="message sent" style="background: gray">
                              <div class="message-content"></div>
                            </div>
                          </div>
                        </div>
                        <div class="card-footer chat-footer">
                          <div class="input-group">
                            <input
                              type="text"
                              class="form-control"
                              placeholder="Type your message..."
                            />
                            <div class="input-group-append">
                              <button class="btn btn-orange">Send</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="right navbar-nav-right">
          <button
            v-if="isLoggedIn && listBookings"
            type="button"
            class="btn cart position-relative"
            data-bs-toggle="modal"
            data-bs-target=".bd-cart-modal"
          >
            <i class="bi bi-cart" style="font-size: 20px"></i>
            <span
              v-if="listBookings.length != 0"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
            >
              {{ listBookings.length }}
              <span class="visually-hidden">unread messages</span>
            </span>
          </button>

          <div class="nav-item position-relative" @click="toggleNotifications">
            <i class="bi bi-bell icon" title="Notifications"></i>
            <span class="tooltip-text">Notifications</span>
            <span v-if="isLoggedIn && notifications && notifications.length != 0" class="badge bg-danger rounded-pill notification-badge">{{notifications.length}}</span>
            <div v-if="showNotifications" class="notification-dropdown">
              <ul style="width:300px">
                <li v-for="(notification, index) in notifications" :key="index"> 
                  {{ notification.message }}
                </li>
              </ul>
            </div>
          </div>

          <router-link v-if="!isLoggedIn" to="/signup" class="nav-item position-relative">
            <button class="btn btn-orange" style="padding: 6.5px 10px">Register</button>
          </router-link>
          <router-link v-if="!isLoggedIn" to="/login" class="nav-item position-relative">
            <button class="btn btn-danger">Login</button>
          </router-link>

          <div v-if="isLoggedIn" class="nav-item dropdown position-relative">
            <a
              class="nav-link dropdown-toggle p-0"
              href="#"
              id="navbarDropdown"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <img
                :src="
                  authStore.user?.profile ||
                  'https://st3.depositphotos.com/1767687/17621/v/450/depositphotos_176214104-stock-illustration-default-avatar-profile-icon.jpg'
                "
                alt="Profile"
                title="Profile"
                class="profile-image"
              />
              <span class="tooltip-text">Profile</span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li>
                <router-link to="/profile" class="dropdown-item">
                  <i class="bi bi-eye-fill"></i>
                  <p>View Profile</p>
                </router-link>
              </li>
              <li>
                <router-link to="#" class="dropdown-item">
                  <i class="bi bi-clock-history"></i>
                  <p>History</p>
                </router-link>
              </li>
              <li>
                <a href="#" class="dropdown-item" @click="logout">
                  <i class="bi bi-box-arrow-right"></i>
                  <p>Logout</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth-store'
import axios from 'axios'
import { Loader } from '@googlemaps/js-api-loader'

const apiKey = 'AIzaSyAEkPs2AFjaazwiQaO25lkaHp-nlX00sK0'
const loader = new Loader({
  apiKey: apiKey,
  version: 'beta',
  libraries: ['places']
})

const map = ref(null)
const marker = ref(null)
const promotions = ref(null)
const latitude = ref('')
const longitude = ref('')
const listBookings = ref(null)
    const notifications = ref();
const authStore = useAuthStore()
const showNotifications = ref(false)
const storeLocation = ref({ lat: null, lng: null })

const fetchNotifications = async () => {
      try {
        const userId = JSON.parse(localStorage.getItem('user')).id; // Replace with dynamic user ID if necessary
        const response = await axios.get(`http://127.0.0.1:8000/api/notification/customer/${userId}`);
        notifications.value = response.data.data;
        console.log(response.data.data);
      } catch (error) {
        console.error('Error fetching notifications:', error);
      }
    };

const initializeMap = async () => {
  await loader.load()
  map.value = new google.maps.Map(document.querySelector('.map'), {
    center: { lat: 12.5657, lng: 104.917 },
    zoom: 8
  })
}

const isLoggedIn = computed(() => !!authStore.user)

onMounted(async () => {
  await listPromotion()
  await listbooking()
  await fetchNotifications()
  initializeMap()
  setInterval(listbooking, 2000)
})

const logout = async () => {
  try {
    authStore.logout()
    location.reload()
  } catch (error) {
    console.error('Error logging out:', error)
  }
}

async function listPromotion() {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/promotion/list')
    promotions.value = response.data
  } catch (error) {
    console.log('error getting promotion')
  }
}

async function listbooking() {
  const user = JSON.parse(localStorage.getItem('user')).id
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/booking/show/' + user)
    listBookings.value = response.data.bookings
  } catch (error) {
    console.log('error getting promotion')
  }
}
const cancelBooking = async (bookingId) => {
  const user = JSON.parse(localStorage.getItem('user')).id

  try {
    const response = await axios.delete(`http://127.0.0.1:8000/api/customer/cancel/${bookingId}`, {
      user_id: user
    })
    console.log('Booking cancelled:', bookingId)
    // Optionally update listBookings or show a message
  } catch (error) {
    console.error('Error cancelling booking:', error)
  }
}
const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value
}
</script>

<style scoped>
/* Navbar Styles */
.custom-slide-modal .modal-dialog {
  margin: 0;
  transform: translateX(100%);
  transition: transform 0.3s ease-out;
  z-index: 1050; /* Ensure modal appears above modal backdrop */
}
.modal-body {
  overflow-y: scroll;
  height: 450px;
}

.hoverable::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: 0;
  width: 0%;
  height: 2px;
  background-color: #007bff;
  transition: width 0.3s ease;
}

.hoverable:hover::after {
  width: 100%;
}
#staticBackdrop {
  position: fixed;
  top: 30px;
  left: 0;
  width: 140%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1040;
}
.card {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 15px;
  max-width: 350px;
  text-align: center;
  margin-left: 6%;
}
.card .title {
  font-size: 1em;
  color: #ffa000;
}

.card .discount {
  font-size: 1.8em;
  margin: 10px 0;
  color: #000;
  font-weight: bold;
}
.card .date {
  font-size: 15px;
}
.modal-backdrop {
  background: white;
  position: relative;
}
/* Logo */
.logo {
  width: 150px;
  height: auto;
}
.btn-orange {
  background-color: orange;
  color: #fff;
  border: none;
  padding: 8px 16px;
  cursor: pointer;
  font-size: 1rem;
  border-radius: 4px;
}
.chat-card {
  width: 100%;
}

.chat-header {
  background-color: #0084ff;
  color: white;
  padding: 10px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.chat-body {
  height: 300px;
  overflow-y: auto;
  padding: 15px;
}

.messages {
  display: flex;
  flex-direction: column;
}

.message {
  max-width: 70%;
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 10px;
}

.message.received {
  align-self: flex-start;
  background-color: #f0f0f0;
}

.message.sent {
  align-self: flex-end;
  background-color: #0084ff;
  color: white;
}

.message-content {
  word-wrap: break-word;
}

.chat-footer {
  padding: 10px;
  border-top: 1px solid rgba(0, 0, 0, 0.125);
}

.input-group {
  margin-bottom: 0;
}

.btn-orange:hover {
  background-color: darkorange;
}
.navbarSupportedContent {
  display: flex;
  background: #000;
  flex: 0.5;
}

/* Offer Button */
.offer-btn {
  padding: 7px 20px;
  background-color: orange;
  color: white;
  width: 100%;
  border: none;
  cursor: pointer;
}

.offer-btn:hover {
  background-color: #ff7f50; /* Lighter shade of orange on hover */
}

/* Profile Image */
.profile-image {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

/* Navbar Toggler */
.navbar-toggler {
  border-color: rgba(0, 0, 0, 0.1);
}

/* Notification Badge */
.notification-badge {
  position: absolute;
  top: 0;
  right: 11px;
  z-index: 1;
  font-size: 0.8rem;
}

/* Icon Styles */
.icon {
  font-size: 24px;
  margin: 0 1.5rem;
  cursor: pointer;
  transition: transform 0.1s ease, box-shadow 0.1s ease;
  color: #343a40;
}

.icon:hover {
  transform: scale(1.3);
  color: #ff7f50;
}
.navbar-nav-right {
  display: flex;
  justify-content: end;
  gap: 3rem;
  flex: 0.5;
}
.dropdown-item {
  display: flex;
  gap: 5px;
}
.center {
  display: flex;
  flex: 0.2;
  justify-content: center;
}
.center1 {
  display: flex;
  margin-left: 4%;
  flex: 0.2;
  justify-content: center;
}
.navbar-nav-left {
  display: flex;
  justify-content: center;
  flex: 0.5;
  gap: 2rem;
}
/* Tooltip Text */
.tooltip-text {
  visibility: hidden;
  width: 120px;
  background-color: #0a0a0a88;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  position: absolute;
  z-index: 1;
  bottom: -55%;
  left: 50%;
  margin-left: -30px;
  opacity: 0;
  transition: opacity 0.3s;
  font-size: 15px;
}

.nav-item:hover .tooltip-text {
  visibility: visible;
  opacity: 1;
}

/* Dropdown Menu */
.dropdown-menu {
  position: absolute;
  background-color: #fff;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border: none;
  border-radius: 5px;
  padding: 10px;
  left: -90px; /* Adjust dropdown position */
}

.dropdown-menu .dropdown-item {
  padding: 8px 20px;
  color: #343a40;
}

.dropdown-menu .dropdown-item:hover {
  background-color: orange;
  color: #fff;
}

.dropdown-toggle::after {
  display: none;
}

/* Notification Dropdown */
.notification-dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  background-color: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  border-radius: 6px;
  z-index: 10;
  padding: 5px 0;
  min-width: 200px;
  text-align: left;
}

.notification-dropdown ul {
  list-style: none;
  padding: 0;
}

.notification-dropdown ul li {
  padding: 10px;
  cursor: pointer;
}

.notification-dropdown ul li:hover {
  background-color: #f1f1f1;
}

/* Responsive Styles */
@media (max-width: 992px) {
  .navbar-toggler {
    border-color: #ff7f50; /* Orange color for toggler on smaller screens */
  }

  .navbar-collapse {
    background-color: #fff;
  }

  .dropdown-menu {
    left: -60px; /* Adjust dropdown position */
  }

  .notification-dropdown {
    left: unset;
    right: 10px; /* Adjust notification dropdown position */
  }
}
.popup {
  position: absolute;
  z-index: 1; /* Set a high z-index value to ensure it appears on top */
  background-color: #fff; /* Add a background color to make it visible */
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.popup-container {
  position: relative; /* Set the container to relative positioning */
}
.custom-slide-modal .modal-dialog {
  position: fixed;
  top: 25px;
  right: 0;
  width: 40%; /* Adjust width as per your design */
  height: 100%;
  margin: 0;
  transform: translateX(100%);
  transition: transform 0.3s ease-out;
  z-index: 1050; /* Ensure modal appears above modal backdrop */
}

#staticBackdrop.show .modal-dialog {
  transform: translateX(0%);
}

.modal-backdrop {
  z-index: 1040; /* Lower than the modal's z-index */
}

@media (max-width: 992px) {
  .custom-slide-modal .modal-dialog {
    width: 80%; /* Adjust for smaller screens */
  }
}
</style>
