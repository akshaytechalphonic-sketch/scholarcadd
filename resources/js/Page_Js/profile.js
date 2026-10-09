document.addEventListener("DOMContentLoaded", function() {
    const imageUpload = document.getElementById("image-upload");
    const uploadBox = document.getElementById("upload-box");
    const imageContainer = document.getElementById("image-container");
    const profileImage = document.getElementById("profile-image");
    const iconUpload = document.getElementById("icon-upload");
    const deleteBtn = document.getElementById("delete-btn");
    const changePhotoBtn = document.getElementById("change-photo-btn");
    uploadBox.addEventListener("click", () => {
        if (profileImage.classList.contains('hidden')) {
            imageUpload.click();
        }
    });
    imageUpload.addEventListener("change", function(event) {
        const file = event.target.files[0];

        if (file) {
            if (!['image/jpeg', 'image/png'].includes(file.type)) {
                alert('Please select a valid image (JPG, JPEG, PNG).');
                return;
            }
            if (file.size > 1024 * 1024) { 
                alert('File size exceeds the 1MB limit.');
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                profileImage.src = e.target.result;
                profileImage.classList.remove('hidden');
                iconUpload.classList.add('hidden');
                deleteBtn.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
    deleteBtn.addEventListener("click", () => {
        resetImagePreview();
    });
    changePhotoBtn.addEventListener("click", () => {
        imageUpload.click(); 
    });
    function resetImagePreview() {
        profileImage.classList.add('hidden'); 
        iconUpload.classList.remove('hidden'); 
        deleteBtn.classList.add('hidden'); 
    }
});