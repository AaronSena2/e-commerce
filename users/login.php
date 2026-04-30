<?php
include "../config/db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($result);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['is_admin'] = $user['is_admin'];
        header("Location: ../index.php");
        exit();
    } else {
        $error = 'Invalid email or password.';
    }
}
?>
<?php include "../includes/header.php"; ?>
<div class="container mt-5" style="max-width:400px;">
  <h2>Login</h2>
  <?php if(!empty($error)): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
  <form method="POST">
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Login</button>
  </form>
  <p class="mt-2">Don't have an account? <a href="register.php">Register</a></p>
</div>
<?php include "../includes/footer.php"; ?>
