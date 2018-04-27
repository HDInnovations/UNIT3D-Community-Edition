<template>
    <div class="text-center">
        <button @click="checkUpdate()" class="btn btn-primary btn-lg">
            <i v-if="loading" class="fa fa-circle-o-notch fa-spin"></i> {{ loading ? 'Loading...' : 'Check For Update' }}
        </button>
    </div>
</template>

<script>
  import Swal from 'sweetalert2'

  export default {

    data() {
      return {
        loading: false,
      }
    },

    methods: {
      checkUpdate() {
        this.loading = true;
        axios.get('/staff_dashboard/check-update')
          .then((response) => {
            if (response.data.updated === false) {
              this.loading = false;
              Swal({
                position: 'center',
                type: 'warning',
                title: 'There Is A Update Available!',
                showConfirmButton: false,
                timer: 4500
              })
            } else {
              this.loading = false;
              Swal({
                position: 'center',
                type: 'success',
                title: 'You Are Running The Latest Version Of UNIT3D!',
                showConfirmButton: false,
                timer: 4500
              })
            }
          })
          .catch((error) => {
            Swal('Oops...', error.response.data, 'error')
          })
      },
    }
  }
</script>