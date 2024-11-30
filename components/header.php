<?php

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
                            <a class="nav-link active" href="index.php" aria-current="page">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="gallery.php">Gallery</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="saved.php">Saved</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="upload.php">Upload</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="creators.php">Creators</a>
                        </li>

                    </ul>

';

if(isset($_SESSION) && !empty($_SESSION['username']) && !empty($_SESSION['user_id']  ) ){
    $username = $_SESSION['username']; 
    echo "<button id='profile-button'>
    <i class='fas fa-user-circle'></i> $username
    </button>"; 
}else {
    echo "
       <a id='signin' class='btn btn-primary'>Sign in</a>

       <a id='login' class='btn btn-primary'>Log in</a>

    "; 
}

echo '
                </div>
            </div>
        </nav>

    </header>


'; 


?>