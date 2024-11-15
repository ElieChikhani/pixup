const dropArea = document.getElementById("drop-area"); 
const inputFile = document.getElementById("input-file");
const imageView = document.getElementById("img-view"); 
const dropAreaContent =  imageView.textContent; 

inputFile.addEventListener("change",uploadImage);

let previousImage; 


//showing image preview
function uploadImage(){
    if(inputFile.files[0]){
    let imgURL=URL.createObjectURL(inputFile.files[0]);
    imageView.style.backgroundImage=`url(${imgURL})`; 
    imageView.textContent="";
    dropArea.style.border="none";
    imageView.style.backgroundColor="white";
    }else {
        imageView.style.backgroundImage="none"; 
        imageView.style.backgroundColor="#f7f8ff";
        imageView.textContent=dropAreaContent;

    }
}


//Drag and drop functionality
dropArea.addEventListener("dragover",function(event){
    event.preventDefault();
})

dropArea.addEventListener("drop",function(event){
    event.preventDefault();
    inputFile.files = event.dataTransfer.files; 
    uploadImage();
})


//form controls 
const nextButton = document.getElementById("next-button"); 
const image_upload_form = document.getElementById("image-upload-form");
const image_description_form = document.getElementById("image-description-form");
nextButton.addEventListener("click",saveImage); 






