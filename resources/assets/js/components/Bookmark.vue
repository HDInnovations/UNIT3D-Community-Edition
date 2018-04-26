<template>
    <span>
        <a href="#" v-if="isBookmarked" @click.prevent="unBookmark(id)" class="btn btn-labeled btn-danger" role="button">
            <span class="btn-label"><i class="fa fa-fw fa-bookmark-o"></i></span>{{ trans('torrent.unbookmark') }}
        </a>
        <a href="#" v-else @click.prevent="bookmark(id)" class="btn btn-labeled btn-primary" role="button">
            <span class="btn-label"><i class="fa fa-fw fa-bookmark-o"></i></span>{{ trans('torrent.bookmark') }}
        </a>
    </span>
</template>

<script>
  export default {
    props: ['id', 'bookmarked'],

    data: function () {
      return {
        isBookmarked: '',
      }
    },

    mounted() {
      this.isBookmarked = this.isBookmarked ? true : false;
    },

    computed: {
      isBookmarked() {
        return this.bookmarked;
      },
    },

    methods: {
      bookmark(id) {
        axios.post('/torrents/bookmark/' + id)
          .then(response => this.isBookmarked = true)
        {
          Swal({
            position: 'top-end',
            type: 'success',
            title: 'Torrent Has Been Bookmarked Successfully!',
            showConfirmButton: false,
            timer: 4500
          })

            .catch(response => console.log(response.data));
        }
      },

      unBookmark(id) {
        axios.post('/torrents/unbookmark/' + id)
          .then(response => this.isBookmarked = false)
        {
          Swal({
            position: 'top-end',
            type: 'success',
            title: 'Torrent Has Been Unbookmarked Successfully!',
            showConfirmButton: false,
            timer: 4500
          })

            .catch(response => console.log(response.data));
        }
      },
    }
  }
</script>