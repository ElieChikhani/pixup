<?php

$current_page = basename($_SERVER['PHP_SELF']);

echo '

<header>

    <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#"> <i class="fas fa-camera-retro"> </i> PixUp</a>
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link ' . ($current_page == 'index.php' ? 'active' : '') . '" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ' . ($current_page == 'gallery.php' ? 'active' : '') . '" href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ' . ($current_page == 'saved.php' ? 'active' : '') . '" href="saved.php">Saved</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ' . ($current_page == 'upload.php' ? 'active' : '') . '" href="upload.php">Upload</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ' . ($current_page == 'creators.php' ? 'active' : '') . '" href="creators.php">Creators</a>
                    </li>
                </ul>
';

if(isset($_SESSION) && !empty($_SESSION['username']) && !empty($_SESSION['user_id']  ) ){
    $username = $_SESSION['username']; 
    $user_id = $_SESSION['user_id'];
    echo "<a id='profile-button' href=userprofile.php?user_id=$user_id>
    <i class='fas fa-user-circle'></i> $username
    </a>

    <button id='sign-out-button'> <i class='fa fa-sign-out'> </i> </button>

    <script src='scripts/header.js'> </script>
    
    "; 

    
}else {
    echo "
       <a id='signin' class='btn btn-primary' href = 'signin.php'>Sign In</a>

       <a id='login' class='btn btn-primary' href = 'signup.php'>Sign Up</a>

    "; 
}

echo '
                </div>
            </div>
        </nav>

    </header>


'; 


?>