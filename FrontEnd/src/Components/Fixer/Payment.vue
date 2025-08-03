<template>
  <div class="table-responsive mt-3">
    <table class="table table-striped table-hover">
      <thead class="bg-warning text-white">
        <tr>
          <th scope="col" class="text-center">Number fixing</th>
          <th scope="col" class="text-center">Amount/1service</th>
          <th scope="col" class="text-center">Total</th>
          <th scope="col" class="text-center">Date</th>
          <th scope="col" class="text-center">Dateline</th>
          <th scope="col" class="text-center">Status</th>
        </tr>
      </thead>
      <tbody id="service-table-body">
        <tr v-for="pay in payments" :key="pay.id">
          <td class="text-center">{{ pay.number_fixed }}</td>
          <td class="text-center">{{ pay.amount }}$</td>
          <td class="text-center text-warning">{{ pay.total }}$</td>
          <td class="text-center">{{ pay.datepay }}</td>
          <td class="text-center">{{ pay.dateline }}</td>
          <div v-if="pay.status !== 'done'" :id="'incom' + pay.id">
          <td class="text-center d-flex gap-3 justify-content-center">
            <span class="status-incomplete text-4 p-1" >
              Incomplete<i class="bx bx-time-five"></i>
            </span>
            <button
              id="btn-pay" 
              class="btn btn-warning btn-sm d-flex align-items-center" 
              data-bs-toggle="modal" 
              data-bs-target="#paymentModel" 
              @click="openPaymentModal(pay.total,pay.id)">
              <i class="bi bi-credit-card me-2"></i>
              Pay Now
            </button>
          </td>
        </div>
          <td class='d-flex justify-content-center' v-else>  
            <span  class="status-succeeded text-4 p-0 w-40">
              Succeeded<i class="bx bx-check"></i>
          </span>
        </td>
          <td :id="'suc' + pay.id" hidden>  
            <span  class="status-succeeded text-4 p-0">
              Succeeded<i class="bx bx-check"></i>
          </span>
        </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Payment Form Modal -->
  <div class="modal fade" id="paymentModel" tabindex="-1" aria-labelledby="paymentModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="paymentModelLabel">Payment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-wrapper">
            <h3 class="text-center">App using Payment</h3>
            <p class="text-center mb-4">Total Amount: ${{ amount }}</p>
            <form @submit.prevent="submitPayment" id="payment-form">
              <div class="form-group">
                <input type="number" id='payment_id' v-model="payment_id" hidden>
                <label for="amount">Total Amount ($)</label>
                <input type="number" id="amount" v-model="amount" class="form-control" placeholder="Enter amount" required>
              </div>
              <div id="card-element" class="form-control card-element">
                <!-- Stripe Card Element will be inserted here -->
              </div>
              <button type="submit" class="btn btn-primary btn-block">
                <span v-if="loading" class="spinner"></span>
                <span v-else>Pay Now</span>
              </button>
              <div id="card-errors" role="alert" class="text-danger mt-3"></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</template>
<script>
import { loadStripe } from '@stripe/stripe-js';
import axios from 'axios';
export default {
  data() {
    return {
      stripe: null,
      elements: null,
      cardElement: null,
      amount: 0,
      payment_id: 0,
      loading: false,
      payments: [],
    };
  },
  async mounted() {
    await this.initializeStripe();
    await this.getPayments();
  },
  methods: {
    async initializeStripe() {
      this.stripe = await loadStripe('pk_test_51Pe7Zv2NtVBKOlb1FnqE3IU5MuaaM8ULidN7qGl63QWBwoqV8hkfg4nIzTiAoDA3gImPQLhuDoI1qX2vHlPl21wx00PFxTZrwy');
      this.elements = this.stripe.elements();
      this.cardElement = this.elements.create('card', {
        style: {
          base: {
            iconColor: '#FF6F00',
            color: '#2a2a2a',
            fontWeight: 500,
            fontFamily: 'Arial, sans-serif',
            fontSize: '16px',
            '::placeholder': {
              color: '#aab7c4',
            },
          },
        },
      });
      this.cardElement.mount('#card-element');
      this.cardElement.on('change', this.handleCardChange);
    },
    handleCardChange(event) {
      const displayError = document.getElementById('card-errors');
      displayError.textContent = event.error ? event.error.message : '';
    },
    async submitPayment() {
      this.loading = true;
      console.log(this.payment_id);
      try {
        const { data } = await axios.post('http://127.0.0.1:8000/api/stripe/payment', { amount: this.amount * 100 ,payment_id:this.payment_id});
        const { error, paymentIntent } = await this.stripe.confirmCardPayment(data.clientSecret, {
          payment_method: { card: this.cardElement },
        });

        if (error) {
          document.getElementById('card-errors').textContent = error.message;
        } else if (paymentIntent.status === 'succeeded') {
          alert('Payment successful!');
          this.closeModal();
          window.location.reload();
        }

      } catch (error) {
        console.error('Error processing payment:', error);
        document.getElementById('card-errors').textContent = 'Payment failed. Please try again.';
      } finally {
        this.loading = false;
      }
    },
    refreshPage() {
  setTimeout(() => {
    // Example: Reset values or fetch new data
    this.amount = 0;
    this.payment_id = '';
    // Optionally fetch updated data here
    this.fetchUpdatedData();
  }, 2000);
},
    closeModal() {
      document.querySelector('[data-bs-dismiss="modal"]').click();
    },
    openPaymentModal(total,payment_id) {
      this.amount = total;
      this.$nextTick(() => {
        document.getElementById('amount').value = total;
      });
      this.payment_id = payment_id;
      this.$nextTick(() => {
        document.getElementById('payment_id').value = payment_id;
      });
    },
    async getPayments() {
      const fixer = JSON.parse(localStorage.getItem('user'));
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/getlistpay/${fixer.id}`);
        this.payments = response.data;
      } catch (error) {
        console.error('Error fetching payment list:', error);
      }
    },
  },
};
</script>
<style scoped>
.table-responsive {
  margin: 0 auto;
}

.table {
  border-collapse: separate;
  border-spacing: 0;
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: #f9f9f9;
}

.table-hover tbody tr:hover {
  background-color: #eaeaea;
}

.bg-warning {
  background-color: #ffc107;
}

.text-white {
  color: #fff;
}

.btn-warning {
  background-color: #ffc107;
  border-color: #ffc107;
}

.btn-warning:hover {
  background-color: #e0a800;
  border-color: #e0a800;
}

.form-wrapper {
  max-width: 450px;
  margin: auto;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  background-color: #f9f9f9;
  border: 1px solid #ddd;
}

h3 {
  color: #333;
  font-size: 1.75rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  font-size: 0.875rem;
  font-weight: 600;
  color: #333;
}
input.form-control,
.card-element {
  border: 1px solid #ced4da;
  border-radius: 5px;
  padding: 0.75rem 1.25rem;
  font-size: 1rem;
  color: #495057;
  transition: border-color 0.3s ease;
}

input.form-control:focus,
.card-element:focus {
  border-color: #FF6F00;
  outline: none;
}

.card-element {
  padding: 0.75rem;
  margin-bottom: 1rem;
}

.btn-primary {
  background-color: #007bff;
  border-color: #007bff;
  color: #fff;
  padding: 0.75rem;
  border-radius: 5px;
  font-size: 1rem;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  transition: background-color 0.3s ease, border-color 0.3s ease;
  position: relative;
}

.btn-primary:hover {
  background-color: #0056b3;
  border-color: #004085;
}

.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  border-left-color: #FF6F00;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  position: absolute;
  top: 50%;
  left: 20px;
  transform: translateY(-50%);
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

#card-errors {
  font-size: 0.875rem;
  font-weight: 500;
  color: #dc3545;
}

#btn-pay {
  transition: background-color 0.3s, transform 0.3s;
}

#btn-pay:hover {
  background-color: #e0a800;
  transform: scale(1.1);
}

.status-incomplete {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
  border-radius: 5px;
  padding: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
}

.status-succeeded {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
  border-radius: 5px;
  padding: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
}
</style>