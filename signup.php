
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="styles/signin.css">

</head>
<body>
<main>

<?php 
$errors = [];


if ($_SERVER['REQUEST_METHOD'] === "POST") {

  if (empty($_POST["username"])) {
    $errors['username'] = "Username is required.";
  } else {
    $username = trim(htmlspecialchars($_POST["username"]));
  }
  
  if (empty($_POST["email"])) {
    $errors['email'] = "Email is required.";
  } else {
    $email = trim(htmlspecialchars($_POST["email"]));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = "Invalid email format";
    } 
    
  }
  if (empty($_POST["password"])) {
    $errors['password'] = "Password is required.";
  } else {
    $password = trim(htmlspecialchars($_POST["password"]));
  }

  // Confirm password validation
  if (empty($_POST["retypepassword"])) {
    $errors['retypepassword'] = "Password confirmation is required.";
  } else {
    $retypepassword = trim(htmlspecialchars($_POST["retypepassword"]));
  }

  // Check if passwords match
 if (!empty($password) && !empty($retypepassword) && $password !== $retypepassword) {
    $errors['password_match'] = "Passwords do not match.";
}


  // Check if no errors before processing further
  if (empty($errors)) {
    // Continue with further processing (e.g., database insertion)
  }

  if(!empty($_POST["bio"]))
  $bio=trim(htmlspecialchars($_POST["bio"]));



}



?>

  <!-- Section: Design Block -->
<section class="background-radial-gradient overflow-hidden">
 

  <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
    <div class="row gx-lg-5 align-items-center mb-5">
      <div class="alltexts">
      

      <div class="col-lg-6 mb-5 mb-lg-0 position-relative">

        <div class="card bg-glass">
          <div class="card-body px-4 py-5 px-md-5">
            <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?> method="POST" novalidate>
              <h2>Create Your Account</h2>
              <br>
              <!-- username input -->
              <div data-mdb-input-init class="form-outline mb-4">
                <label class="form-label" for="form3Example2">Username</label>
                <input type="text" id="username" name="username" class="form-control" 
                value = <?php if(isset($username)) echo $username?>>
                <div class="error-message"> <?php if(isset($errors["username"])) echo $errors["username"] ?> </div> 

              </div>
            
              <!-- Email input -->
              <div data-mdb-input-init class="form-outline mb-4">
                <label class="form-label" for="form3Example3">Email address</label>
                <input type="email" id="email" name="email" class="form-control" 
                value = <?php if(isset($email)) echo $email?>>
                <div class="error-message"> <?php if(isset($errors["email"])) echo $errors["email"] ?> </div> 
             
              </div>

              <!-- Password input -->
              <div data-mdb-input-init class="form-outline mb-4">
                <label class="form-label" for="form3Example4">Password</label>
                <input type="password" id="password" name="password" class="form-control" />
                <div class="error-message"><?php if(isset($errors["password"])) echo $errors["password"]; ?></div>
                </div>

              <div data-mdb-input-init class="form-outline mb-4">
                <label class="form-label" for="form3Example4">Confirm your password</label>
                <input type="password" id="retypepassword" name="retypepassword" class="form-control"/>
                <div class="error-message text-danger"><?php if(isset($errors["retypepassword"])) echo $errors["retypepassword"]; ?></div>
                <div class="error-message text-danger"><?php if(isset($errors["password_match"])) echo $errors["password_match"]; ?></div>
              </div>

              <!-- BIO input -->
              <div data-mdb-input-init class="form-outline mb-4">
                <label class="form-label" for="form3Example4">Enter a description of yourself</label>
                <textarea id="bio" name="bio" class="form-control" 
                 value = <?php if(isset($bio)) echo $username?>> </textarea>
              </div>


              <!-- Submit button -->
              <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">
                Sign up
              </button>
              <p>Login to an existing Account? <a href="signin.php">Click Here</a></p>
             

              </div>
            </form>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
</section>
</main>


      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
      integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
      crossorigin="anonymous"></script>
</body>
