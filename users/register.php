<?php
include "../config/db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name,email,password) VALUES ('$name','$email','$password')";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['user_id'] = mysqli_insert_id($conn);
        $_SESSION['user_name'] = $name;
        header("Location: ../index.php");
        exit();
    } else {
        $error = 'Registration failed.';
    }
}
?>
<?php include "../includes/header.php"; ?>
<div class="container mt-5" style="max-width:400px;">
  <h2>Register</h2>
  <?php if(!empty($error)): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
  <form method="POST">
    <div class="form-group">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Register</button>
  </form>
  <p class="mt-2">Already have an account? <a href="login.php">Log in</a></p>
</div>
<?php include "../includes/footer.php"; ?>
