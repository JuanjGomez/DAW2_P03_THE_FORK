document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.stars input');
    const form = document.getElementById('rating-form');

    stars.forEach((star) => {
        star.addEventListener('change', function() {
            form.submit();
        });
    });

    const commentInput = document.querySelector('.comment-input');
    commentInput.addEventListener('blur', function() {
        form.submit();
    });
});