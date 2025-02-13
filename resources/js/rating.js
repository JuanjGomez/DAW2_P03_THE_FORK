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

    // Manejar la eliminación de opiniones
    const deleteButtons = document.querySelectorAll('.delete-rating');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('¿Estás seguro de que quieres eliminar tu opinión?')) {
                const ratingId = this.dataset.id;
                fetch(`/rating/${ratingId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                });
            }
        });
    });
});