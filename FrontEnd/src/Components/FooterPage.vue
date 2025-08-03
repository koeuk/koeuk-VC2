<script setup lang="ts">
import { Icon } from '@iconify/vue'
import facebookIcon from '@iconify-icons/bi/facebook'
import googleIcon from '@iconify-icons/bi/google'
import linkedinIcon from '@iconify-icons/bi/linkedin'

</script>

<template>
  <div class="footer-container">
    <p class="cta-text">READY TO START WITH THE BEST SERVICES FOR YOU?</p>
    <footer class="footer">
      <div class="container">
        <div class="footer-top">
          <div class="footer-column social">
            <h3>Social</h3>
            <a href="#">
              <Icon :icon="facebookIcon" class="social-icon" />
              <span>Facebook</span>
            </a>
            <a href="#">
              <Icon :icon="googleIcon" class="social-icon" />
              <span>Google</span>
            </a>
            <a href="#">
              <Icon :icon="linkedinIcon" class="social-icon" />
              <span>LinkedIn</span>
            </a>
            <div class="footer-bottom">
              <div class="logo">
                <img src="../assets/img/image.png" alt="QuickFix Logo" style="height: 100px;"/>
              </div>
            </div>
          </div>
          <div class="footer-column services">
            <h3>Services</h3>
            <p>Computer Repair</p>
            <p>Vehicle Repair</p>
            <p>Telephone Repair</p>
            <p>Television </p>
            <p>Equipment Repair</p>
          </div>
          <div class="footer-column contact">
            <h3>Contact Us</h3>
            <form @submit.prevent="submitForm">
              <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required />
              </div>
              <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="4" required></textarea>
              </div>
              <button type="submit">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.footer-container {
  position: relative;
}

.cta-text {
  position: absolute;
  top: -6%;
  left: 50%;
  transform: translateX(-50%);
  width: 80%;
  text-align: center;
  font-size: 1.5rem;
  color: white;
  font-weight: bold;
  margin: 0;
  padding: 20px;
  background-color: #f59300;
}

.footer {
  background-color: #333;
  color: #fff;
  padding: 40px 20px;
  font-family: Arial, sans-serif;
  overflow: hidden;
}

.footer .container {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.footer-top {
  display: flex;
  justify-content: space-between;
  width: 100%;
  max-width: 1200px;
  margin-top: 40px;
}

.footer-column {
  flex: 1;
  margin: 10px;
}

.footer-column h3 {
  margin-bottom: 15px;
  font-size: 2rem;
  color: #f59300;
}

.footer-column p {
  margin: 25px 0;
  font-size: 1rem;
  color: #ddd;
}

.social a {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
  color: #ddd;
  text-decoration: none;
  margin: 30px 0;
}

.social-icon {
  font-size: 1.5rem;
  margin-right: 10px;
  transition: color 0.3s;
}

.social-icon:hover {
  color: #f59300;
}

.contact form {
  display: flex;
  flex-direction: column;
}

.contact .form-group {
  margin-bottom: 15px;
}

.contact .form-group label {
  display: block;
  margin-bottom: 5px;
  color: #ddd;
}

.contact .form-group input,
.contact .form-group textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background-color: #444;
  color: #fff;
}

.contact button {
  padding: 10px 15px;
  border: none;
  border-radius: 4px;
  background-color: #f59300;
  color: #fff;
  cursor: pointer;
  transition: background-color 0.3s;
}

.contact button:hover {
  background-color: #e07d00;
}

.logo {
  position: relative;
  text-align: center;
}

.logo img {
  width: 150px;
  height: 90px;
  /* border-radius: 50%; */
}

.logo p {
  color: black;
  font-size: 2rem;
  text-align: center;
  position: absolute;
  bottom: -8px;
  left: 140%;
  transform: translateX(-50%);
  font-weight: bolder;
}

.footer-bottom {
  display: flex;
  width: 270px;
  background: white;
  padding: 1px 10px;
  margin-top: 50px;
  border-radius: 4px;
}

@media (max-width: 768px) {
  .footer-top {
    flex-direction: column;
    align-items: center;
  }

  .footer-column {
    margin: 10px 0;
  }

  .cta-text {
    font-size: 1.2rem;
  }
}
</style>
