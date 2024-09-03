document.addEventListener('alpine:init', () => {
    Alpine.data('likeButton', (postId, initialLikes) => ({
        postId: postId,
        likesCount: initialLikes,
        like() {
            axios
                .post(`/api/posts/${this.postId}/like`)
                .then((response) => {
                    const data = response.data;
                    if (data.success) {
                        this.likesCount = data.likesCount;
                        Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                        }).fire({
                            icon: 'success',
                            title: 'Your like was successfully applied!',
                        });
                        this.$dispatch('like-updated', {
                            postId: this.postId,
                            likesCount: this.likesCount,
                        });
                    }
                })
                .catch((error) => {
                    Swal.fire({
                        title: '<strong style="color: rgb(17,17,17);">Error</strong>',
                        icon: 'error',
                        html: error.response.data.message || error.message,
                        showCloseButton: true,
                    });
                });
        },
    }));
});
