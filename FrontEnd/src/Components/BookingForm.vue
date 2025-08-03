<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="modal-header">
            <h3>Book Service</h3>
            <button class="close" @click="$emit('close')">&times;</button>
          </div>
          <div class="modal-body">
            <div v-if="service">
              <transition name="alert-fade">
                <div v-if="alertMessage" :class="`alert alert-${alertType}`" role="alert">
                  {{ alertMessage }}
                </div>
              </transition>
              <form @submit.prevent="submitBooking" class="booking-form">
                <div class="form-group-map-container">
                  <div class="form-group-map">
                    <input type="hidden" v-model="latitude" />
                    <input type="hidden" v-model="longitude" />

                    <div class="input-group">
                      <input
                        type="text"
                        class="form-control map-search"
                        v-model="reverseGeocodeResult"
                        @input="searchSimilarPlaces"
                        placeholder="Enter location or use the map icon"
                      />
                      <span class="input-group-append">
                        <button type="button" class="btn btn-map" @click="getCurrentLocation">
                          <i class="bi bi-geo-alt"></i>
                        </button>
                      </span>
                    </div>

                    <div v-if="similarPlaces.length" class="similar-places">
                      <ul class="list-group">
                        <li v-for="place in similarPlaces" :key="place.place_id" class="list-group-item" @click="selectPlace(place)">
                          {{ place.place_name }}
                        </li>
                      </ul>
                    </div>

                    <input
                      v-else
                      type="hidden"
                      class="form-control map-search"
                      v-model="reverseGeocodeResult"
                      placeholder="Enter location or use the map icon"
                      required
                    />

                    <div class="form-group">
                      <label for="bookingDate">Booking Date:</label>
                      <div class="date">
                        <input type="date" v-model="bookingDate" required class="form-control" />
                        <button type="button" class="btn btn-secondary day" @click="setTodayDate">Today</button>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="promotion">Promotion Code:</label>
                      <input type="text" v-model="promotionCode" placeholder="Enter promotion code (if any)" class="form-control" />
                    </div>

                    <div class="form-group">
                      <textarea v-model="description" placeholder="More information....." class="form-control"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Book</button>
                  </div>

                  <div class="map" ref="mapContainer"></div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted, defineEmits, defineProps } from 'vue';
import { Loader } from '@googlemaps/js-api-loader';

const apiKey = 'AIzaSyAEkPs2AFjaazwiQaO25lkaHp-nlX00sK0';
const loader = new Loader({
  apiKey: apiKey,
  version: 'beta',
  libraries: ['places']
});

const props = defineProps({
  service: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['close']);

const reverseGeocodeResult = ref('');
const bookingDate = ref('');
const description = ref('');
const promotionCode = ref('');
const similarPlaces = ref([]);
const map = ref(null);
const marker = ref(null);
const geocoder = ref(null);
const placesService = ref(null);
const alertMessage = ref('');
const alertType = ref('success');
const latitude = ref('');
const longitude = ref('');

onMounted(async () => {
  await loader.load();
  initializeMap();
});

const initializeMap = () => {
  map.value = new google.maps.Map(document.querySelector('.map'), {
    center: { lat: 12.5657, lng: 104.917 },
    zoom: 8
  });

  geocoder.value = new google.maps.Geocoder();
  placesService.value = new google.maps.places.PlacesService(map.value);
};

const getCurrentLocation = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const { latitude: lat, longitude: lng } = position.coords;
        const latLng = new google.maps.LatLng(lat, lng);
        latitude.value = lat
        longitude.value = lng

        if (map.value) {
          map.value.setCenter(latLng);
          map.value.setZoom(14);

          if (marker.value) {
            marker.value.setMap(null);
          }
          marker.value = new google.maps.Marker({
            position: latLng,
            map: map.value,
            title: 'Your current location',
            icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
          });

          const circle = new google.maps.Circle({
            strokeColor: '#2196F3',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#2196F3',
            fillOpacity: 0.35,
            map: map.value,
            center: latLng,
            radius: 1000
          });

          const bounds = new google.maps.LatLngBounds();
          bounds.extend(marker.value.getPosition());
          bounds.union(circle.getBounds());
          map.value.fitBounds(bounds);
          console.log(bounds);

        }
      },
      (error) => {
        console.error('Error getting location:', error);
      },
      {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
      }
    );
  } else {
    alert('Geolocation is not supported by this browser.');
  }
};

const searchSimilarPlaces = async () => {
  try {
    if (!reverseGeocodeResult.value.trim()) {
      similarPlaces.value = [];
      return;
    }

    const request = {
      query: reverseGeocodeResult.value,
      fields: ['formatted_address', 'name', 'place_id', 'geometry']
    };

    placesService.value.textSearch(request, (results, status) => {
      if (status === google.maps.places.PlacesServiceStatus.OK) {
        similarPlaces.value = results.map((place) => ({
          place_id: place.place_id,
          place_name: place.name,
          location: place.geometry.location
        }));
      } else {
        console.error('PlacesService failed with status:', status);
        similarPlaces.value = [];
      }
    });
  } catch (error) {
    console.error('Error searching similar places:', error);
    similarPlaces.value = [];
  }
};

const selectPlace = (place) => {
  reverseGeocodeResult.value = place.place_name;
  similarPlaces.value = [];
  const latLng = new google.maps.LatLng(place.location.lat(), place.location.lng());

  if (marker.value) {
    marker.value.setMap(null);
  }

  marker.value = new google.maps.Marker({
    position: latLng,
    map: map.value,
    title: place.place_name,
    icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
  });

  const circle = new google.maps.Circle({
    strokeColor: '#2196F3',
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: '#2196F3',
    fillOpacity: 0.35,
    map: map.value,
    center: latLng,
    radius: 1000
  });

  const bounds = new google.maps.LatLngBounds();
  bounds.extend(marker.value.getPosition());
  bounds.union(circle.getBounds());
  map.value.fitBounds(bounds);

  geocoder.value.geocode({ location: latLng }, (results, status) => {
    if (status === 'OK') {
      if (results[0]) {
        const addressComponents = results[0].address_components;
        const village = addressComponents.find(component =>
          component.types.includes('locality') || component.types.includes('sublocality')
        );

        if (village) {
          reverseGeocodeResult.value = place.place_name;
        } else {
          reverseGeocodeResult.value = results[0].formatted_address;
        }

        latitude.value = place.location.lat();
        longitude.value = place.location.lng();
      } else {
        console.error('No reverse geocode results found');
      }
    } else {
      console.error('Geocoder failed due to: ' + status);
    }
  });
};

const submitBooking = async () => {
  const userString = localStorage.getItem('user');
  if (!userString) {
    console.error('No user found in localStorage');
    return;
  }

  const user = JSON.parse(userString);
  const user_id = user.id;
  const today = new Date().toISOString().split('T')[0];
  const isImmediateBooking = bookingDate.value === today;

  const bookingData = {
    user_id: user_id,
    service_id: props.service.id,
    date: bookingDate.value,
    promotion_id: promotionCode.value || null,
    message: description.value || null,
    latitude: latitude.value,
    longitude: longitude.value
  };

  try {
    let response;
    if (isImmediateBooking) {
      response = await axios.post('http://127.0.0.1:8000/api/bookin_immediatly', bookingData);
    } else {
      response = await axios.post('http://127.0.0.1:8000/api/bookin_deadline', bookingData);
    }
    alertMessage.value = 'Booking successful!';
    alertType.value = 'success';
    setTimeout(() => {
      emit('close');
    }, 2000);
  } catch (error) {
    alertMessage.value = 'Booking failed. Please try again.';
    alertType.value = 'danger';
    console.error('Error submitting booking:', error);
  }

  setTimeout(() => {
    alertMessage.value = '';
  }, 2000);
};

const setTodayDate = () => {
  bookingDate.value = new Date().toISOString().split('T')[0];
};
</script>






<style scoped>
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
.map-search{
  padding: 11px 0;
}
.day{
  background: orange;
  color: white;
  border: none;
  border-radius: 0 5px 5px 0;
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
  border: 1px solid orange;
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
    border: 1px solid orange;

}

.input-group-append {
  margin-left: 10px;
  /* background: #000; */
  width: 18%;
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
  width: 100%;
  border-radius:0 5px 5px 0;
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
  width: calc(100% - 50%);
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