<template>
    <div class="text-center">
        <button @click="checkUpdate()" class="btn btn-primary btn-lg">Check For Update</button>
    </div>
</template>

<script>
    import Swal from 'sweetalert2'

    export default {
        methods: {
            checkUpdate() {
                axios.get('/staff_dashboard/check-update')
                    .then((response) => {
                        if (response.data.updated === false) {
                            Swal({
                                position: 'top-end',
                                type: 'warning',
                                title: 'There is a update available.',
                                showConfirmButton: false,
                                timer: 3500
                            })
                        } else {
                            Swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'You are completely updated!',
                                showConfirmButton: false,
                                timer: 3500
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