<template>
  <div class="custom-container">
    <div class="sidebar">
      <div class="sidebar-header">
        <i class="bi bi-chevron-left"></i>
        <router-link to="/HomeFixer" class="back">Back</router-link>
      </div>
      <div class="sidebar-image">
        <h2>Information</h2>
      </div>
      <div class="info-container">
        <div class="info">
          <p>Duration</p>
          <p>Distance</p>
        </div>
        <div class="info-details">
          <p>{{ duration }} mn</p>
          <p>Fixing...</p>
        </div>
      </div>
      <div class="icon">
  <div class="center d-flex justify-content-center align-items-center" @click="doneFixing">
    Done
  </div>
</div>

    </div>
    <div ref="map" class="map-container"></div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'DeliveryMap',
  data() {
    return {
      map: null,
      destination: null,
      distance: 0,
      duration: 40,
      userLocation: null,
      userMarker: null,
      deliveryMarker: null,
      directionsService: null,
      directionsRenderer: null,
      phone: null,
      bookingLocationWatchId: null,
      bookingLocation: null,
      routePolyline: null 
    };
  },
  mounted() {
    const googleMapsScript = document.createElement('script');
    googleMapsScript.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyAEkPs2AFjaazwiQaO25lkaHp-nlX00sK0&libraries=places`;
    googleMapsScript.async = true;
    googleMapsScript.defer = true;
    googleMapsScript.onload = () => {
      this.initMap();
    };
    document.head.appendChild(googleMapsScript);

    const user = JSON.parse(localStorage.getItem('user'));
    if (user && user.phone) {
      this.phone = user.phone;
    }
  },
  methods: {
    doneFixing() {
      const id = localStorage.getItem('id');
      try {
        const response = axios.put(`http://127.0.0.1:8000/api/fixer/done/${id}`);
        localStorage.removeItem('id');
        localStorage.removeItem('latitude');
        localStorage.removeItem('longitude');
        this.$router.push('/HomeFixer');
      } catch {
        console.log('error');
      }
    },
    initMap() {
      this.map = new google.maps.Map(this.$refs.map, {
        center: { lat: 12.5657, lng: 105 },
        zoom: 17
      });

      this.directionsService = new google.maps.DirectionsService();
      this.directionsRenderer = new google.maps.DirectionsRenderer({
        map: this.map,
        suppressMarkers: true,
        polylineOptions: {
          strokeColor: '#1a73e8', 
          strokeOpacity: 0.8,
          strokeWeight: 5
        }
      });

      this.getCurrentLocation();
      this.trackBookingLocation();
    },
    getCurrentLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          position => {
            const { latitude, longitude } = position.coords;
            this.userLocation = { lat: latitude, lng: longitude };
            this.updateMap();
          },
          error => {
            console.error('Error getting current location:', error);
          }
        );
      } else {
        alert('Geolocation is not supported by this browser.');
      }
    },
    trackBookingLocation() {
      this.getCustomerLocation();
      this.bookingLocationWatchId = setInterval(() => {
        this.getCustomerLocation();
      }, 5000); // Update every 5 seconds (adjust as needed)
    },
    getCustomerLocation() {
      // Retrieve booking location from localStorage
      const customerLongitude = parseFloat(localStorage.getItem('longitude'));
      const customerLatitude = parseFloat(localStorage.getItem('latitude'));

      if (customerLongitude && customerLatitude) {
        this.destination = { lat: customerLatitude, lng: customerLongitude };
        this.bookingLocation = { lat: customerLatitude, lng: customerLongitude };
        this.addDeliveryMarker();
        this.calculateRoute();
      } else {
        console.error('Customer location not found in localStorage');
      }
    },
    updateMap() {
      if (!this.userLocation) return;

      this.map.setCenter(this.userLocation);
      this.map.setZoom(13);

      if (!this.userMarker) {
        this.userMarker = new google.maps.Marker({
          position: this.userLocation,
          map: this.map,
          icon: {
            path: google.maps.SymbolPath.CIRCLE,
            fillColor: '#FF5733',
            fillOpacity: 1,
            strokeWeight: 0,
            scale: 10
          }
        });
      } else {
        this.userMarker.setPosition(this.userLocation);
      }

      this.calculateRoute();
    },
    addDeliveryMarker() {
      // Add or update delivery marker position
      if (this.destination && !this.deliveryMarker) {
        this.deliveryMarker = new google.maps.Marker({
          position: this.destination,
          map: this.map,
          icon: {
            path: google.maps.SymbolPath.CIRCLE,
            fillColor: '#3887be',
            fillOpacity: 1,
            strokeWeight: 0,
            scale: 10
          }
        });
      } else {
        this.deliveryMarker.setPosition(this.destination);
      }
    },
    calculateRoute() {
      // Calculate route between user location and destination
      if (!this.userLocation || !this.destination) return;

      const request = {
        origin: this.userLocation,
        destination: this.destination,
        travelMode: 'DRIVING'
      };

      this.directionsService.route(request, (result, status) => {
        if (status === 'OK') {
          this.directionsRenderer.setDirections(result);
          const route = result.routes[0];
          this.distance = (route.legs[0].distance.value / 1000).toFixed(1);
          this.duration = Math.floor(route.legs[0].duration.value / 60);

          // Draw polyline for the route
          if (this.routePolyline) {
            this.routePolyline.setMap(null); // Clear previous polyline
          }
          this.routePolyline = new google.maps.Polyline({
            path: route.overview_path,
            strokeColor: '#1a73e8', // Blue color for polyline
            strokeOpacity: 0.8,
            strokeWeight: 5
          });
          this.routePolyline.setMap(this.map);
        } else {
          console.error('Directions request failed due to ' + status);
        }
      });
    }
  },
  beforeDestroy() {
    // Cleanup before component destruction
    if (navigator.geolocation && this.locationWatchId) {
      navigator.geolocation.clearWatch(this.locationWatchId);
    }
    if (this.bookingLocationWatchId) {
      clearInterval(this.bookingLocationWatchId);
    }
  }
};
</script>

<style scoped>
.custom-container {
  display: flex;
  height: 100vh;
  width: 100%;
  overflow: hidden;
}

.sidebar {
  width: 30%;
  background: #f7f7f7;
  padding: 20px;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
}

.sidebar-header {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #fff;
  margin-bottom: 20px;
  border: 2px solid orange;
  border-radius: 10px;
  padding: 5px;
  background: orange;
  transition: background 0.3s, border-color 0.3s;
  cursor: pointer;
  width: 100px;
}

.sidebar-header:hover {
  background: #ff9f1c;
  border-color: #ff9f1c;
}

.sidebar-header i {
  font-size: 1.5rem;
}

.back {
  color: white;
  text-decoration: none;
}

.sidebar-image {
  margin-bottom: 20px;
}

.info-container {
  display: flex;
  justify-content: space-between;
  gap: 10px;
}

.info,
.info-details {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.info p,
.info-details p {
  margin: 0;
}

.icon {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px;
  border-radius: 10px;
  background-color: #fff;
  width: 100%;
  margin-top: auto;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.icon > div {
  color: white;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100px;
  height: 50px;
  margin: auto;
  background-color: #f57c00;
  border-radius: 1px;
  transition: background-color 0.3s ease, transform 0.3s ease;
  cursor: pointer;
}

.icon > div:hover {
  background-color: #ff9800;
  transform: scale(1.1);
}

.icon .bi {
  font-size: 1.5rem;
  color: #fff;
}

.map-container {
  width: 70%;
  height: 100%;
}
</style>
