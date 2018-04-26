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
                                title: 'There Is A Update Available!',
                                showConfirmButton: false,
                                timer: 4500
                            })
                        } else {
                            Swal({
                                position: 'top-end',
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