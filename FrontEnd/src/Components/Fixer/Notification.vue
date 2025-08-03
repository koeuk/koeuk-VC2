<template>
  <div class="notification-container">
    <template v-if="notifications.length > 0">
      <div class="notification-card" v-for="notification in notifications" :key="notification.id">
        <div class="notification-content ">
          <h3>{{ notification.title }}</h3>
          <h4><span style="color:orange;font-weight:bold">    {{notification.user_id.name}}   </span>:     Booked you to fix service</h4>
<!-- <p class="mt-4 ml-5">{{ new Date(notification.booking_id.created_at).toLocaleDateString() }}</p> -->
        </div>
        <div class="notification-actions">
          <button class="btn btn-success" @click="approveBooking(notification)">
            Approve
          </button>
          <button class="btn btn-danger" @click="rejectBooking(notification)">
            Reject
          </button>
        </div>
      </div>
    </template>
    <template v-else>
      <p class="no-notifications">No new notifications</p>
    </template>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'Notification',
  setup() {
    const notifications = ref([]);

    // Method to fetch notifications from the API
    const fetchNotifications = async () => {
      try {
        const fixer = JSON.parse(localStorage.getItem('user'));
        const response = await axios.get(`http://127.0.0.1:8000/api/notification/show/${fixer.id}`);
        notifications.value = response.data.data; // Assuming the API returns an array of notifications under 'data'
      } catch (error) {
        console.error('Error fetching notifications:', error);
      }
    };

    const approveBooking = async (notification) => {
      console.log('Approved booking with ID:', notification.id);
      try {
        const response = axios.put(`http://127.0.0.1:8000/api/notification/update/${notification.id}`)
        notifications.value = notifications.value.filter(n => n.id !== notification.id);
      } catch (error) {
          console.error('Error approving booking:', error);
      }
    };

    const rejectBooking = async (notification) => {
        console.log('Rejected booking with ID:', notification.id);
      try {
          const response = axios.delete(`http://127.0.0.1:8000/api/notification/delete/${notification.id}`)
       
        notifications.value = notifications.value.filter(n => n.id !== notification.id);
      } catch (error) {
        console.error('Error rejecting booking:', error);
      }
    };

    onMounted(async () => {
      await fetchNotifications();
      
    // setInterval(async () => {
    //      await fetchNotifications();
    //    }, 1500);
    });

    return {
      notifications,
      approveBooking,
      rejectBooking,
    };``
  },
};
</script>

<style scoped>
.notification-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px; /* Space between notification cards */
}

.notification-card {
  width: 100%;
  background-color: #ffffff;
  border: 1px solid #cccccc;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.notification-content {
  flex: 1; /* Take remaining space in the card */
}

.notification-actions {
  margin-left: 20px; /* Space between content and actions */
  display: flex;
  gap: 10px; /* Space between action buttons */
}

.no-notifications {
  color: #999999;
  font-style: italic;
}
</style>
