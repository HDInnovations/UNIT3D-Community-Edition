var scollBox = $('.shoutbox');
var next_batch = null;
var forceScroll = true;
let messages = $('.chat-messages .list-group');

messages.animate({ scrollTop: messages.prop('scrollHeight') }, 0);

load_data = {
  'fetch': 1
};
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('input[name=_token]').val()
  }
});

messages.scroll(function() {
  forceScroll = false;
  let scrollTop = messages.scrollTop() + messages.prop('clientHeight');
  let scrollHeight = messages.prop('scrollHeight');
  forceScroll = scrollTop >= scrollHeight;
});

window.setInterval(function() {
  $('.chat-messages .list-group');
  $.ajax({
  url: "shoutbox/messages/" + parseInt(next_batch),
  type: 'get',
  data: load_data,
  dataType: 'json',
  success: function(data) {
    if (next_batch === null) {
      next_batch = data.next_batch;
    } else {
      next_batch = data.next_batch;
      data.data.forEach(function(h) {
        let message = $(h);
        messages.append(message);
      });
    }

    if (forceScroll) {
      messages.animate({ scrollTop: messages.prop('scrollHeight') }, 0);
    }
  }});
}, 3000);

$("#chat-message").keypress(function(evt) {
  if (evt.which == 13) {
    var message = $('#chat-message').val();
    post_data = {
      'message': message
    };
    $.ajax({
      url: "shoutbox/send",
      type: 'post',
      data: post_data,
      dataType: 'json',
      success: function(data) {
        forceScroll = true;
        $('#chat-error').addClass('hidden');
        $('#chat-message').removeClass('invalid');
        $('#chat-message').val('');
        messages.animate({
          scrollTop: messages.prop('scrollHeight')
        }, 0);
      },
      error: function(data) {
        $('#chat-message').addClass('invalid');
        $('#chat-error').removeClass('hidden');
        $('#chat-error').text('Whoops Im Currently Offline');
      }
    });
  }
});
