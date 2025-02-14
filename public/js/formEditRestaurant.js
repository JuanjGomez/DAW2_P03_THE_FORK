document.onload = function() {
    const fileInput = document.getElementById('file');
    const previewImage = document.getElementById('previewImage');

    fileInput.onchange = function() {
        const file = this.files[0];

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function() {
                previewImage.setAttribute('src', this.result);
            };

            reader.readAsDataURL(file);
        }
    };
};
