<template>
  <div id="main-motorcycles">
    <div class="card mb-3" style="max-width: 840px" id="moto-brand">
      <div class="row g-0">
        <div class="col-md-4" id="moto-image">
          <img src="https://static.vecteezy.com/system/resources/previews/020/190/461/original/honda-logo-honda-icon-free-free-vector.jpg" class="img-fluid rounded-start" alt="..." />
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title">HONDA</h5>
            <p class="card-text">
             Honda motorcycles are extremely popular in Cambodia due to their reliability, fuel efficiency.
            </p>
            <button id="Booking-btn">Booking Now!</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card mb-3"  style="max-width: 840px" id="moto-brand">
      <div class="row g-0">
        <div class="col-md-4">
          <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSrKmRYGuRZ72lgHn3Lg2mQ_N7uBF7VdhABlw&s" width="100%" class="img-fluid rounded-start" alt="..." />
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title">YAMAHA</h5>
            <p class="card-text">
             Yamaha is another major player in the Cambodian motorcycle market. Models like the Yamaha Fino, Yamaha Nouvo.
            </p>
            <button id="Booking-btn">Booking Now!</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card mb-3"  style="max-width: 840px" id="moto-brand">
      <div class="row g-0">
        <div class="col-md-4">
          <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRC0paXp3aS0OwRbrleuUaHnUgidu89zxs0uw&s" width="100%" class="img-fluid rounded-start" alt="..." />
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title">SUZUKI</h5>
            <p class="card-text">
              Suzuki motorcycles have a significant presence in Cambodia as well. Models like the Suzuki Smash.
            </p>
            <button id="Booking-btn">Booking Now!</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card mb-3"  style="max-width: 840px" id="moto-brand">
      <div class="row g-0">
        <div class="col-md-4">
          <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQXIiIGezgwI2uT6PPv7iU1Gt8c_3pHWgyFKA&s" width="100%" class="img-fluid rounded-start" alt="..." />
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title">KAWASAKI</h5>
            <p class="card-text">
              While less common compared to Honda and Yamaha, Kawasaki motorcycles are still well-regarded in Cambodia.
            </p>
            <button id="Booking-btn">Booking Now!</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card mb-3"  style="max-width: 840px" id="moto-brand">
      <div class="row g-0">
        <div class="col-md-4">
          <img src="https://ae01.alicdn.com/kf/Sf17aaef2ae92408894dbd13b16d71c5cY.jpg_640x640Q90.jpg_.webp" width="100%" class="img-fluid rounded-start" alt="..." />
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title">SYM</h5>
            <p class="card-text">
              SYM, a Taiwanese brand, has gained popularity in Cambodia with models like the SYM Attila.
            </p>
            <button id="Booking-btn">Booking Now!</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card mb-3"  style="max-width: 840px" id="moto-brand">
      <div class="row g-0">
        <div class="col-md-4">
          <img src="https://i.pinimg.com/736x/63/35/fc/6335fca6635d1cedd3246ec57cd09785.jpg" width="100%" class="img-fluid rounded-start" alt="..." />
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title">DUCATI</h5>
            <p class="card-text">
              Ducati is a wider card with supporting text below as a natural lead-in to additional
              content. This content is a little bit longer.
            </p>
            <button id="Booking-btn">Booking Now!</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {}
</script>

<style>
#main-motorcycles {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2%;
    margin-bottom: 4%;
    margin-top: 2%;
    margin: 2%;
}
/* #moto-brand {
    width: 100%;
    margin-left: 12%;
} */

/* #moto-image img {
    width: 100%;
    height: 40vh;
} */
 #Booking-btn {
    background-color: orange;
    border: none;
    width: 30%;
    height: 5vh;
    margin-top: 17%;
    margin-left: 68%;
    font-weight: bold;
    border-radius: 50vh;
 }
</style>