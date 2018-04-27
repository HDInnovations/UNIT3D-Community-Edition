<template>
    <div id="bookmark-component">
        <button @click.prevent="bookmarked ? unBookmark(id) : bookmark(id)"
                :class="['btn', bookmarked ? 'btn-danger' : 'btn-primary']">
            <i class="fa fa-fw fa-bookmark-o"></i> {{ bookmarked ? 'Unbookmark' : 'Bookmark'}}
        </button>
    </div>
</template>

<script>
  import Swal from 'sweetalert2'

  export default {
    // you could create a endpoint also to get the "state" using axios.get(`/torrent/${id}/bookmarked`)
    // then you would be able to remove the "state" prop
    props: ['id', 'state'],

    data() {
      return {
        bookmarked: null,
      }
    },

    mounted() {
      this.bookmarked = this.state
    },

    methods: {

      bookmark(id) {
        axios.post('/torrents/bookmark/' + id)
          .then((response) => {
            this.bookmarked = true;

            Swal({
              position: 'top-end',
              type: 'success',
              title: 'Torrent Has Been Bookmarked Successfully!',
              showConfirmButton: false,
              timer: 4500
            })
          })
          .catch((error) => {
            console.log(error.response.data)
          });
      },

      unBookmark(id) {
        axios.post('/torrents/unbookmark/' + id)
          .then((response) => {
            this.bookmarked = true;

            Swal({
              position: 'top-end',
              type: 'success',
              title: 'Torrent Has Been Unbookmarked Successfully!',
              showConfirmButton: false,
              timer: 4500
            })
          })
          .catch((error) => {
            console.log(error.response.data)
          });

      },

    }
  };
</script>