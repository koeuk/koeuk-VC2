<template>
  <Navbar />
  <div class="container">
    <div class="mb-3">
      <div class="card-currently mt-4">
        <!-- <CardCurrently/>
        <ListBtn /> -->
        <div class="booking mt-5">
          <div class="group p-3 bg-active bg-info">
            <ListBooking />
          </div>
        </div>
        <!-- <CardSkill/> -->
      </div>
    </div>
  </div>
</template>

<script>
// import CardCurrently from "@/Components/Fixer/CardCurrently.vue";
import Navbar from "@/Components/Fixer/Navbar.vue";
// import ListBtn from "@/Components/Fixer/ListBtn.vue";
import ListBooking from "@/Components/Fixer/ListBooking.vue";
// import CardSkill from "@/Components/Fixer/CardSkill.vue";
export default {
  name: "fixerUser",
  components: {
    Navbar,
    // CardCurrently,
    // ListBtn,
    ListBooking,
//     CardSkill,
  },
};
</script>

<style></style>
