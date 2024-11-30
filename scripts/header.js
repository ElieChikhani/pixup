const sign_out = document.getElementById('sign-out-button');
console.log(sign_out); if(sign_out){
    sign_out.addEventListener('click', function(){
        window.location.href = 'components/signout.php';
    }); 
}