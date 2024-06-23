<?php
  include('connection.php');
// this for query execution perfectly
  $data_inserted = false;
  $data_updated = false;
  $data_deleted = false;
  

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(isset($_POST['idEdit'])){
    // Update the record
    $idEdit = $_POST['idEdit'];
    $titleEdit = $_POST['titleEdit'];
    $descEdit = $_POST['descEdit'];

    $SQL = "UPDATE notes SET `title` = '$titleEdit', `description` = '$descEdit' WHERE ID = '$idEdit'";

    $result = mysqli_query($connection, $SQL);

    if($result && mysqli_affected_rows($connection) > 0){
      $data_updated = true;
    }else{
      $data_updated_error = true;
    }

  }else{
    // insert the record
    $title = $_POST['title'];
    $description = $_POST['desc'];

    $SQL = "INSERT INTO notes (`title`, `description`) VALUES ('$title', '$description')";
    $result = mysqli_query($connection, $SQL);

    if($result){
      $data_inserted = true;
    }else{
      $data_inserted_error = true;
    }
  }  
}

// Delete the record:

if(isset($_GET['delete'])){
  $deleteId = $_GET['delete'];
  $SQL = "DELETE FROM notes WHERE `notes`.`ID` = $deleteId";
  
  $result = mysqli_query($connection, $SQL);

  if($result && mysqli_affected_rows($connection)){
    $data_deleted = true;
  }
  else{
    $data_deleted_error = true;
  }
}



?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP - CRUD App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="//cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet">
  </head>
  <body>
      <!-- Edit note modal goes here -->
      <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel">Edit Note</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
      <form class="px-5" method="POST">
        <input type="hidden" name="idEdit" id="idEdit">
    <div class="mb-3">
      <label for="exampleInputEmail1" class="form-label">Notes Title</label>
      <input type="text" class="form-control" id="titleEdit"  name="titleEdit" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
      <label for="exampleInputPassword1" class="form-label">Notes Description</label>
      <input type="text" class="form-control" id="descEdit" name="descEdit">
    </div>
    <button type="submit" class="btn btn-primary">Update note</button>
  </form>
</div>      
      </div>
    </div>
  </div>
</div>
      <!-- Edit note modal ends here -->

    <!-- Navbar goes here -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#" style="display: flex; align-items:center; gap: 5px;">
          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/PHP-logo.svg/2560px-PHP-logo.svg.png" alt="" height="25px">
          <span>CRUD</span>
        </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
<!-- Navbar ends here -->

<!-- Alert messages -->
<?php
// show alert message if query executed successfully:
        if($data_inserted){
          echo "
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>Success</strong> Your note added successfully.
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div>
          ";
        }

       if($data_updated){
          echo "
            <div class='alert alert-primary alert-dismissible fade show' role='alert'>
  <strong>Success</strong> Your note has been updated successfully.
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div>
          ";
        }

        if($data_deleted){
          echo "
            <div class='alert alert-primary alert-dismissible fade show' role='alert'>
  <strong>Success</strong> Your note has been deleted successfully.
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div>
          ";
        }

     ?>

<!-- Notes taking form goes here -->
 <div class="container my-5">
<form class="px-5" method="POST">
    <div class="mb-3">
      <label for="exampleInputEmail1" class="form-label">Notes Title</label>
      <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
      <label for="exampleInputPassword1" class="form-label">Notes Description</label>
      <input type="text" class="form-control" id="desc" name="desc">
    </div>
    <button type="submit" class="btn btn-primary">Add note</button>
  </form>
</div>
<!-- Notes taking form ends here -->

<!-- Notes display section goes here -->
    <div class="container">
    <table class="table" id="myTable">
  <thead>
    <tr>
      <th scope="col">S.No</th>
      <th scope="col">Title</th>
      <th scope="col">Description</th>
      <th scope="col">Time</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $SQL = "SELECT * FROM notes";
      $result = mysqli_query($connection, $SQL);

      if($result){
        if(mysqli_num_rows($result) > 0){
          $sno = 0;
          while($row = mysqli_fetch_assoc($result)){
            $sno++;
            echo "
                <tr>
      <th scope='row'>". $sno ."</th>
      <td>". $row['title'] ."</td>
      <td>". $row['description'] ."</td>
      <td>". $row['time'] ."</td>
      <td> 
        <button class='btn btn-sm btn-primary updateBtn' id='".$row['ID']."'>Update</button>
        <button class='btn btn-sm btn-danger deleteBtn' id='D".$row['ID']."'>Delete</button>
      </td>
    </tr>
            ";
          }
        }
        else{
          echo "
            <tr>
              <td>No data found</td>
            <tr>
          ";
        }
      }
    ?>
    
    
  </tbody>
</table>
    </div>
<!-- Notes display section ends here -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script>
      let table = new DataTable('#myTable');
    </script>
    <script>
      // Update front end code
      const updateBtn = document.getElementsByClassName('updateBtn');
      Array.from(updateBtn).forEach((note) => {
        note.addEventListener('click', (event) => {
          tr = event.target.parentNode.parentNode;
          title = tr.getElementsByTagName('td')[0].textContent;
          description = tr.getElementsByTagName('td')[1].textContent;   
          titleEdit.value = title;
          descEdit.value = description;
          idEdit.value = event.target.id;
          editModal = new bootstrap.Modal(document.getElementById('editModal'), {
            keyboard : false
          })
          editModal.show();
        })
      })

      // Delete front end code:
      const deleteBtn = document.getElementsByClassName('deleteBtn');
      Array.from(deleteBtn).forEach((note) => {
        note.addEventListener('click', (event) => {
          idDelete = event.target.id.substr(1,);
          if(confirm('Do you want to delete this note?')){
            window.location = `index.php?delete=${idDelete}`;
          }
        })
      })
    </script>
  </body>
</html>