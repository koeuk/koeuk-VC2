<template>
  <div class="container">
    <div class="row province mt-5">
      <div class="col d-flex flex-column justify-content-between">
        <h1 class="text-center display-6 text mb-5">PROVINCES</h1>
       <div id="map-container">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d6384840.041511697!2d104.9910163!3d12.5656794!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sus!4v1718962529381!5m2!1sen!2sus"
            width="1500"
            height="650"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
        ></iframe>
    </div>
      </div>
    </div>
  </div>
</template>
<style scoped>

.text {
  color: orange;
  font-weight: bolder;
}
#map-container {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        iframe {
            border: 0;
        }
</style>