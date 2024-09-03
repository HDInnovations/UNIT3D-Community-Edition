document.addEventListener('alpine:init', () => {
    Alpine.data('dislikeButton', (postId, initialDislikesCount) => ({
        postId: postId,
        dislikesCount: initialDislikesCount,
        dislike() {
            axios
                .post(`/api/posts/${this.postId}/dislike`)
                .then((response) => {
                    const data = response.data;
                    if (data.success) {
                        this.dislikesCount++;
                        Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                        }).fire({
                            icon: 'success',
                            title: 'Your dislike was successfully applied!',
                        });
                        this.$dispatch('dislike-updated', {
                            postId: this.postId,
                            dislikesCount: this.dislikesCount,
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
