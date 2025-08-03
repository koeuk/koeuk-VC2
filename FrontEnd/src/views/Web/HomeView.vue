<template>
  <router-view>
    <router-view v-slot="{ Component }">
      <component :is="Component" />
    </router-view>
  </router-view>
  <WebLayout>
    <div class="container-fluid p-0">
      <div class="container-slider">
        <div class="slide" ref="slide">
          <div
            class="item"
            style="
              background-image: url(https://i.pinimg.com/564x/86/17/d3/8617d33d2e81105204f8a68b9b2866ec.jpg);
            "
          >
            <div class="content">
              <div class="name">Efficient Service</div>
              <div class="des">
                We value your time. Our streamlined processes and skilled technicians ensure prompt
                and efficient repairs, minimizing downtime for your vehicle.
              </div>
              <router-link :to="'/fixerForm'"><button>Register Now</button></router-link>
            </div>
          </div>
          <div
            class="item"
            style="
              background-image: url(https://i.pinimg.com/564x/31/65/46/316546a7fb5d1ce47e3f6146fb90a162.jpg);
            "
          >
            <div class="content">
              <div class="name">Skilled Technicians</div>
              <div class="des">
                Our team consists of highly trained and certified technicians who have extensive
                experience in repairing a wide range of vehicles, including cars, motorcycles, and
                more.
              </div>
              <!-- <button>See More</button> -->
            </div>
          </div>
          <div
            class="item"
            style="
              background-image: url(https://i.pinimg.com/564x/df/1d/1a/df1d1af1897cd846b3e5fa3e265913ab.jpg);
            "
          >
            <div class="content">
              <div class="name">Quality Parts</div>
              <div class="des">
                We use high-quality, genuine parts and materials in our repairs to ensure durability
                and performance.
              </div>
              <!-- <button>See More</button> -->
            </div>
          </div>
          <div
            class="item"
            style="
              background-image: url(https://i.pinimg.com/564x/65/45/1f/65451f8ab361bb8f42f5db02b0fcd20c.jpg);
            "
          >
            <div class="content">
              <div class="name">Specialized Knowledge</div>
              <div class="des">
                We stay updated with the latest advancements in automotive technology and repair
                techniques to ensure that we can diagnose and resolve issues effectively.
              </div>
              <!-- <button>See More</button> -->
            </div>
          </div>
          <div
            class="item"
            style="
              background-image: url(https://i.pinimg.com/736x/f3/ad/f5/f3adf501c6010aaab1aceb6377de20ea.jpg);
            "
          >
            <div class="content">
              <div class="name">Excellent Customer Service</div>
              <div class="des">
                Our friendly and knowledgeable staff are dedicated to ensuring your satisfaction. We
                prioritize clear communication and strive to exceed your expectations.
              </div>
              <!-- <button>See More</button> -->
            </div>
          </div>
          <div
            class="item"
            style="
              background-image: url(https://i.pinimg.com/564x/20/06/58/200658ead1212c34b994d8842b8538ee.jpg);
            "
          >
            <div class="content">
              <div class="name">Personalized Service</div>
              <div class="des">
                Each vehicle is treated with individual attention and care. We tailor our services
                to meet your specific needs and preferences.
              </div>
              <!-- <button>See More</button> -->
            </div>
          </div>
        </div>

        <div class="button">
          <button class="prev" ref="prevButton"><i class="bi bi-arrow-left"></i></button>
          <button class="next" ref="nextButton"><i class="bi bi-arrow-right"></i></button>
        </div>
      </div>

      <!-- Additional Content -->
      <div class="content">
        <button id="feedbackBtn" data-bs-toggle="modal" data-bs-target="#exampleModal">
<i class='bx bxs-message-rounded-dots bx-tada' style='color:#ffffff;font-size:30px'  ></i>
        </button>
        <div
          class="modal fade"
          id="exampleModal"
          tabindex="-1"
          aria-labelledby="exampleModalLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Feedback</h1>
              </div>
              <div class="modal-body">
                <textarea
                  rows="4"
                  cols="60"
                  name="comment"
                  form="usrform"
                  v-model="content"
                  placeholder="Enter text here ..."
                ></textarea>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  Close
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  style="background: orange"
                  @click="feedback"
                  data-bs-dismiss="modal"
                >
                  Send
                </button>
              </div>
            </div>
          </div>
        </div>
        <WebService />
        <div class="row mt-5 mapProvince">
          <ProvinceMap />
        </div>
        <div class="row mt-5 mb-5">
          <AboutPage />
        </div>
        <div class="row mt-5 mapProvince">
          <FooterPage />
        </div>
      </div>
    </div>
  </WebLayout>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue'
import WebLayout from '@/Components/Layouts/WebLayout.vue'
import ProvinceMap from '@/Components/ProvinceMap.vue'
import AboutPage from '@/Components/AboutPage.vue'
import FooterPage from '@/Components/FooterPage.vue'
import WebService from '@/Components/WebServiceCustomer.vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { json } from 'stream/consumers'

const user_id = ref(null)
const content = ref(null)
const router = useRouter()
const slide = ref(null)
const nextButton = ref(null)
const prevButton = ref(null)
const moveSlide = (direction: string) => {
  const items = slide.value?.querySelectorAll('.item')
  if (items && items.length > 0) {
    if (direction === 'next') {
      slide.value?.appendChild(items[0])
    } else if (direction === 'prev') {
      slide.value?.prepend(items[items.length - 1])
    }
  }
}
const feedback = async () => {
  try {
    const user = JSON.parse(localStorage.getItem('user')).id
    console.log(user)
    console.log(content.value)

    const response = await axios.post("http://127.0.0.1:8000/api/feedback/create", {
      user_id: user,
      content: content.value,
    });
    console.log(response.data);
    
    // const { user, access_token } = response.data;
  } catch (e) {
    console.error('Error feedback:')
  }
}

onMounted(() => {
  if (nextButton.value && prevButton.value) {
    nextButton.value.addEventListener('click', () => moveSlide('next'))
    prevButton.value.addEventListener('click', () => moveSlide('prev'))
  }
})

onBeforeUnmount(() => {
  if (nextButton.value && prevButton.value) {
    nextButton.value.removeEventListener('click', () => moveSlide('next'))
    prevButton.value.removeEventListener('click', () => moveSlide('prev'))
  }
})
</script>

<style scoped>
.carousel.slide {
  width: 100%;
}

.carousel-item img {
  height: 90vh; /* Adjust the height of the carousel */
  object-fit: cover;
}

.carousel-caption {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, 15%);
  text-align: center;
  color: #fff;
  width: 100%;
  z-index: 1;
  background: rgba(
    11,
    11,
    11,
    0.142
  ); 

}

.carousel-title {
  font-size: 3rem;
  font-weight: bold;
  margin-bottom: 10px;
}

.carousel-subtitle {
  font-size: 1.5rem;
}
/* Apply styles to the button */
#feedbackBtn {
  position: fixed; /* Fixed position keeps the button in place relative to the viewport */
  bottom: 50px; /* Adjust as needed to position the button */
  right: 20px; /* Adjust as needed to position the button */
  padding: 10px 20px;
  background-color: orange;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  z-index: 1000; /* Ensure it's above other content */
}

/* Optional: Style on hover */
#feedbackBtn:hover {
  background-color: rgba(255, 166, 0, 0.637);
}
.custom-prev,
.custom-next {
  background-color: orange;
  border: none;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 2; /* Ensure buttons are above blurred background */
}

.custom-prev {
  left: 10px;
  background: #000;
}

.custom-next {
  right: 10px;
}

.custom-prev:hover,
.custom-next:hover {
  background-color: darkorange;
}

.content {
  padding-top: 20px;
}

/* ---------------------------------------------------------- */

.container-slider {
  height: 500px;
  background: #f5f5f5;
  box-shadow: 0 30px 50px #dbdbdb;
}

.container-slider .slide .item {
  width: 150px;
  height: 200px;
  position: absolute;
  top: 50%;
  transform: translate(0, -50%);
  border-radius: 10px;
  box-shadow: 0 30px 50px #505050;
  background-position: 50% 50%;
  background-size: cover;
  display: inline-block;
  transition: 0.5s;
}

.slide .item:nth-child(1),
.slide .item:nth-child(2) {
  top: 0;
  left: 0;
  transform: translate(0, 0);
  border-radius: 0;
  width: 100%;
  height: 100%;
}

.slide .item:nth-child(3) {
  left: 60%;
}

.slide .item:nth-child(4) {
  left: calc(60% + 170px);
}

.slide .item:nth-child(5) {
  left: calc(60% + 340px);
}

/* here n = 0, 1, 2, 3,... */
.slide .item:nth-child(n + 6) {
  left: calc(50% + 100px);
  opacity: 0;
}

.item .content {
  position: absolute;
  top: 50%;
  left: 100px;
  width: 300px;
  text-align: left;
  color: #eee;
  transform: translate(0, -50%);
  font-family: system-ui;
  display: none;
}

.slide .item:nth-child(2) .content {
  display: block;
}

.content .name {
  font-size: 40px;
  text-transform: uppercase;
  font-weight: bold;
  opacity: 0;
  animation: animate 1s ease-in-out 1 forwards;
}

.content .des {
  margin-top: 10px;
  margin-bottom: 20px;
  opacity: 0;
  animation: animate 1s ease-in-out 0.3s 1 forwards;
}

.content button {
  padding: 10px 20px;
  border: none;
  cursor: pointer;
  opacity: 0;
  animation: animate 1s ease-in-out 0.6s 1 forwards;
  background: orange;
  color: white;
  
}
.content button:hover{
  background: #f9a825;
  border-radius:5px;
  transition:5ms;
  box-shadow: #000;
}

@keyframes animate {
  from {
    opacity: 0;
    transform: translate(0, 100px);
    filter: blur(33px);
  }

  to {
    opacity: 1;
    transform: translate(0);
    filter: blur(0);
  }
}

.button {
  width: 90%;
  text-align: center;
  position: absolute;
  bottom: 80px;
}

.button button {
  width: 30px;
  height: 25px;
  border-radius: 5px;
  border: none;
  cursor: pointer;
  margin: 0 10px;
  border: 1px solid #000;
  transition: 0.3s;
}

.button button:hover {
  background: #ababab;
  color: #fff;
}
</style>