<template>
  <div class="container w-auto ">
    <div class="mb-3 w-auto">
      <!-- <button class="btn" data-bs-toggle="modal" data-bs-target="#edit">
        <i class="bi text-primary text-25px bi-info-square"></i>
      </button> -->
      <div id="card" class="card border-0 p-3 w-100">
        <div class="card-body">
          <div class="d-flex gap-3 mb-3">
            <button class="btn btn-outline-danger">All</button>
            <input type="text" class="form-control w-50" placeholder="Search ..." />
          </div>

          <div id="cardMessages" data-bs-toggle="modal" data-bs-target="#edit">
            <div class="cardMessage shadow p-3 mb-3 rounded-2 d-flex align-items-center">
              <img
                src="../../../src/assets/img/cat.jpeg"
                alt="df"
                class="rounded-30"
                style="width: 50px; height: 50px"
              />
              <div class="text ms-3 flex-grow-1">
                <div class="d-flex align-items-center">
                  <h5 class="mb-0 me-3">Name</h5>
                  <p class="mb-0">sdfksjfksdjfksjfgksdf</p>
                </div>
                <p class="mb-0 text-muted">2024, 23, 02 : 12 : 3 PM</p>
              </div>
            </div>

            <!-- Repeat the above block for each message -->
            <div class="cardMessage shadow p-3 mb-3 rounded-2 d-flex align-items-center">
              <img
                src="../../../src/assets/img/cat.jpeg"
                alt="df"
                class="rounded-30"
                style="width: 50px; height: 50px"
              />
              <div class="text ms-3 flex-grow-1">
                <div class="d-flex align-items-center">
                  <h5 class="mb-0 me-3">Name</h5>
                  <p class="mb-0">sdfksjfksdjfksjfgksdf</p>
                </div>
                <p class="mb-0 text-muted">2024, 23, 02 : 12 : 3 PM</p>
              </div>
            </div>

            <!-- Additional message cards here -->
            <div class="cardMessage shadow p-3 mb-3 rounded-2 d-flex align-items-center">
              <img
                src="../../../src/assets/img/cat.jpeg"
                alt="df"
                class="rounded-30"
                style="width: 50px; height: 50px"
              />
              <div class="text ms-3 flex-grow-1">
                <div class="d-flex align-items-center">
                  <h5 class="mb-0 me-3">Name</h5>
                  <p class="mb-0">sdfksjfksdjfksjfgksdf</p>
                </div>
                <p class="mb-0 text-muted">2024, 23, 02 : 12 : 3 PM</p>
              </div>
            </div>

            <div class="cardMessage shadow p-3 mb-3 rounded-2 d-flex align-items-center">
              <img
                src="../../../src/assets/img/cat.jpeg"
                alt="df"
                class="rounded-30 shadow-lg"
                style="width: 50px; height: 50px"
              />
              <div class="text ms-3 flex-grow-1">
                <div class="d-flex align-items-center">
                  <h5 class="mb-0 me-3">Name</h5>
                  <p class="mb-0">sdfksjfksdjfksjfgksdf</p>
                </div>
                <p class="mb-0 text-muted">2024, 23, 02 : 12 : 3 PM</p>
              </div>
            </div>

            <div class="cardMessage shadow p-3 mb-3 rounded-2 d-flex align-items-center">
              <img
                src="../../../src/assets/img/cat.jpeg"
                alt="df"
                class="rounded-30"
                style="width: 50px; height: 50px"
              />
              <div class="text ms-3 flex-grow-1">
                <div class="d-flex align-items-center">
                  <h5 class="mb-0 me-3">Name</h5>
                  <p class="mb-0">sdfksjfksdjfksjfgksdf</p>
                </div>
                <p class="mb-0 text-muted">2024, 23, 02 : 12 : 3 PM</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- <div
    class="modal fade  "
    id="edit"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  
  > -->
  <div
    class="modal fade"
    id="edit"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-scrollable mr-0 modal-dialog-centered">
      <div class="modal-content rounded-4 border-0 mr-0">
        <!-- <div class="modal-header w-100 border-0"></div> -->
        <div class="card rounded-4 border-0 p-3">
          <div class="h-auto text-center w-auto">
            <div class="card-group border-1 mx-auto mt-3" style="max-width: 100%">
              <div class="card-body border-1 p-0 rounded-3 shadow-lg">
                <h5 class="card-title">
                  <div
                    class="profile w-100 p-1 rounded-2 d-flex gap-3 shadow align-baseline"
                  >
                    <img
                      id="imgPro"
                      src="/src/assets/img/cat.jpeg"
                      alt="cat"
                      class="rounded-circle"
                      style="width: 50px; height: 50px"
                    />
                    <h4 class="align-baseline">koeuk</h4>

                    <div
                      class="list d-flex justify-content-end align-items-center w-100 gap-2"
                    >
                      <button type="button" class="btn "   data-bs-toggle="modal"
                      data-bs-target="#zoom">   <i class="bi bi-arrows-angle-expand"></i></button>
                      
                      <button type="button" class="btn "  data-bs-dismiss="modal"
                      aria-label="Close">
                      <i class="bi bi-backspace-reverse"></i>
                      </button>
                    </div>
                  </div>
                </h5>
                <div
                  id="chats"
                  class="card-body text-end scrollspy-example overflow-auto"
                  style="max-height: 300px"
                >
                  <div class="chat d-flex gap-2 mb-2">
                    <img
                      src="/src/assets/img/cat.jpeg"
                      alt="cat"
                      class="rounded-circle"
                      style="width: 40px; height: 40px"
                    />
                    <div class="textchat">
                      <p id="para" class="p-1 m-1 border align-baseline">
                        skksdfssdfsddf
                      </p>
                      <p id="para" class="p-1 m-1 border align-baseline">
                        skksdfssdfsddf
                      </p>
                    </div>
                  </div>
                  <div class="chat d-flex gap-2 mb-2">
                    <img
                      src="/src/assets/img/cat.jpeg"
                      alt="cat"
                      class="rounded-circle"
                      style="width: 40px; height: 40px"
                    />
                    <div class="textchat">
                      <p id="para1" class="p-1 m-1 border align-baseline">
                        skksdfssdfsddf
                      </p>
                      <p id="para1" class="p-1 m-1 border align-baseline">
                        skksdfssdfsddf
                      </p>
                    </div>
                  </div>
                  <div class="chat d-flex justify-content-end gap-2 mb-2">
                    <div class="textchatright">
                      <p id="para1" class="p-1 border align-baseline">
                        skksdfssdfsddfskksdfssdfsddfskksdfssdfsddf
                      </p>
                      <p id="para1" class="p-1 border align-baseline">skksdfssdfsddf</p>
                    </div>
                    <img
                      src="/src/assets/img/cat.jpeg"
                      alt="cat"
                      class="rounded-circle"
                      style="width: 40px; height: 40px"
                    />
                  </div>
                  <div class="chat d-flex gap-2 mb-2">
                    <img
                      src="/src/assets/img/cat.jpeg"
                      alt="cat"
                      class="rounded-circle"
                      style="width: 40px; height: 40px"
                    />
                    <div class="textchat">
                      <p id="para" class="p-1 m-1 border align-baseline">
                        skksdfssdfsddsdfsddfskksdfssdfsddsdfsddfskksdfssdfsddsdfsddf
                      </p>
                      <p id="para" class="p-1 m-1 border align-baseline">
                        skksdfssdfsddsdfsddf
                      </p>
                    </div>
                  </div>
                </div>
                <div class="send p-3 d-flex gap-1">
                  <button class="btn">
                    <i class="bi text-25px text-success bi-image"></i>
                  </button>
                  <button class="btn">
                    <i class="bi text-25px text-success bi-telephone"></i>
                  </button>
                  <input type="text" class="form-control w-75" placeholder="text ..." />
                  <button class="btn">
                    <i class="bi text-25px text-success bi-send-arrow-up"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import Navbar from "@/Components/Fixer/Navbar.vue";
export default {
  name: "chatView",
  // components: {
  //   Navbar,
  // },
};

// function scrollUp() {
//     document.querySelector('.scrollspy-example').scrollBy({ top: -100, behavior: 'smooth' });
// }

// function scrollDown() {
//     document.querySelector('.scrollspy-example').scrollBy({ top: 100, behavior: 'smooth' });
// }
</script>

<style>
.rounded-25 {
  border-radius: 25%;
}
.cardMessage {
  transition: transform 0.2s;
}
.cardMessage:hover {
  transform: scale(1.02);
}
#card {
  width: auto;
}
.btn-close {
  display: flex;
  justify-content: end;
  margin-left: 210px;
}
#imgPro {
  width: 80px;
  padding: 5px;
}
img {
  width: 90px;
}
#card {
  display: grid;
  flex-wrap: wrap;
  grid-template-columns: 3fr 2fr;
  grid-gap: 10px;
}
#cardMessage {
  display: flex;
  height: auto;
}
#cardMessage img {
  width: 50px;
  height: 50px;
  display: flex;
  align-self: center;
  background-color: aquamarine;
}
#message {
  display: flex;
  flex-direction: column;
  /* align-items: center; */
  justify-content: center;
  flex: start;
}
#name {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  flex: start;
}
h4 {
  margin: 0;
  display: flex;
  align-self: center;
}
#para1 {
  width: 200px;
  border-radius: 5px;
}
#para {
  border-radius: 5px;
}

#chat1 img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
}
#chat img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
}
#chat1 {
  display: flex;
  flex-direction: row;
  /* align-items: center; */
  justify-content: end;
  flex: end;
  width: 100%;
  margin-right: 0;
}
/*  */
#chat {
  display: flex;
  /* height: auto; */
  flex-direction: row;
  /* align-items: center; */
  justify-content: start;
  flex: start;
  width: 200px;
}
/* .list-btn  {
  margin-right: 3px;
} */
.scrollspy-example {
  position: relative;
  height: 400px;
  overflow: auto;
}
.chat-img {
  max-width: 50px;
}
.scroll-button {
  position: fixed;
  right: 20px;
  z-index: 1000;
}
.scroll-button-up {
  bottom: 60px;
}
.scroll-button-down {
  bottom: 20px;
}
.textchat {
  width: 200px;
  display: flex;
  flex-direction: column;
  border-radius: 10px;
}
#textchatright {
  width: 200px;
  display: flex;
  flex-direction: column;
  border-radius: 10px;
  justify-content: end;
}

@media (min-width: 1023px) {
  .div {
    width: 100%;
  }
}
@media (max-width: 768px) {
  .profile h4 {
    font-size: 1rem;
  }

  .chat img,
  .chat1 img {
    width: 30px;
    height: 30px;
  }

  .textchat p,
  .textchatright p {
    font-size: 0.875rem;
  }

  .send .form-control {
    font-size: 0.875rem;
  }
}
@media (min-width: 512px) {
  .div {
    width: 100%;
  }
  .modal-content {
    width: 100%;
  }
}
/* 
@media (max-width: 576px) {
    .profile {
      flex-direction: column;
      align-items: center;
      gap: 0.5rem;
    }

    .profile h4 {
      font-size: 0.875rem;
    }

    .send {
      flex-direction: column;
      gap: 0.5rem;
    }

    .send .btn i {
      font-size: 1.25rem;
    }

    .send .form-control {
      width: 100%;
      font-size: 0.75rem;
    }
  } */
</style>
