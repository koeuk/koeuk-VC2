<template>
  <div class="container">
    <!-- Section for Accepted Fixers -->
    <div class="mb-5">
      <h1 class="text-center mb-4">YOUR ACCEPTED FIXERS</h1>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th scope="col">Profile Image</th>
              <th scope="col">Booking Type</th>
              <th scope="col">Service</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="fixer in acceptedFixers" :key="fixer.id">
              <td>
                <img
                  :src="fixer.profile_image_url ? fixer.profile_image_url : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png'"
                  class="rounded-circle profile-image"
                  alt="Profile"
                />
              </td>
              <td>Immedaitely</td>
              <td>Mouse</td>
              <td>
                <button class="btn btn-cancel" data-bs-toggle="modal" data-bs-target="#exampleModal" @click="setFixerId(fixer.id, fixer.booking_id)">
                  <i class="bi bi-x-circle text-warning me-1"></i> Cancel
                </button>
                <button class="btn btn-primary ms-2" @click="startFixer(fixer.id, fixer.booking_id)">
                  <i class="bi bi-play-circle text-primary me-1"></i> Start
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal for Cancellation Confirmation -->
    <div class="modal fade" id="exampleModal" tabindex="-1" data-bs-dismiss="modal" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Confirm Cancellation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to cancel this accepted booking?</p>
            <div class="d-flex align-items-center">
              <i class="bi bi-info-circle-fill text-info me-1"></i>
              <span class="text-muted">Cancellation is irreversible once confirmed.</span>
            </div>
          </div>
          <!-- Hidden Inputs for Fixer and Booking IDs -->
          <input type="hidden" v-model="fixer_id">
          <input type="hidden" v-model="booking_id">
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="bi bi-x-circle-fill text-danger me-1"></i> Cancel
            </button>
            <button type="button" class="btn btn-primary" @click="cancelFixerAction">
              <i class="bi bi-check-circle-fill text-success me-1"></i> Confirm
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const accessToken = localStorage.getItem('access_token')
const acceptedFixers = ref([])
let fixerId = JSON.parse(localStorage.getItem('user')).id
let fixer_id = ref('')
let booking_id = ref('')
let fixing_progress_id = ref('')

async function getAcceptedFixers() {
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/fixer/accepted/${fixerId}`, {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        'Content-Type': 'application/json'
      }
    })
    acceptedFixers.value = response.data.accepted_bookings
  } catch (error) {
    console.error('Error fetching accepted fixers:', error)
  }
}

function setFixerId(id, bookingId) {
  fixer_id.value = id
  booking_id.value = bookingId
}

async function cancelFixerAction() {
  try {
    const response = await axios.delete(`http://127.0.0.1:8000/api/fixer/cancel/${fixer_id.value}`, {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        'Content-Type': 'application/json'
      },
      data: {
        booking_id: booking_id.value
      }
    })
    console.log('Cancellation response:', response.data)
    await getAcceptedFixers()
  } catch (error) {
    console.error('Error cancelling fixer:', error)
  }
}

async function startFixer(fixing_progress_id, booking_progres_id) {
  console.log(fixing_progress_id,booking_progres_id);
  try {
    const response = await axios.put(`http://127.0.0.1:8000/api/fixer/start/${fixing_progress_id}`, {
      booking_id: booking_progres_id,
      fixer_id: fixerId
    }, {
      headers: {
        Authorization: `Bearer ${accessToken}`,
        'Content-Type': 'application/json'
      }
    })

    localStorage.setItem('latitude', response.data.latitude)
    localStorage.setItem('id', response.data.fixingProgress.id)
    localStorage.setItem('longitude', response.data.longitude)
    router.push('/map')
  } catch (error) {
    console.error('Error starting fixer:', error)
  }
}

onMounted(async () => {
  await getAcceptedFixers()
})
</script>

<style scoped>
/* Scoped styles for the table */
.table {
  width: 100%;
  margin-bottom: 1rem;
  background-color: #fff;
  border: 1px solid #dee2e6;
  border-collapse: collapse;
}
button{
  background: orange;
}
button:hover{
  background: #FFA500;
}

.table th,
.table td {
  padding: 0.75rem;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}

.table th {
  background-color: #f8f9fa;
  color: #212529;
  border-color: #dee2e6;
  font-weight: bold;
  text-align: center;
}

.table tbody tr:hover {
  background-color: rgba(0, 0, 0, 0.075);
}

/* Custom styles for profile images */
.profile-image {
  width: 50px;
  height: 50px;
  border-radius: 50%;
}
</style>
