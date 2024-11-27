document.addEventListener("DOMContentLoaded", function() {
    var grid = document.querySelector('.photo-gallery');
    var msnry = new Masonry(grid, {
        itemSelector: '.grid-item',
        columnWidth: '.grid-item',
        percentPosition: true

    });

     // Ensuring images are loaded before layout
     imagesLoaded(grid).on('progress', function () {
        msnry.layout();
    });

    //making the images clickable
    const images = document.querySelectorAll('.grid-item');
    
    Array.from(images).forEach((image)=> {
        let image_id;

        image.addEventListener('click', function(event){ 

            image_id=event.target.id.replace('image',"");
            fetch(`components/imageInfo.php?image_id=${image_id}&action=open`)
            .then(response => {
                 if (!response.ok) {
                     throw new Error(`HTTP error! Status: ${response.status}`);
                 }
            return response.text(); // it's a text containing the content of the html
            })
            .then(data => {
                let popup = document.getElementsByClassName('popup')[0]
                popup.style.display='flex'; 
                popup.innerHTML = data; 

                 //event listener for save button 
                const save_button=document.getElementsByClassName('save-button')[0]; 
                if(save_button){
                    save_button.addEventListener('click',()=>{
                    handleImageSave(save_button)
                })}

                 //event listener for remove button 
                    const close_button=document.getElementById('image-popup-close');
                    close_button.addEventListener('click',()=>{
                        handlePopupClosure(popup); 
                 }); 

                 //add event listener on delete button 
                 const delete_button=document.getElementsByClassName('delete-button')[0];
                 if(delete_button){
                    delete_button.addEventListener('click',()=>{
                        handleImageDelete()
                    })
                 }
            })
            .catch(error => {
            console.error('Error fetching data:', error); // Handle any errors
             });

 }); 

     })
    }); 


    


//the search query should be submitted automatically when the ordering changes 
document.getElementById("order-select").addEventListener('change',()=>{
    document.getElementById("search-form").submit(); 
})

function setSaveButtonState(save_button,saved){
    if(saved){
        save_button.classList.add('unsave'); 
        save_button.innerText= 'Unsave';
    }else{
        save_button.classList.remove('unsave');
        save_button.innerText= 'Save';
    }

}

function handleImageSave(save_button) {
    // Getting image ID of the image being currently displayed

    fetch('dbModule/updateSaveTable.php', {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json', 
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json(); 
    })
    .then(data => {
        if (data.success) {
            if (data.action === 'saved') {
                setSaveButtonState(save_button, true);
            } else {
                setSaveButtonState(save_button, false);
            }
        }else {
            console.log(data);
        }
    })
    .catch(error => {
        console.error('Error fetching data:', error); // Handle any errors
    });
}

function handleImageDelete (){
    fetch('dbModule/deleteImage.php', {
        method: 'POST'
    }).then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json(); 
    })
    .then(data => {
        console.log('Request succeeded:', data);
    })
    .catch(error => {
        console.error('An error occurred:', error);
    });

    location.reload(); 
}

function handlePopupClosure (popup){
    popup.style.display='none'; 
    fetch(`components/imageInfo.php?action=close`); //clear the image tracking
}






