<template>
  <div>
    <section id="categories" class="d-flex align-items-center">
      <div class="category-list-container">
        <div class="search-input">
          <i class="bi bi-search"></i>
          <input
            type="text"
            placeholder="Search Service..."
            v-model="searchTerm"
            @input="filterCategories"
          />
        </div>
        <ul class="category-list d-flex">
          <li @click="filterServices(null)" :class="{ active: selectedCategory === null }">All</li>
          <li
            v-for="(category, index) in categories"
            :key="index"
            @click="filterServices(category.name)"
            :class="{ active: selectedCategory === category.name }"
            class="category-item"
          >
            {{ category.name }}
          </li>
        </ul>
      </div>
    </section>

    <section id="services">
      <div class="card-containers">
        <div v-if="filteredServices.length === 0" class="no-services">
          No services found.
        </div>
        <div v-for="(service, index) in filteredServices" :key="index" class="card">
          <div class="image">
            <img :src="service.image" class="card-img-top" :alt="service.name" />
          </div>
          <div class="card-body">
            <h5 class="card-title">{{ service.name }}</h5>
            <p class="card-text">{{ service.description }}</p>
            <p class="card-text">Price: {{ service.price }}</p>
            <button @click="openModal(service)" class="btn btn-primary">Get Service</button>
          </div>
        </div>
      </div>
    </section>
    <ModalForm v-if="showModal" :service="selectedService" @close="closeModal" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import ModalForm from '@/Components/BookingForm.vue';

const categories = ref([]);
const services = ref([]);
const selectedCategory = ref(null);
const searchTerm = ref('');
const showModal = ref(false);
const selectedService = ref(null);

onMounted(async () => {
  await fetchCategories();
  await fetchServices();
});

async function fetchCategories() {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/category/list');
    categories.value = response.data.category;
  } catch (error) {
    console.error('Error fetching categories:', error);
    throw error;
  }
}

async function fetchServices() {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/service/list');
    services.value = response.data.services;
  } catch (error) {
    console.error('Error fetching services:', error);
    throw error;
  }
}

const closeModal = () => {
  showModal.value = false;
  selectedService.value = null;
};

const openModal = (service) => {
  selectedService.value = service;
  showModal.value = true;
};

const filteredServices = computed(() => {
  let filtered = services.value;
  if (selectedCategory.value !== null) {
    filtered = filtered.filter((service) => {
      const category = categories.value.find(cat => cat.name === service.category);
      return category && category.name === selectedCategory.value;
    });
  }
  if (searchTerm.value.trim() !== '') {
    const regex = new RegExp(searchTerm.value.trim(), 'i');
    filtered = filtered.filter(
      (service) => regex.test(service.name) || regex.test(service.description)
    );
  }
  return filtered;
});

function filterServices(categoryName) {
  selectedCategory.value = categoryName;
}

function filterCategories() {
  selectedCategory.value = null;
}
</script>


<style scoped>
:root {
  --primary: orange;
}

body {
  font-family: Arial, sans-serif;
  background-color: #f8f9fa;
}

#categories {
  background-color: #fff;
  padding: 1.1rem;
  margin-bottom: 1rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  overflow-x: auto; /* Enable horizontal scrolling */
  -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
  scroll-behavior: smooth; /* Smooth scrolling behavior */
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: relative; /* Add relative positioning */
}

.category-list-container {
  flex: 1; /* Take up remaining space */
  overflow-x: auto; /* Enable horizontal scrolling */
  white-space: nowrap; /* Prevent wrapping */
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.category-list {
  list-style-type: none;
  padding: 0;
  display: flex;
  gap: 1rem;
  margin-left: 25%;
}

.image img {
  width: 100%;
  height: 200px; /* Fixed height */
  object-fit: cover; /* Cover the container, maintaining aspect ratio */
}

.category-list li {
  cursor: pointer;
  min-width: 200px;
  padding: 10px 15px;
  text-align: center;
  transition: background-color 0.3s ease;
  display: inline-block;
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 0 9px rgba(0, 0, 0, 0.1);
}

.search-input {
  position: absolute;
  display: flex;
  align-items: center;
  border: 1px solid orange;
  border-radius: 5px;
  padding: 2px 10px;
  top: 15px;
}

.search-input i {
  color: orange;
  font-size: 20px;
  margin-right: 10px;
}

.category-list li:hover {
  background-color: #f1f1f1;
}

.category-list .active {
  background-color: orange;
  color: #fff;
}

.search-input input {
  border: none;
  outline: none;
  padding: 10px;
  font-size: 14px;
}

/* Services Styles */
#services {
  padding: 2rem 0;
}

.card-containers {
  display: flex;
  flex-wrap: nowrap;
  gap: 20px;
  justify-content: flex-start;
  overflow-x: auto;
  padding-bottom: 20px;
  margin-bottom: -20px;
  -webkit-overflow-scrolling: touch;
  scroll-behavior: smooth;
}

.card {
  flex: 0 0 calc(33.33% - 20px);
  max-width: 300px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card img {
  width: 100%;
  height: 200px; /* Fixed height for images */
  object-fit: cover; /* Ensure images cover the container without distortion */
  border-top-left-radius: 8px;
  border-top-right-radius: 8px;
}

.card-body {
  padding: 1rem;
}

.card-title {
  color: #333;
  font-size: 1.2rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.card-text {
  color: #666;
  line-height: 1.5;
}

.btn-primary {
  background-color: orange;
  color: #fff;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  text-decoration: none;
  display: inline-block;
  transition: background-color 0.3s ease;
  cursor: pointer;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.btn-primary:hover {
  background-color: #ff7f50;
}

@media (max-width: 768px) {
  .card {
    flex: 0 0 calc(50% - 20px);
    max-width: calc(50% - 20px);
  }
}

@media (max-width: 576px) {
  .card {
    flex: 0 0 100%;
    max-width: 100%;
  }
}
</style>
