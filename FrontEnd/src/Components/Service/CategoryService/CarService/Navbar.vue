<template>
  <nav class="navbar navbar-expand-lg navbar-light">
    <i id="back-icon" class="bi bi-arrow-left-circle text-12"></i>
  </nav>
</template>

<script>
export default {}
</script>

<style>
#navbarSupportedContent {
  padding-left: 60%;
}
#logo{
  margin-left: 8%;
}
form{
  margin-left: 60%;
}
nav {
  background-color: rgb(255, 253, 253);
  height: 10vh;
  border-bottom:4px solid rgba(0, 0, 0, 0.249);
}
.d-flex1 {
  margin-left: -6%;
  padding-left: 8%;
  width: 30%;
}
.d-flex1 i {
  transition: transform 0.1s ease, box-shadow 0.1s ease; 
  display: inline-block; 
}

.d-flex1 i:hover {
  transform: scale(1.3); 
}

#search {
  margin-right: 4%;
  border: 2px solid black;
  margin-left: -11%;
  height: 6vh;
  width: 80%;
}
#back-icon {
    margin-left: 2%;
}
</style>