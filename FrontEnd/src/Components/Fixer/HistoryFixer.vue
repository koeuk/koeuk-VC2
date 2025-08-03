<template>
  <div class="container mt-5">
    <h1 class="text-center mb-4">History of Fixer</h1>
    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Name</th>
          <th scope="col">Status</th>
          <th scope="col">Date</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>Mark</td>
          <td><span class="badge bg-success">Completed</span></td>
          <td>2024-07-01</td>
        </tr>
        <tr>
          <th scope="row">2</th>
          <td>Jacob</td>
          <td><span class="badge bg-warning text-dark">In Progress</span></td>
          <td>2024-07-15</td>
        </tr>
        <tr>
          <th scope="row">3</th>
          <td>Larry the Bird</td>
          <td><span class="badge bg-secondary text-light">Pending</span></td>
          <td>2024-07-19</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {}
</script>

<style scoped>
.container {
  background-color: #ffffff;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

#st-1 {
    background-color: orange;
}

h1 {
  font-family: 'Roboto', sans-serif;
  font-weight: 700;
  color: #343a40;
  margin-bottom: 20px;
}

.table {
  margin-top: 20px;
  border-collapse: separate;
  border-spacing: 0;
}

.table thead th {
  background-color: orange;
  color: #ffffff;
  font-weight: 600;
  border: 1px solid #dee2e6;
}

.table tbody tr {
  transition: background-color 0.3s ease;
}

.table tbody tr:hover {
  background-color: #f1f1f1;
}

.table-bordered {
  border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
  border: 1px solid #dee2e6;
}

.badge {
  font-size: 0.9rem;
  padding: 0.3em 0.6em;
  border-radius: 0.2rem;
  vertical-align: middle;
}
</style>
