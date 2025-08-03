<template>

  <!-- ----------------------------------------- -->
  <div class="container mt-lg-5 gap-3 d-flex row-cols-3">
    <!------------------------- Card ----------------- -->
    <div class="col">
      <div class="card border-0 h-auto">
        <img src="/src/assets/img/images.jpeg" class="card-img-top hover-img" alt="image" />
        <div class="card-body">
          <h5 class="card-title d-flex justify-content-start">Card title</h5>
          <p class="card-text">I'm skill</p>
          <div class="d-flex gap-1 justify-content-end">
            <a href="#" id="btn-delete" class="btn btn-outline-danger">Delete</a>
          </div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card border-0 h-auto">
        <img src="/src/assets/img/images.jpeg" class="card-img-top hover-img" alt="image" />
        <div class="card-body">
          <h5 class="card-title d-flex justify-content-start">Card title</h5>
          <p class="card-text">I'm skill</p>
          <div class="d-flex gap-1 justify-content-end">
            <a href="#" id="btn-delete" class="btn btn-outline-danger">Delete</a>
          </div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card border-0 h-auto">
        <img src="/src/assets/img/images.jpeg" class="card-img-top hover-img" alt="image" />
        <div class="card-body">
          <h5 class="card-title d-flex justify-content-start">Card title</h5>
          <p class="card-text">I'm skill</p>
          <div class="d-flex gap-1 justify-content-end">
            <a href="#" id="btn-delete" class="btn btn-outline-danger">Delete</a>
          </div>
        </div>
      </div>
    </div>
    </div>

  <!------------------------- Card ----------------- -->


<!-- ----------------select box ---------------- -->
  <div
    class="modal fade"
    id="update"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content p-4">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create Card</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <input class="form-control" type="file" id="formFile" />
            </div>
            <div class="mb-3">
              <input
                type="text"
                class="form-control"
                id="recipient-name"
                placeholder="Card title"
              />
            </div>
            <div class="mb-3">
              <label for="message-text" class="col-form-label">Description:</label>
              <textarea
                class="form-control"
                id="message-text"
                placeholder="Card description"
              ></textarea>
            </div>
            <select class="form-select" aria-label="Default select example">
              <option selected>Open this select menu</option>
              <option value="1">One</option>
              <option value="2">Two</option>
              <option value="3">Three</option>
            </select>
          </form>
        </div>
        <div class="modal-footer">
          <button id="btn-delete" type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancel
          </button>
          <button id="btn" type="button" class="btn btn-primary">Create</button>
        </div>
      </div>
    </div>

  </div>

<!-- ----------------select box ---------------- -->

</template>

<script>
export default {
  // components: {
  //   Navbar,
  // },
};
</script>

<style>
/* -------------------------------- */

.modal-content {
  width: 100%;
}
/* -------------------------------- */

.modal-dialog {
  max-width: 800px; /* Adjust as needed */
}
/* -------------------------------- */

.modal-header,
.modal-body,
.modal-footer {
  padding: 1rem 1.5rem;
}

/* ----------------- style color card ------------------- */
#btn {
  border: 1px solid green;
  color: rgb(0, 0, 0);
  border-radius: 3px;
}
#btn-delete {
  border: 1px solid orange;
  color: rgb(0, 0, 0);
  border-radius: 3px;
}
/* -------------------------------- */
#btn:hover {
  background-color: green;
  color: white;
  border: none;
  border-radius: 3px;
  cursor: pointer;
  transition: 0.3s ease;
  border: 1px solid green;
  transition: color 0.3s ease;
  bottom: 2px;
  position: relative;
  box-shadow: 0px 4px 8px 4px rgba(0, 0, 0, 0.2);
}
/* -------------------------------- */

#btn-delete:hover {
  background-color: orange;
  color: white;
  border: none;
  border-radius: 3px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  transition: color 0.3s ease;
  bottom: 2px;
  position: relative;
  box-shadow: 0px 4px 8px 4px rgba(0, 0, 0, 0.2);
}
/* ----------------- style color card ------------------- */

.card {
      overflow: hidden; /* Ensure the zoom effect stays within the card */
    }

    .hover-img {
      transition: transform 0.3s ease; /* Smooth transition for the zoom effect */
    }

    .card:hover .hover-img {
      transform: scale(1.05); /* Zoom effect */
    }

</style>
