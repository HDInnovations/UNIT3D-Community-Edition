<template>
    <button
        @click="bookmarked ? unBookmark(id) : bookmark(id)"
        :class="['btn', bookmarked ? 'btn-md btn-danger' : 'btn-md btn-primary']"
    >
        <i class="fal fa-bookmark"></i> {{ bookmarked ? 'Unbookmark' : 'Bookmark' }}
    </button>
</template>

<script>
import Swal from 'sweetalert2';

export default {
    // you could create a endpoint also to get the "state" using axios.get(`/torrent/${id}/bookmarked`)
    // then you would be able to remove the "state" prop
    props: ['id', 'state'],

    data() {
        return {
            bookmarked: 0,
        };
    },

    mounted() {
        this.bookmarked = this.state;
    },

    methods: {
        bookmark(id) {
            axios
                .post('/bookmarks/' + id)
                .then(response => {
                    this.bookmarked = true;

                    Swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Torrent Has Been Bookmarked Successfully!',
                        showConfirmButton: false,
                        timer: 4500,
                    });
                })
                .catch(error => {
                    console.log(error.response.data);
                });
        },

        unBookmark(id) {
            axios
                .delete('/bookmarks/' + id)
                .then(response => {
                    this.bookmarked = false;

                    Swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Torrent Has Been Unbookmarked Successfully!',
                        showConfirmButton: false,
                        timer: 4500,
                    });
                })
                .catch(error => {
                    console.log(error.response.data);
                });
        },
    },
};
</script>
