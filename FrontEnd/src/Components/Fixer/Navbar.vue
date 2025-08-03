<template>
  <div class="container-fluid p-0">
    <div class="d-flex">
      <!-- Sidebar -->
      <aside class="sidebar border-end shadow-md bg-light">
        <div class="logo d-flex align-items-center">
          <div class="logo-color"></div>
          <h2 class="quickfix ms-2">QUICKFIX</h2>
        </div>
        <hr style="border: 2px solid orange" />

        <div class="sidebar-content">
          <button
            :class="{ 'btn-outline-secondary active': currentView === 'Dashboard' }"
            @click="setCurrentView('Dashboard')"
            class="btn w-100 mb-2"
          >
            Dashboard
          </button>
          <button
            :class="{
              'btn-outline-secondary active':
                currentView === 'Booking' || currentView === 'Booking2'
            }"
            @click="setCurrentView('Booking')"
            class="btn w-100 mb-2 position-relative"
          >
            Booking List
            <span
              v-if="request !== 0"
              class="position-absolute top-50 start-40 translate-middle badge rounded-pill bg-danger"
            >
              {{ request }}
              <span class="visually-hidden">unread messages</span>
            </span>
          </button>
          <button
            :class="{
              'btn-outline-secondary active':
                currentView === 'AcceptBooking' || currentView === 'Booking2'
            }"
            @click="setCurrentView('AcceptBooking')"
            class="btn w-100 mb-2 position-relative"
          >
            Accepted
            <span
              v-if="accepted !== 0"
              class="position-absolute top-50 start-40 translate-middle badge rounded-pill bg-danger"
            >
              {{ accepted }}
              <span class="visually-hidden">unread messages</span>
            </span>
          </button>
          <button
            :class="{ 'btn-outline-secondary active': currentView === 'Notification' }"
            @click="setCurrentView('Notification')"
            class="btn w-100 mb-2 position-relative"
          >
            Notification
            <span
              v-if="notificationCount !== 0"
              class="position-absolute top-50 start-40 translate-middle badge rounded-pill bg-danger"
            >
              {{ notificationCount }}
              <span class="visually-hidden">unread messages</span>
            </span>
          </button>
          <!-- <button
            :class="{ 'btn-outline-secondary active': currentView === 'Messanger' }"
            @click="setCurrentView('Messanger')"
            class="btn w-100 mb-2 position-relative"
          >
            Message
            <span
              v-if="accepted !== 0"
              class="position-absolute top-50 start-40 translate-middle badge rounded-pill bg-danger"
            >
              {{ accepted }}
              <span class="visually-hidden">unread messages</span>
            </span>
          </button> -->
          <button
            :class="{ 'btn-outline-secondary active': currentView === 'Payment' }"
            @click="setCurrentView('Payment')"
            class="btn w-100 mb-1"
          >
            <div class="d-flex gap-2">
              <p>Payment</p>
              <span class="text-warning text-5 -mt-2" v-if="payments.length > 0">
                <i class="bx bxs-bell-ring bx-tada"></i>
              </span>
            </div>
          </button>

          <button
            :class="{ 'btn-outline-secondary active': currentView === 'Skill' }"
            @click="setCurrentView('Skill')"
            class="btn w-100 mb-2"
          >
            Skill
          </button>
          <button
            :class="{ 'btn-outline-secondary active': currentView === 'HistoryFixer' }"
            @click="setCurrentView('HistoryFixer')"
            class="btn w-100 mb-2"
          >
            History
          </button>
        </div>
      </aside>
      <div class="main-content flex-grow-1">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">
            <div class="d-flex w-100 align-items-center">
              <div class="profile d-flex align-items-center gap-2">
                <div class="cardPro border rounded-circle p-2">
                  <img
                    src="/src/assets/img/cat.jpeg"
                    alt="pro"
                    class="w-100 bg-black shadow-lg rounded-circle"
                  />
                </div>
                <div class="cardtext">
                  <h6 class="m-0">{{ users.name }}</h6>
                  <h6 class="m-0">{{ users.id }}</h6>
                </div>
              </div>
              <!-- Dropdown menu -->
              <div class="dropdown ms-auto">
                <a
                  class="bi bi-list text-25px dropdown-toggle"
                  href="#"
                  role="button"
                  id="dropdownMenuLink"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                ></a>
                <ul
                  class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-2"
                  aria-labelledby="dropdownMenuLink"
                >
                  <li>
                    <router-link to="/profile" class="dropdown-item text-center"
                      >View Profile</router-link
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="dropdown-item text-center"
                      data-bs-toggle="modal"
                      data-bs-target="#editProfileModal"
                      >Change</a
                    >
                  </li>
                  <li>
                    <div @click="logout" class="dropdown-item text-center">Log Out</div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </nav>

        <div class="content p-3">
          <component :is="currentView"></component>
        </div>
      </div>
    </div>
  </div>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</template>

<script>
import axios from 'axios'
import Dashboard from './DashBoard.vue'
import Booking from './ListBooking.vue'
import Skill from './CardSkill.vue'
import ChatView from './ChatView.vue'
import HistoryView from './HistoryView.vue'
import AcceptBooking from './AcceptBooking.vue'
import Payment from './Payment.vue'
import HistoryFixer from './HistoryFixer.vue'
import Notification from './Notification.vue'
import Messanger from '@/Components/Messanger.vue'
import MapFixer from '@/Components/Map.vue'
export default {
  name: 'NavBar',
  components: {
    Dashboard,
    Booking,
    Skill,
    ChatView,
    Notification,
    HistoryView,
    AcceptBooking,
    Payment,
    HistoryFixer,
    Messanger,
    MapFixer
  },
  data() {
    return {
      users: {},
      currentView: 'Dashboard',
      payments: [],
      request: null,
      accepted: null,
      notificationCount: null
    }
  },
  methods: {
    setCurrentView(view) {
      this.currentView = view
      localStorage.setItem('currentView', view) 
    },
    async logout() {
      try {
        const token = localStorage.getItem('access_token')
        await axios.post(
          'http://127.0.0.1:8000/api/logout',
          {},
          {
            headers: {
              Authorization: `Bearer ${token}`
            }
          }
        )
        localStorage.clear()
        this.$router.push('/')
       setTimeout(() => {
  location.reload(); // Reload the page after a short delay
}, 100);
      } catch (error) {
        console.error('Logout failed:', error)
      }
    },
    async countRequest() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/booking')
        this.request = response.data.count
      } catch (error) {
        console.error('Error counting requests:', error)
      }
    },
    async countAccepted() {
      const fixerId = JSON.parse(localStorage.getItem('user')).id
      console.log(fixerId);
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/fixer/accepted/${fixerId}`)
        this.accepted = response.data.count
      } catch (error) {
        console.error('Error counting accepted bookings:', error)
      }
    },
    getUserData() {
      this.users = JSON.parse(localStorage.getItem('user')) || {}
    },
    async getPayments() {
      const fixer = JSON.parse(localStorage.getItem('user'))
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/getlistpay/${fixer.id}`)
        this.payments = response.data.filter((payment) => payment.status === 'no')
      } catch (error) {
        console.error('Error fetching payment list:', error)
      }
    },
    async getCountNotification() {
  try {
    const fixer = JSON.parse(localStorage.getItem('user'));
    const response = await axios.get(`http://127.0.0.1:8000/api/notification/show/${fixer.id}`);
    
    if (response.data && response.data.data) {
      this.notificationCount = response.data.data.length;
    } else {
      this.notificationCount = 0; 
    }
  } catch (error) {
    console.error('Error fetching notifications:', error);
    this.notificationCount = 0;  // Set to 0 in case of error
  }
}

  },
  mounted() {
    this.getUserData()
      this.getPayments()
      this.countRequest()
      this.countAccepted()

    setInterval(() => {
      this.getCountNotification();

  }, 2500);

    const storedView = localStorage.getItem('currentView')
    if (storedView) {
      this.currentView = storedView 
    }
  }
}

</script>

<style scoped>
.logo-color {
  background-color: orange;
  color: #ffffff;
  font-weight: bold;
  width: 70px;
  height: 70px;
  border-radius: 100%;
  margin-left: 26px;
}

.quickfix {
  display: flex;
  position: absolute;
  top: 30px;
  left: 50px;
}

.sidebar {
  width: 250px;
  min-height: 100vh; /* Ensure sidebar stretches to full height */
  background-color: #f8f9fa;
  padding: 1rem 0;
}

.sidebar-content {
  display: flex;
  flex-direction: column;
  margin-top: 60px;
  align-items: flex-start;
}
.sidebar-content button {
  display: flex;
  flex-direction: column;
  margin-top: 20px;
  align-items: flex-start;
  padding-left: 30px;
}

.btn-outline-secondary {
  position: relative;
  transition: all 0.3s ease-in-out;
  border: none;
  padding: 13px 0;
  margin: 10px 0;
  border-radius: 0px;
}

.btn-outline-secondary:hover,
.btn-outline-secondary.active {
  background-color: orange;
  color: white;
  padding-left: 40px;
  border: 0px;
}
.main-content {
  width: calc(100% - 250px); /* Adjust for sidebar width */
  min-height: 100vh; /* Ensure main content stretches to full height */
}

.navbar {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 1rem;
  border-bottom: 1px solid #dee2e6;
}

.profile {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.cardPro {
  width: 50px; /* Adjust profile image size */
  height: 50px;
  border-radius: 50%;
  overflow: hidden;
}

.cardPro img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.cardtext h6 {
  margin: 0;
  font-size: 0.875rem;
}

@media (max-width: 768px) {
  .sidebar {
    width: 200px;
  }

  .main-content {
    width: calc(100% - 200px);
  }

  .sidebar-title {
    font-size: 1.5rem;
  }

  .navbar {
    height: auto;
  }
}
</style>