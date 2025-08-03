<template>
  <nav id="nav-footer">
    <div id="footer-main">
      <div id="media">
        <h5>Find us in social media</h5>
        <div id="media2">
          <i class="bi bi-facebook text-35px"></i>
          <i class="bi bi-instagram text-35px"></i>
          <i class="bi bi-twitter-x text-35px"></i>
          <i class="bi bi-linkedin text-35px"></i>
        </div>
      </div>
      <div id="educations">
        <h5>Support and Education</h5>
        <p>Help & Support</p>
        <p>Trust & Safety</p>
        <p>Protect</p>
        <p>Faster</p>
      </div>
      <div id="community">
        <h5>Community</h5>
        <p>Community Standards</p>
        <p>Events</p>
        <p>Invite a Friend</p>
        <p>Customer Success Stories</p>
      </div>
      <div id="about">
        <h5>About</h5>
        <p>Careers</p>
        <p>Press & News</p>
        <p>Privacy Policy</p>
        <p>Investor Relations</p>
      </div>
    </div>
  </nav>
</template>

<script>
export default {}
</script>

<style>
#nav-footer {
  width: 100%;
  height: 40vh;
  background-color: #ffffff;
}
#footer-main {
  display: flex;
  padding-top: 3%;
  margin-left: 8%;
  gap: 14%;
}
#media2 i{
  margin-right: 7%;
}p{
    color: rgba(32, 32, 32, 0.578);
}
</style>