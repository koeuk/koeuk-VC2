<template>
  <div>
    <NavbarView />
<div class="search-container mt-5 d-flex justify-content-center" style="margin-top:200px">
      <select class="form-select">
        <option value="1">Select Career</option>
        <option value="1">Select Career</option>
        <option value="1">Select Career</option>
        <!-- <option v-for="career in careers" :key="career.id" :value="career.name">
          {{ career.name }}
        </option> -->
      </select>
      <select class="form-select">
        <option value="">Select Place</option>
        <!-- <option v-for="place in places" :key="place.id" :value="place.name">
          {{ place.name }}
        </option> -->
      </select>
    </div>
    <hr />

    <div class="repairers-list container m-4 d-flex gap-5 flex-wrap">
      <div v-if="filteredRepairers.length === 0" class="no-results">No repairers found.</div>
      <div v-for="repairer in filteredRepairers" :key="repairer.id" class="repairer-card">
        <div class="repairer-content">
          <div class="repairer-image">
            <img :src="repairer.image || 'https://via.placeholder.com/100'" alt="Repairer Image" />
          </div>
          <div class="repairer-info">
            <h3>
              {{ repairer.name }}
            </h3>
            <p class="career">
              Career: <span>Computer</span>
            </p>
            <p class="place">
              <span style="color:red">Phnom Penh</span>
            </p>
          </div>
          <div class="repairer-icons d-flex justify-content-end">
  <button
    class="btn btn-primary"
    data-bs-toggle="modal"
    data-bs-target="#bookingModal"
    @click="openBookingModal(repairer.id)"
  >
    Book
  </button>
</div>

        </div>
      </div>
    </div>

    <!-- Modal for Booking Form -->
    <div
      class="modal fade"
      id="bookingModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Booking</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <form @submit="submitBooking" class="booking-form">
              <div class="form-group-map-container">
                <div class="form-group-map">
                  <div class="input-group">
                    <input type="hidden" v-model="location" />
                    <input
                      type="text"
                      class="location form-control"
                      v-model="reverseGeocodeResult"
                      @input="searchSimilarPlaces"
                      placeholder="Enter location or use the map icon"
                      required
                    />
                    <button type="button" class="btn btn-map" @click="getCurrentLocation">
                      <i class="bi bi-geo-alt"></i>
                    </button>
                  </div>
                  <div v-if="similarPlaces.length" class="similar-places">
                    <ul>
                      <li
                        v-for="place in similarPlaces"
                        :key="place.id"
                        @click="selectPlace(place)"
                      >
                        {{ place.place_name }}
                      </li>
                    </ul>
                  </div>
                  <div class="form-group">
                    <label for="bookingDate">Booking Date:</label>
                    <div class="date">
                      <input type="date" v-model="bookingDate" required class="form-control date" />
                      <button type="button" class="btn btn-map" @click="setTodayDate">Today</button>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="promotion">Promotion Code:</label>
                    <input
                      type="text"
                      v-model="promotionCode"
                      placeholder="Enter promotion code (if any)"
                      class="form-control"
                    />
                  </div>
                  <div class="form-group">
                    <textarea
                      v-model="information"
                      placeholder="More information....."
                      class="form-control"
                    ></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">
                    Book
                  </button>
                </div>
                <div class="map" ref="mapContainer"></div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import NavbarView from '@/Components/WebHeaderMenu.vue'
import '@fortawesome/fontawesome-free/css/all.css'
import mapboxgl from 'mapbox-gl'
import 'mapbox-gl/dist/mapbox-gl.css'

mapboxgl.accessToken =
  'pk.eyJ1Ijoic2llbWNob3ViMTExMSIsImEiOiJjbHg3bDRrdGowaW1kMmxweG50MHdpazMzIn0.cAYH_6kwxhwH43FM46qmOg'

const fixers = ref([])
const careers = ref([{ name: '' }])
const places = ref([{ name: '' }])
const selectedCareer = ref('')
const selectedPlace = ref('')
const location = ref('')
const reverseGeocodeResult = ref('')
const selectedService = ref('')
const bookingDate = ref('')
const description = ref('')
const promotionCode = ref('')
const information = ref('')
const categories = ref([])
const services = ref([])
const similarPlaces = ref([])
const fixer_id = ref()
onMounted(() => {
  getFixer()
})
const openBookingModal = (id) => {
  fixer_id.value = id
}

const getFixer = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/fixer/list')
    fixers.value = response.data
  } catch (error) {
    console.error('Error fetching fixers:')
  }
}

const openBookingForm = (repairer) => {
  console.log('Opening modal for repairer:', repairer)
  $('#bookingModal').modal('show')
}

// const submitBookingForm = () => {
//   console.log('Submitting booking form:', bookingForm.value)
//   $('#bookingModal').modal('hide')
//   bookingForm.value.name = ''
//   bookingForm.value.email = ''
// }

const filteredRepairers = computed(() => {
  let filtered = fixers.value

  if (selectedCareer.value) {
    filtered = filtered.filter((repairer) => repairer.career === selectedCareer.value)
  }

  if (selectedPlace.value) {
    filtered = filtered.filter((repairer) => repairer.place === selectedPlace.value)
  }

  return filtered
})

onMounted(async () => {
  await fetchCategories()
  await fetchServices()
  initializeMap()
})

async function fetchCategories() {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/category/list')
    categories.value = response.data
  } catch (error) {
    console.error('Error fetching categories:', error)
  }
}

async function fetchServices() {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/service/list')
    services.value = response.data
  } catch (error) {
    console.error('Error fetching services:', error)
  }
}

const submitBooking = async (event) => {
  event.preventDefault()

  const userString = localStorage.getItem('user')
  if (!userString) {
    console.error('No user found in localStorage')
    return
  }

  const user = JSON.parse(userString)
  const user_id = user.id
  const today = new Date().toISOString().split('T')[0]
  const isImmediateBooking = bookingDate.value === today

  const [latitude, longitude] = location.value.split(',').map((coord) => parseFloat(coord.trim()))

  const bookingData = {
    user_id,
    fixer_id: fixer_id.value,
    date: bookingDate.value,
    promotion_id: promotionCode.value || null,
    message: information.value || null,
    latitude,
    longitude
  }

  try {
    let response
    if (isImmediateBooking) {
      response = await axios.post('http://127.0.0.1:8000/api/bookin_immediatly', bookingData)
    } else {
      response = await axios.post('http://127.0.0.1:8000/api/bookin_deadline', bookingData)
      console.log(response.data)
    }
    
  } catch (error) {
    console.error('Error submitting booking:', error)
  }
}

const getCurrentLocation = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const latLng = [position.coords.longitude, position.coords.latitude]
        location.value = `${latLng[1]}, ${latLng[0]}`
        reverseGeocode(latLng)
        map.flyTo({ center: latLng, zoom: 16 })
        addMarker(latLng)
        setTodayDate()
      },
      (error) => {
        console.error('Error getting location:', error)
      }
    )
  } else {
    alert('Geolocation is not supported by this browser.')
  }
}

const setTodayDate = () => {
  const today = new Date().toISOString().split('T')[0]
  bookingDate.value = today
}

let map
const initializeMap = () => {
  const cambodiaBounds = [
    [102.144, 10.486],
    [107.625, 14.704]
  ]

  map = new mapboxgl.Map({
    container: document.querySelector('.map'),
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [104.917, 12.5657],
    zoom: 6,
    maxBounds: cambodiaBounds
  })

  map.on('load', () => {
    let rmFoot = document.querySelector('.mapboxgl-ctrl-bottom-right .mapboxgl-ctrl-attrib')
    let rmFoot1 = document.querySelector('.mapboxgl-ctrl-bottom-left')
    if (rmFoot) {
      rmFoot.style.display = 'none'
      rmFoot1.style.display = 'none'
    }
  })
}

const addMarker = (latLng) => {
  new mapboxgl.Marker().setLngLat(latLng).addTo(map)
}

async function reverseGeocode(latLng) {
  try {
    const response = await axios.get(
      `https://api.mapbox.com/geocoding/v5/mapbox.places/${latLng[0]},${latLng[1]}.json`,
      {
        params: {
          access_token: mapboxgl.accessToken
        }
      }
    )
    if (response.data.features.length > 0) {
      const place = response.data.features[0]
      reverseGeocodeResult.value = place.place_name

      const street = place.text
      const specificLocation = place.properties.address
    } else {
      console.error('No results found for reverse geocoding.')
    }
  } catch (error) {
    console.error('Error in reverse geocoding:', error)
  }
}

const searchSimilarPlaces = async () => {
  try {
    const response = await axios.get(
      'https://api.mapbox.com/geocoding/v5/mapbox.places/' +
        encodeURIComponent(reverseGeocodeResult.value) +
        '.json',
      {
        params: {
          access_token: mapboxgl.accessToken,
          autocomplete: true
        }
      }
    )
    similarPlaces.value = response.data.features
  } catch (error) {
    console.error('Error searching similar places:', error)
  }
}

const selectPlace = (place) => {
  location.value = `${place.center[1]}, ${place.center[0]}`
  reverseGeocodeResult.value = place.place_name
  similarPlaces.value = []
  map.flyTo({ center: place.center, zoom: 18 })
  addMarker(place.center)
}
</script>

<style scoped>
.search-container {
  text-align: center;
  margin-top: 500px;
  background: #000;
}
hr {
  border: 2px solid orange;
  margin-top: 50px;
  width: 94%;
  margin: auto;
  margin-top: 40px;
  margin-bottom: 40px;
  align-self: center;
}

.career span {
  color: blue;
  font-family: cursive;
}

.repairers-list {
  margin-top: 20px;
  display: flex;
  flex-wrap: wrap;
}
.repairer-card {
  background-color: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  margin-bottom: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s ease-in-out;
  overflow: hidden;
  width: 30%;
}

.repairer-card:hover {
  transform: translateY(-5px);
}

.repairer-content {
  display: flex;
  align-items: center;
  padding: 15px;
}

.repairer-image {
  flex: 0 0 auto;
  width: 100px;
  height: 100px;
  border-radius: 50%;
  overflow: hidden;
  border: 2px solid #fff;
}

.repairer-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.repairer-info {
  padding-left: 15px;
  height: 25vh;
}

.repairer-info h3 {
  margin: 0;
  font-size: 1.2rem;
  font-weight: bold;
}

.repairer-info p {
  margin: 5px 0;
  color: #666;
}

.repairer-icons {
  align-self: flex-end;
  display: flex;
  justify-content: space-between;
  align-items: end;
  width: 40%;
  border-radius: 0px;
  padding: 5px 0;
  /* gap: ; */
}

.repairer-icons i {
  color: white;
  font-size: 20px;
  margin-right: 10px;
  padding: 4px 10px;
    border-radius: 50%;
    background: orange;
    cursor: pointer;

  
}

.repairer-icons button {
  /* flex: 0 0 auto; */
  margin-right: 10px;
  background: orange;
  /* border-redius:1px; */
  padding: 8px 10px;
  border: none;
}
.repairer-icons .btn-primary:hover {
  background-color:gainsboro;
  box-shadow: rgba(0, 0, 0, 1);
  color: orange;
}
.repairer-icons i:hover{
  cursor: pointer;
  color: #000;
}
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-wrapper {
  width: 80%;
  margin: 20px;
  max-height: 90%;
  padding: 30px;
}

.modal-container {
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
  padding: 30px;
  position: relative;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.modal-header h3 {
  margin: 0;
  font-size: 1.5rem;
  font-weight: bold;
}

.close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
}

.modal-body {
  margin-bottom: 20px;
}

.booking-form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.form-group-map-container {
  display: flex;
  gap: 20px;
}

.form-group-map {
  display: flex;
  flex-direction: column;
  gap: 15px;
  width: 50%;
}

.map {
  width: 50%;
  height: 300px;
  background-color: #f0f0f0;
}

.form-group-select {
  display: flex;
  width: 100%;
  justify-content: space-between;
  gap: 1rem;
}

.form-group-select select {
  flex: 1;
  padding: 10px;
}

.form-group label {
  margin-bottom: 5px;
  font-weight: bold;
}
.date {
  display: flex;
}
.date input {
  border-radius: 0 5px 5px 0;
}

.form-group input,
.location,
.form-group textarea,
.form-group select {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1rem;
  width: 100%;
}

.input-group {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.input-group input {
  flex: 1;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

.input-group-append {
  margin-left: 10px;
}

.btn {
  cursor: pointer;
  font-size: 1rem;
  padding: 11.5px 20px;
  text-align: center;
} 

 .btn-primary {
  background-color: orange;
  color: white;
  border: none;
}

.btn-primary:hover {
  background-color: #0056b3;
}

.btn-map {
  background-color: orange;
  color: white;
  padding: 11px 10px;
  border: none;
  width: 20%;
}

.btn-map:hover {
  background-color: #0056b3;
}

.transition-enter-active,
.transition-leave-active {
  transition: opacity 0.3s ease;
}

.transition-enter-from,
.transition-leave-to {
  opacity: 0;
}
.similar-places {
  position: absolute;
  background-color: white;
  width: calc(100% - 20px);
  max-height: 200px;
  overflow-y: auto;
  z-index: 1000;
  margin-top: 50px;
  border: 1px solid #ddd;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
}

.similar-places ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

.similar-places ul li {
  padding: 10px;
  cursor: pointer;
}

.similar-places ul li:hover {
  background-color: #f0f0f0;
}
textarea {
  height: 100px;
  resize: none;
}
</style>
