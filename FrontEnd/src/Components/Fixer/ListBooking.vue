<template>
  <div class="container">
    <div class="mb-5 p-4">
      <h1 class="text-3xl font-bold text-gray-900 mb-5 text-center">Customer Bookings</h1>
      <ul id="list-booking" class="list-unstyled d-flex flex-wrap justify-content-center">
        <li v-for="book in bookings" :key="book.id" class="list-booking-item rounded-lg overflow-hidden shadow-md mx-3 mb-4">
          <div class="card">
            <div class="card-body d-flex flex-column">
              <div class="map-placeholder">
                <div class="map" :id="'map_' + book.id"></div>
              </div>
              <div class="profile-info-overlay p-3 d-flex align-items-center">
                <img
                  src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png"
                  class="rounded-circle profile-image mr-3"
                  style="width: 50px; height: 50px;"
                  alt="Profile"
                />
                <div>
                  <h3 class="h5 mb-1">{{ book.user.name }}</h3>
                  <p class="mb-1">{{ book.user.location }}</p>
                  <p class="mb-1">Service: {{ getServiceName(book.booking.service_id) }}</p>
                  <p class="mb-0">Date: {{ book.booking.date }}</p>
                </div>
              </div>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center p-3">
              <button class="btn btn-accept" @click="acceptBooking(book.booking.id)">
                Accept Booking
                <i class="bi bi-check2-circle ml-2"></i>
              </button>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const accessToken = localStorage.getItem('access_token')
const bookings = ref([])

async function getBookings() {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/booking', {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        'Content-Type': 'application/json'
      }
    })
    bookings.value = response.data.bookings
    initializeMaps();
  } catch (error) {
    console.error('Backend error:', error.response.data)
  }
}

async function acceptBooking(bookingId) {
  try {
    const fixer = JSON.parse(localStorage.getItem('user'))
    const fixer_id = fixer.id

    const response = await axios.post(
      'http://127.0.0.1:8000/api/fixer/accept',
      {
        fixer_id: fixer_id,
        booking_id: bookingId
      },
      {
        headers: {
          Authorization: `Bearer ${accessToken}`,
          'Content-Type': 'application/json'
        }
      }
    )
    getBookings()
  } catch (error) {
    console.error('Backend error:', error.response.data)
  }
}

function initializeMaps() {
  // Initialize Google Maps for each booking with lat/lng
  bookings.value.forEach(book => {
    const mapElement = document.getElementById('map_' + book.id);
    if (mapElement) {
      const map = new google.maps.Map(mapElement, {
        center: { lat: parseFloat(book.booking.latitude), lng: parseFloat(book.booking.longitude) },
        zoom: 12 // Adjust zoom level as needed
      });
      new google.maps.Marker({
        position: { lat: parseFloat(book.booking.latitude), lng: parseFloat(book.booking.longitude) },
        map: map,
        title: book.user.name // Marker title
      });
    }
  });
}

// Load Google Maps API script asynchronously
function loadGoogleMapsScript() {
  const script = document.createElement('script');
  script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyAEkPs2AFjaazwiQaO25lkaHp-nlX00sK0&callback=initializeMaps`;
  script.defer = true;
  script.async = true;
  script.onload = getBookings; // Trigger getBookings() after script is loaded
  document.head.appendChild(script);
}

function getServiceName(serviceId) {
  switch (serviceId) {
    case 1:
      return 'Mouse';
    case 2:
      return 'Car';
    default:
      return 'Unknown Service';
  }
}

onMounted(() => {
  loadGoogleMapsScript();
  getBookings()
});
</script>

<style scoped>
.list-booking-item {
  width: 30%;
  display: flex;
  /* justify-content: s; */
  }

.card {
  width: 100%;
  height: 400px;
  transition: transform 0.3s ease-in-out;
  /* background-color: #fff; */
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 10px;
}

.card-body {
  padding: 0;
}

.map {
  width: 100%;
  height: 200px; /* Adjust height as needed */
}

.map-placeholder {
  position: relative;
  width: 100%;
  height: 200px; /* Adjust height as needed */
  overflow: hidden;
  border-radius: 8px;
}

.profile-info-overlay {
  position: absolute;
  bottom: 70px;
  left: 0;
  width: 100%;
  background-color: rgba(255, 255, 255, 0.8);
  padding: 10px;
  border-bottom-left-radius: 8px;
  border-bottom-right-radius: 8px;
}

.profile-image {
  border: 2px solid #fff; /* Ensure the image border is visible */
}

.btn-accept {
  background-color: #f0ad4e;
  color: #fff;
  border: none;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-accept:hover {
  background-color: #ec971f;
}
</style>
