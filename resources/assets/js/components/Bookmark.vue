<template>
    <!-- wrap your templates is this fashion -->
    <div id="bookmark-component">

        <!-- we really don't even need 2 of these because we can use logic in the html -->
        <!-- pay special attention the the : before the props (very important) -->
        <button @click.prevent="bookmarked ? unBookmark(id) : bookmark(id)"
                :class="['btn', bookmarked ? 'btn-danger' : 'btn-primary']">
            <i class="fa fa-fw fa-bookmark-o"></i> {{ bookmarked ? 'Unbookmark' : 'Bookmark'}}
        </button>

        <!--<a href="#" v-else @click.prevent="bookmark(id)" class="btn btn-labeled btn-primary" role="button">
            <span class="btn-label"><i class="fa fa-fw fa-bookmark-o"></i></span>{{ trans('torrent.bookmark') }}
        </a>-->
    </div>
</template>

<script>
  // DON'T FORGET TO IMPORT YOUR Swal
  import Swal from 'sweetalert2'

  export default {
    // you could create a endpoint also to get the "state" using axios.get(`/torrent/${id}/bookmarked`)
    // then you would be able to remove the "state" prop
    props: ['id', 'state'],

    data: function () {
      return {
        // we just give dynamic data a default value as it will be updated by state prop
        bookmarked: null,
      }
    },

    mounted () {
      // we dont have to do inline logic if state already is either true/false
      this.bookmarked = this.state
    },

    // we don't need this if we can check the variable itself right ?
    // computed: {
    //   isBookmarked() {
    //     return this.bookmarked;
    //   },
    // },

    methods: {
      bookmark (id) {
        // The better way to post data is by passing the params through the request and not the url
        // like axios.post(URL, ['id': id]).then(...)

        //axios.post('/torrents/bookmark/' + id)
        // I like to use "template strings" when including vars in strings like this:
        axios.post(`/torrents/bookmark/${id}`)

        // this is all wrong
        // .then(response => { this.isBookmarked = true})
        // try and be consistent with this as it makes it easier to read
          .then((response) => {
            this.bookmarked = true

            Swal({
              position: 'top-end',
              type: 'success',
              title: 'Torrent Has Been Bookmarked Successfully!',
              showConfirmButton: false,
              timer: 4500
            })

          }).catch((error) => {
          console.log(error.response.data)
        })
      }
    },

    unBookmark (id) {
      axios.post('/torrents/unbookmark/' + id)
        .then((response) => {
          this.bookmarked = true

          Swal({
            position: 'top-end',
            type: 'success',
            title: 'Torrent Has Been Unbookmarked Successfully!',
            showConfirmButton: false,
            timer: 4500
          })

        }).catch((error) => {
        console.log(error.response.data)
      })
    },
  }
</script>