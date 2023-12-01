import Swal from 'sweetalert2';

export default {
    methods: {
        pmUser(user) {
            if (user.id === this.$parent.auth.id) {
                return false;
            }
            Swal.fire({
                title: `Send Private Message to ${user.username}`,
                width: '800px',
                height: '600px',
                inputAttributes: {
                    autocapitalize: 'off',
                },
                html:
                    '<div class="text-left">' +
                    '<input type="hidden" id="receiver-id" name="receiver-id" value="' +
                    user.id +
                    '">\n' +
                    '<textarea id="chat-message-pm" name="message-pm" placeholder="Write your message..." cols="30" rows="5"></textarea>' +
                    '</div>',
                showCancelButton: true,
                confirmButtonText: 'Send',
                showLoaderOnConfirm: true,
                willOpen: () => {
                    this.editor = document.getElementById('chat-message-pm').value;
                    this.target = document.getElementById('receiver-id').value;
                },
                willClose: () => {
                    this.editor = null;
                    this.target = null;
                },
                preConfirm: (msg) => {
                    let target = this.target;
                    msg = this.input.value = this.input.value.trim();
                    if (msg !== null && msg !== '') {
                        this.$emit('pm-sent', {
                            message: msg,
                            save: true,
                            user_id: this.$parent.auth.id,
                            receiver_id: target,
                        });
                        document.getElementById('chat-message-pm').value = '';
                    }
                    return user;
                },
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        title: `Sent Private Message to ${result.value.username}`,
                        timer: 1500,
                        onOpen: () => {
                            Swal.showLoading();
                        },
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                        }
                    });
                }
            });
        },
    },
};
