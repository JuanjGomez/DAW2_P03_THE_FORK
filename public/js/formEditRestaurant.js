const fileInput = document.getElementById('file');
const previewImage = document.getElementById('previewImage');

fileInput.onchange = function(e) {
    const file = e.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
        }
        reader.readAsDataURL(file);
    } else {
        previewImage.src = "/images/upload-icon.png";
    }
};
