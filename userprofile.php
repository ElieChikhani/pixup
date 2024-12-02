<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles/userprofile.css">
</head>

<body>
  <?php
 
  if (!isset($_SESSION)) session_start();
  $user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

  // Fetch user information from the API
  
  $user_info = "http://localhost/PIXUP/dbModule/getUserInfo.php?user_id=$user_id";
  $result = file_get_contents($user_info);

  if ($result === false) {
      echo "<p>Error occurred while fetching user information.</p>";
      exit;
  }

  $data = json_decode($result, true);

  if (json_last_error() !== JSON_ERROR_NONE) {
        $error = json_last_error_msg();
        echo "<p>An error occurred while processing the image information. Please try again later $error</p>";
    } 
    
    if(!$data['success']){
      exit;
    }

  $userInfoData = $data['user_info'];
  $user_username = isset($userInfoData['username']) ? $userInfoData['username'] :null;
  $user_email = isset($userInfoData['email']) ? $userInfoData['email'] : null;
  $user_bio = isset($userInfoData['bio']) ? $userInfoData['bio'] : null;
  $user_creation_date = isset($userInfoData['creation_date']) ? $userInfoData['creation_date'] : null;
  $user_imageCount = isset($userInfoData['imageCount']) ? $userInfoData['imageCount'] : null;
  $user_albumCount = isset($userInfoData['albumCount']) ? $userInfoData['albumCount'] : null;
  ?>

  <main>
      <section class="h-100 gradient-custom-2">
          <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
          <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

          <div class="container py-5 h-100">
              <div class="row d-flex justify-content-center">
                  <div class="col col-lg-9 col-xl-8">
                      <div class="card">
                          <div class="rounded-top text-white d-flex flex-row" style="background-color: hsl(221, 26%, 19%); height:100px;">
                              <div class="ms-3" style="margin-top: 15px;">
                                  <h2><?php echo htmlspecialchars($user_username); ?></h2>
                                  <p><?php echo htmlspecialchars($user_email); ?></p>
                              </div>
                          </div>

                          <div class="p-4 text-black bg-body-tertiary">
                              <div class="d-flex justify-content-end text-center py-1 text-body" style="margin-top: -20px; height: 25px;">
                                  <p class="mb-1 h5"><?php echo htmlspecialchars($user_imageCount); ?> Images</p>
                              </div>
                          </div>

                          <div class="card-body p-4 text-black" style="margin-top: 0px;">
                              <div class="mb-5 text-body">
                                  <p class="lead fw-normal mb-1">About</p>
                                  <div class="p-4 bg-body-tertiary">
                                      <p class="font-italic mb-1"><?php echo htmlspecialchars($user_bio); ?></p>
                                  </div>
                              </div>
                              <div class="d-flex justify-content-between align-items-center mb-4 text-body">
                                  <p class="lead fw-normal mb-0">Recent photos</p>
                              </div>
                              <div class="row g-2">
                                  <div class="col mb-2">
                                      <img src="https://mdbcdn.b-cdn.net/img/Photos/Lightbox/Original/img%20(112).webp" alt="image 1"
                                           class="w-100 rounded-3">
                                  </div>
                                  <div class="col mb-2">
                                      <img src="https://mdbcdn.b-cdn.net/img/Photos/Lightbox/Original/img%20(107).webp" alt="image 2"
                                           class="w-100 rounded-3">
                                  </div>
                              </div>
                              <div class="row g-2">
                                  <div class="col">
                                      <img src="https://mdbcdn.b-cdn.net/img/Photos/Lightbox/Original/img%20(108).webp" alt="image 3"
                                           class="w-100 rounded-3">
                                  </div>
                                  <div class="col">
                                      <img src="https://mdbcdn.b-cdn.net/img/Photos/Lightbox/Original/img%20(114).webp" alt="image 4"
                                           class="w-100 rounded-3">
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
</html>
