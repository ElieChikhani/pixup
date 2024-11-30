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
    imageView.style.borderColor="#bbb5ff";
    }else {
        imageView.style.backgroundImage="none"; 
        imageView.style.backgroundColor="#f7f8ff";
        imageView.textContent=dropAreaContent;
        imageView.style.borderColor="#dc3545"; 
    }
}


//Drag and drop image functionality
dropArea.addEventListener("dragover",function(event){
    event.preventDefault();
})

dropArea.addEventListener("drop",function(event){
    event.preventDefault();
    inputFile.files = event.dataTransfer.files; 
    uploadImage();
})


//letting the bottstrap javascipt show the validation of the script 
const forms = document.querySelectorAll('.needs-validation');
Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()

          if(!inputFile.files[0]){
            imageView.style.borderColor="#dc3545"; 
          }
          
        }
        form.classList.add('was-validated')
      }, false)
}) 

//character count in text file 
const charCount=document.getElementById("char-count"); 
const textArea = document.getElementById("image-description");
const TEXTLIMIT = 500; 

textArea.addEventListener("input", () => {
    let count = textArea.value.length;
    charCount.innerHTML=(TEXTLIMIT - count )+" characters left"; 
})














