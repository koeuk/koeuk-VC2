<template>
  <div>
    <!-- <Navbar /> -->
    <div class="container">
      <div class="mb-3">
        <div class="card border-0">
          <div class="card-body">
            <h5 class="card-title d-flex justify-content-end w-100 gap-3 mb-3">
              <!-- <button class="btn ">All</button> -->
              <button class="btn ">
                <input type="date" name="" id="date" class="form-control" value="">
              </button>
            </h5>
            <ul class="list-group gap-3 w-auto">
              <li v-for="(item, index) in items" :key="index" class="list-group-item rounded-2 border-1">
                <div class="pro">
                  <div class="profile d-flex gap-3">
                    <img src="/src/assets/img/cat.jpeg" alt="" class="rounded-25" />
                    <h5>Repair: <b>{{ item.id }}</b></h5>
                    <h5 id="result">Finished</h5>
                  </div>
                  <p>Date : {{ item.date }}</p>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import Navbar from "../Fixer/Navbar.vue";

export default {
  name: "HistoryView",
  // components: {
  //   // Navbar,
  // },
  data() {
    return {
      items: [
        { id: 1, date: "12, May 2024 12:23 PM" },
        { id: 2, date: "13, May 2024 01:30 PM" },
        { id: 3, date: "14, May 2024 02:45 PM" },
        { id: 4, date: "15, May 2024 03:15 PM" },
        { id: 5, date: "16, May 2024 04:00 PM" },
        { id: 6, date: "17, May 2024 05:20 PM" },
        { id: 7, date: "18, May 2024 06:10 PM" }
      ]
    };
  },
};
</script>

<style>
img {
  width: 15%;
  height: 100%;
  object-fit: cover;
}

.profile {
  display: flex;
  align-items: center;
  width: auto;
}

.pro {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: 10px;
  align-items: center;
}

.pro p {
  margin: 0;
  color: rgb(0, 0, 0);
  font-size: 16px;
  text-align: right;
  padding: 8px;
  border-radius: 5px;
}

@media (max-width: 992px) {
  .pro p {
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: end;
    width: auto;
  }
  .profile {
    width: 300px;
    gap: 2px;
  }
}

@media (max-width: 768px) {
  .profile {
    width: auto;
  }
  .pro p {
    font-size: 12px;
    width: 150px;
  }
}

@media (max-width: 512px) {
  .card {
    padding: 0;
  }
  .card-body {
    margin: 0;
    width: auto;
  }
  .list-group {
    width: 100%;
  }
  li {
    width: 340px;
    height: auto;
  }
  .profile {
    width: auto;
    flex-direction: column;
    align-content: center;
    align-items: center;
    align-items: flex-start;
    /* width: 100%; */
  }
  .pro {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    gap: 0;
  }
  #date {
    height: 20px;
  }
  .pro p {
    font-size: 12px;
    width: auto;
    align-self: flex-start;
    margin-top: 10px;
    margin-left: 0;
  }
  h5 {
    font-size: 12px;
    width: auto;
    align-self: flex-start;
  }
  .profile img {
    display: none;
  }
  .card-title {
    justify-content: start;
  }
  #result {
    align-self: flex-end;
  }
  .pro {
    font-size: 10px;
    width: auto;
  }
}
</style>
