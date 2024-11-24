<?php
if(empty($_SESSION['user_id'])){

echo "

  <div id='not-signed-in-message'>

 <h1> You cannot access this page <br> unless you are a registered user ! </h1>

 <img src='webPictures/notsignin.png' width=400px> 

<h4> I am a registered user : <a href='#'> Sign in here </a> </h4>

<p> Not a member of our community ?   <a href='#'> Register Here </a> </p>

</div>

"; 


exit;


}

?>