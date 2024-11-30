
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>signin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles/signin.css">

</head>
<body>
<main>

<?php

//checking that a post request reloaded this page
if($_SERVER['REQUEST_METHOD']==="POST"){

   // Validating username or email
  if (empty($_POST["email_or_username"])) {
    $errors['email_or_username'] = "Username or Email is required.";
  } else {
    $email_or_username= trim(htmlspecialchars($_POST["email_or_username"]));
  }


  if (empty($_POST["password"])) {
    $errors['password'] = "Password is required";
  } else {
    $password = trim(htmlspecialchars($_POST["password"]));
  }

  if(empty($errors)){
    include "dbModule/checkCredentials.php"; 
    $errors = checkCredentials($email_or_username,$password); 
  }


}



?>

<!-- BOOTSTRAP TEMPLATE -->
<section class="background-radial-gradient overflow-hidden">
 

  <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5" id="all-contents">
    <div class="row gx-lg-5 align-items-center mb-5">
      <div class="alltexts">
      <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
        <h1 class="my-5 display-5 fw-bold ls-tight" style="color: hsl(218, 81%, 95%)">
          Welcome Back!<br />
          <span style="color: hsl(218, 81%, 75%)">Login From Your Existing Account!</span>
        </h1>
        <p class="mb-4 opacity-70" style="color: hsl(218, 81%, 85%)">
            Your creative space to share, organize, and explore stunning pictures. 
            Sign in now to connect with a vibrant community of photo enthusiasts, showcase your talent, 
            and get inspired by countless amazing visuals. Let’s make your imagination shine on PixUp!
        </p>
      </div>

      <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
        <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
        <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

        <div class="card bg-glass">
          <div class="card-body px-4 py-5 px-md-5">
            <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?> method="POST">
              <!-- Email input -->
              <div data-mdb-input-init class="form-outline mb-4">
                <label class="form-label" for="form3Example3">Email address or username</label>
                <input type="text" id="email-username" name="email_or_username" class="form-control" 
                    value = <?php if(isset($email_or_username)) echo $email_or_username?>>
                <div class="error-message"> <?php if(isset($errors["email_or_username"])) echo $errors["email_or_username"] ?> </div> 
              </div>

              <!-- Password input -->
              <div data-mdb-input-init class="form-outline mb-4">
                <label class="form-label" for="form3Example4">Password</label>
                <input type="password" id="password" name="password" class="form-control"/>
                <div class="error-message"> <?php if(isset($errors["password"])) echo $errors["password"]  ?> </div> 
              </div>

              <div class="error-message"> <?php if(isset($errors["general_error"])) echo $errors["general_error"]  ?> </div> 
                
              <!-- Submit button -->
              <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">
                Login
              </button>
              <p>Create An Account?<a href="signup.php"> Click Here</a></p>
            </form>

            </div>
            
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
