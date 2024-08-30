// Get the image upload modal
var imageModal = document.getElementById("imagePopup");
var initialImagesData = [];

// Open modal and store initial data
function openImageModal() {
    imageModal.style.display = "block";
    initialImagesData = JSON.parse(JSON.stringify(imagesData));
}

// Close modal without saving
function cancelImageModal() {
    imagesData = JSON.parse(JSON.stringify(initialImagesData));
    displayImages();
    closeImageModal();
}

function closeImageModal() {
    imageModal.style.display = "none";
}

// Image upload handling
let imageInput = document.getElementById('img');
let imageContainer = document.getElementById('imageContainer');
if (!imagesData) {
    var imagesData = [];
}

imageInput.addEventListener('change', function(event) {
    let files = event.target.files;
    for (let i = 0; i < files.length; i++) {
        let file = files[i];
        let reader = new FileReader();
        reader.onload = function(e) {
            let imgData = e.target.result;
            imagesData.push(imgData);
            displayImages();
        }
        reader.readAsDataURL(file);
    }
});

function displayImages() {
    imageContainer.innerHTML = '';
    for (let i = 0; i < imagesData.length; i++) {
        let imgWrapper = document.createElement('div');
        imgWrapper.className = 'image-wrapper';
        imgWrapper.innerHTML = `
            <img src="${imagesData[i]}" class="uploaded-image">
            <button type="button" onclick="removeImage(${i})" class="button-spec little">-</button>
        `;
        imageContainer.appendChild(imgWrapper);
    }
}

function removeImage(index) {
    imagesData.splice(index, 1);
    displayImages();
}

function confirmImages() {
    let uploadedImagesData = document.getElementById('uploadedImagesData');
    uploadedImagesData.value = JSON.stringify(imagesData);
    closeImageModal();
}