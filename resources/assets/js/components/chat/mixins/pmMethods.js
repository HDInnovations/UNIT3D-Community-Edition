export default {
  methods: {
    pmUser (user) {
      if (user.id === this.$parent.auth.id) {
        return false
      }

      swal({
        title: `Send Private Message to ${user.username}`,
        input: 'textarea',
        width: '800px',
        height: '600px',
        inputAttributes: {
          autocapitalize: 'off'
        },

        showCancelButton: true,
        confirmButtonText: 'Send',
        showLoaderOnConfirm: true,

        onOpen: () => {
          this.editor = $('.swal2-textarea').wysibb({})
        },

        onClose: () => {
          this.editor = null
        },

        preConfirm: (msg) => {

          msg = this.editor.bbcode().trim()

          if (msg !== null && msg !== '') {

            this.$emit('pm-sent', {
              message: msg,
              save: true,
              user_id: this.$parent.auth.id,
              receiver_id: user.id
            })

            $('.wysibb-body').html('')
          }

          return user

        },

        allowOutsideClick: false

      }).then(result => {
        // console.log(result)

        if (result.value) {
          swal({
            title: `Sent Private Message to ${result.value.username}`,
            timer: 1500,
            onOpen: () => {
              swal.showLoading()
            }
          }).then((result) => {
            if (result.dismiss === swal.DismissReason.timer) {

            }
          })
        }
      })
    },
  }
}