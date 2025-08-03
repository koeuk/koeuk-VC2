<script setup lang="ts">
import { Icon } from '@iconify/vue'
</script>

<template>
  <div>
    <div class="about-us-container mt-5 mb-5">
      <div class="left-side">
        <div class="circle-image-container">
          <div class="circle-image"></div>
          <div class="text-overlay">
            <p class="display-6 text" style="color:orange">Fast Service For You</p>
          </div>
        </div>
      </div>
      <div class="right-side">
        <div class="about">
          <h1 class="title display-6 text">ABOUT US</h1>
          <p style="color:white">
            Our Commitment: We are dedicated to providing fast and reliable service solutions to
            meet your needs. Our team consists of highly skilled technicians who are committed to
            delivering the best repair solutions.
          </p>
        </div>
        <img
          src="../assets/images/image.png"
          alt="Description of the right image"
          style="height: 100%; widht: 300px"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>
.about-us-container {
  display: flex;
  height: 80vh;
}

.left-side {
  position: relative;
  width: 40%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.circle-image-container {
  position: relative;
  width: 83%;
  height: 78%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.circle-image {
  position: absolute;
  width: 120%;
  height: 120%;
  background-image: url('../assets/images/about.png');
  background-size: cover;
  background-position: center;
  animation: circular-motion 40s linear infinite;
}
.about {
  position: absolute;
  width: 40%;
  height: 30%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: black;
  background-color: rgba(0, 0, 0, 0.4);
  border: 2px solid #f59300;
  text-align: end;
  padding: 10px 50px;
  text-align: start;
}

@keyframes circular-motion {
  0% {
    transform: rotate(0deg);
  }
  25% {
    transform: rotate(90deg);
  }
  50% {
    transform: rotate(180deg);
  }
  75% {
    transform: rotate(270deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.text-overlay {
  position: absolute;
  width: 58%;
  height: 60%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: black;
  background-color: white;
  border: 3px solid #f59300;
  border-radius: 50%;
  text-align: center;
  padding: 0 20px;
}

.right-side {
  width: 60%;
  display: flex;
  align-items: center;
  justify-content: end;
}

.service {
  height: 80vh;
}

.title {
  color: orange;
  font-weight: bolder;
}
</style>
