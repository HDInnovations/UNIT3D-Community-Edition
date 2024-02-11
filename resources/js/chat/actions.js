import axios from "axios";
import { state } from "./state";
import { each, find, reverse } from "lodash";



export function loadUser() {
  axios.get('/api/chat/user')
    .then(d => {
      state.user = d.data
      state.auth = state.user
      state.activeRoom = state.auth.chatroom.name;
      fetchRooms();
      fetchBots();
      fetchStatuses();
      fetchAudibles();
      fetchEchoes();
      listenForChatter();
      attachAudible();
    })
}
export function isTyping(e) {
  if (state.tab !== 'userlist') {
    if (state.target < 1 && state.channel && state.tab !== '') {
      state.channel.whisper('typing', {
        username: e.username,
      });
    }
  }
}
export function changeAudible(typeVal, targetVal, newVal) {
  if (typeVal === 'room') {
    let currentRoom = find(state.audibles, (o) => {
      if (o.room && o.room.id && o.room.id === targetVal) {
        return o.room.id;
      }
    });
    if (currentRoom) {
      let i = currentRoom.room.id;
      toggleAudible('room', i, newVal);
    }
  } else if (typeVal === 'target') {
    let currentTarget = find(state.audibles, (o) => {
      if (o.target && o.target.id && o.target.id === targetVal) {
        return o.target.id;
      }
    });
    if (currentTarget) {
      let i = currentTarget.target.id;
      toggleAudible('target', i, newVal);
    }
  } else if (typeVal === 'bot') {
    let currentBot = find(state.audibles, (o) => {
      if (o.bot && o.bot.id && o.bot.id === targetVal) {
        return o.bot.id;
      }
    });
    if (currentBot) {
      let i = currentBot.bot.id;
      toggleAudible('bot', i, newVal);
    }
  }
}
export function changeTab(typeVal, newVal) {
  if (typeVal === 'room') {
    state.bot = 0;
    state.target = 0;
    state.bot_id = 0;
    state.receiver_id = 0;
    state.tab = newVal;
    state.activeTab = 'room' + newVal;
    state.activeRoom = newVal;
    deletePing('room', newVal);
    let currentRoom = find(state.echoes, (o) => {
      if (o.room && o.room.id && o.room.id === newVal) {
        return o.room.id;
      }
    });
    if (currentRoom) {
      let i = currentRoom.room.id;
      changeRoom(i);
      state.receiver_id = null;
      state.bot_id = null;
    }

    let currentAudio = find(state.audibles, (o) => {
      if (o.room && o.room.id && o.room.id === newVal) {
        return o.id;
      }
    });
    if (currentAudio) {
      if (currentAudio.status === 1) {
        state.listening = 1;
      } else {
        state.listening = 0;
      }
    } else {
    }
  } else if (typeVal === 'target') {
    state.bot = 0;
    state.tab = newVal;
    state.activeTab = 'target' + newVal;
    state.activeTarget = newVal;
    deletePing('target', newVal);
    let currentTarget = find(state.echoes, (o) => {
      if (o.target && o.target.username && o.target.id === newVal) {
        return o.target.id;
      }
    });
    if (currentTarget) {
      let i = currentTarget.target.id;
      changeTarget(i);
      state.receiver_id = i;
      state.bot_id = null;
    }

    let currentAudio = find(state.audibles, (o) => {
      if (o.target && o.target.username && o.target.id === newVal) {
        return o.id;
      }
    });
    if (currentAudio) {
      if (currentAudio.status === 1) {
        state.listening = 1;
      } else {
        state.listening = 0;
      }
    } else {
    }
  } else if (typeVal === 'bot') {
    state.target = 0;
    state.tab = newVal;
    state.activeTab = 'bot' + newVal;
    state.activeBot = newVal;
    deletePing('bot', newVal);
    let currentBot = find(state.echoes, (o) => {
      if (o.bot && o.bot.name && o.bot.id === newVal) {
        return o.bot.id;
      }
    });
    if (currentBot) {
      let i = currentBot.bot.id;
      state.botName = currentBot.bot.name;
      state.botCommand = currentBot.bot.command;
      state.botId = currentBot.bot.id;
      changeBot(i);
      state.receiver_id = 1;
      state.bot_id = i;
    }

    let currentAudio = find(state.audibles, (o) => {
      if (o.bot && o.bot.name && o.bot.id === newVal) {
        return o.id;
      }
    });
    if (currentAudio) {
      if (currentAudio.status === 1) {
        state.listening = 1;
      } else {
        state.listening = 0;
      }
    } else {
    }
  } else if (typeVal === 'list') {
    state.tab = newVal;
  }
}
export function fetchAudibles() {
  axios.get('/api/chat/audibles').then((response) => {
    state.audibles = response.data.data;
    let currentAudio = find(state.audibles, (o) => {
      if (o.room && o.room.id && o.room.id === 1) {
        return o.id;
      }
    });
    if (currentAudio) {
      if (currentAudio.status === 1) {
        state.listening = 1;
      } else {
        state.listening = 0;
      }
    } else {
    }
    fetchConfiguration();
  });
}

export function fetchEchoes() {
  axios.get('/api/chat/echoes')
    .then((response) => {
    state.echoes = sortEchoes(response.data.data);
    state.boot = 1;
  });
}
export function fetchBots() {
  axios.get('/api/chat/bots')
    .then((response) => {
    state.bots = response.data.data;
    state.helpId = response.data.data[0].id;
    state.helpName = response.data.data[0].name;
    state.helpCommand = response.data.data[0].command;
  });
}
export function fetchRooms() {
  axios.get('/api/chat/rooms')
    .then((response) => {
      state.chatrooms = response.data.data;
      state.room = response.data.data[0].id;
      state.tab = response.data.data[0].name;
      state.activeTab = 'room' + state.room;
      fetchConfiguration();
      fetchMessages()
  });
}
export function fetchConfiguration() {
  axios.get(`/api/chat/config`).then((response) => {
    state.config = response.data;
  });
}
export function fetchBotMessages(id) {
  axios.get(`/api/chat/bot/${id}`).then((response) => {
    state.messages = reverse(response.data.data);
    state.connecting = false;
  });
}
export function fetchPrivateMessages() {
  axios.get(`/api/chat/private/messages/${state.target}`).then((response) => {
    state.messages = reverse(response.data.data);
    state.connecting = false;
  });
}
export function fetchMessages() {
  axios.get(`/api/chat/messages/${state.room}`).then((response) => {
    state.messages = reverse(response.data.data);
    state.connecting = false;
  });
}
export function fetchStatuses() {
  axios.get('/api/chat/statuses').then((response) => {
    state.statuses = response.data;
  });
}

export function forceMessage(name) {
  document.getElementById('chatbox__messages-create').value = '/msg ' + name + ' ';
}
export function  forceGift(name) {
  document.getElementById('chatbox__messages-create').value = '/gift ' + name + ' ';
}
export function leaveBot(id) {
  if (id > 0) {
    state.bot = 0;
    state.botName = '';
    state.botId = '';
    /* Update the users bot in the database */
    axios
      .post(`/api/chat/echoes/delete/bot`, {
        bot_id: id,
      })
      .then((response) => {
        // reassign the auth variable to the response data
        state.auth = response.data;
        state.selectedRoom = 1
        fetchRooms();
      });
  }
}
export function toggleAudible(type, id, nv) {
  if (id !== 0) {
    if (type === 'room') {
      axios
        .post(`/api/chat/audibles/toggle/chatroom`, {
          room_id: id,
          nv: nv,
        })
        .then((response) => {
          // reassign the auth variable to the response data
          state.auth = response.data;
          state.listening = nv;
        });
    } else if (type === 'target') {
      axios
        .post(`/api/chat/audibles/toggle/target`, {
          target_id: id,
          nv: nv,
        })
        .then((response) => {
          // reassign the auth variable to the response data
          state.auth = response.data;
          state.listening = nv;
        });
    } else if (type === 'bot') {
      axios
        .post(`/api/chat/audibles/toggle/bot`, {
          bot_id: id,
          nv: nv,
        })
        .then((response) => {
          // reassign the auth variable to the response data
          state.auth = response.data;
          state.listening = nv;
        });
    }
  }
}
export function leaveRoom(id) {
  if (id !== 1) {
    /* Update the users chatroom in the database */
    axios
      .post(`/api/chat/echoes/delete/chatroom`, {
        room_id: id,
      })
      .then((response) => {
        // reassign the auth variable to the response data
        state.auth = response.data;
        state.selectedRoom = 1
        fetchRooms();
      });
  }
}
export function leaveTarget(id) {
  if (id !== 1) {
    state.target = 0;
    /* Update the users chatroom in the database */
    axios
      .post(`/api/chat/echoes/delete/target`, {
        target_id: id,
      })
      .then((response) => {
        // reassign the auth variable to the response data
        state.auth = response.data;
        state.selectedRoom = 1;
        fetchRooms();
      });
  }
}

export function changeWhispers() {
  state.showWhispers = !state.showWhispers
}
export function changeStatus(status_id) {
  state.status = status_id;
  state.showStatuses = false;
  if (state.auth.chat_status.id !== status_id) {
    /* Update the users chat status in the database */
    axios
      .post(`/api/chat/user/status`, {
        status_id: status_id,
      })
      .then((response) => {
        // reassign the auth variable to the response data
        state.auth = response.data;
      });
  }
}
export function changeRoom(id) {
  state.forced = false;
  state.frozen = false;
  state.bot = 0;
  state.target = 0;
  state.room = id;
  state.bot_id = null;
  state.receiver_id = null;
  if (state.auth.chatroom.id === id) {
    state.tab = state.auth.chatroom.name;
    state.activeRoom = state.auth.chatroom.name;
    fetchMessages();
  } else {
    state.room = id;
    /* Update the users chatroom in the database */
    axios
      .post(`/api/chat/user/chatroom`, {
        room_id: id,
      })
      .then((response) => {
        // reassign the auth variable to the response data
        state.auth = response.data;
        state.tab = state.auth.chatroom.name;
        state.activeRoom = state.auth.chatroom.name;
        fetchMessages();
      });
  }
}
export function changeTarget(id) {
  state.forced = false;
  state.frozen = false;
  if (state.target !== id && id !== 0) {
    state.target = id;
    fetchPrivateMessages();
  }
}
export function changeBot(id) {
  state.forced = false;
  state.frozen = false;
  if (state.bot !== id && id !== 0) {
    state.bot = id;
    state.bot_id = id;
    state.receiver_id = 1;
    fetchBotMessages(state.bot);
  }
}
export function sortEchoes(obj) {
  return obj.sort(function (a, b) {
    let nv1 = '';
    if (a.type === 'room') {
      nv1 = a.name;
    }
    if (a.type === 'target') {
      nv1 = a.username;
    }
    if (a.type === 'bot') {
      nv1 = a.name;
    }
    const nv2 = '';
    if (b.type === 'room') {
      nv1 = b.name;
    }
    if (b.type === 'target') {
      nv1 = b.username;
    }
    if (b.type === 'bot') {
      nv1 = b.name;
    }
    return nv1 - nv2;
  });
}

export function startBot() {
  state.forced = false;
  if (state.bot === 9999) {
  } else {
    state.tab = '@' + state.helpName;
    state.bot = state.helpId;
    state.bot_id = state.helpId;
    state.receiver_id = 1;

    state.botId = state.helpId;
    state.botName = state.helpName;
    state.botCommand = state.helpCommand;

    fetchBotMessages(state.bot);
  }
}
export function playSound() {
  if (window.sounds && window.sounds.hasOwnProperty('alert.mp3')) {
    window.sounds['alert.mp3'].pause;
    window.sounds['alert.mp3'].position = 0;
    window.sounds['alert.mp3'].play();
  }
}
export function handleSound(type, id) {
  let i;
  let audioState = document.getElementById('chatbody').getAttribute('audio');
  if (type === 'room') {
    for (i = 0; i < state.audibles.length; i++) {
      if (
        state.audibles[i].room != null &&
        parseInt(state.audibles[i].status) === 1 &&
        parseInt(state.audibles[i].room.id) === parseInt(id)
      ) {
        if (state.activeTab === 'room' + id && audioState === 'true') {
          playSound();
        } else if (state.activeTab !== 'room' + id) {
          playSound();
        }
      }
    }
  }
  if (type === 'target') {
    for (i = 0; i < state.audibles.length; i++) {
      if (
        state.audibles[i].target != null &&
        parseInt(state.audibles[i].status) === 1 &&
        parseInt(state.audibles[i].target.id) === parseInt(id)
      ) {
        if (state.activeTab === 'target' + id && audioState === 'true') {
          playSound();
        } else if (state.activeTab !== 'target' + id) {
          playSound();
        }
      }
    }
  }
  if (type === 'bot') {
    for (i = 0; i < state.audibles.length; i++) {
      if (
        state.audibles[i].bot != null &&
        parseInt(state.audibles[i].status) === 1 &&
        parseInt(state.audibles[i].bot.id) === parseInt(id)
      ) {
        if (state.activeTab === 'bot' + id && audioState === 'true') {
          playSound();
        } else if (state.activeTab !== 'bot' + id) {
          playSound();
        }
      }
    }
  }
}
export function handleMessage(type, id, message) {
  let i;
  let audioState = document.getElementById('chatbody').getAttribute('audio');
  if (type === 'room') {
    for (i = 0; i < state.audibles.length; i++) {
      if (
        state.audibles[i].room != null &&
        parseInt(state.audibles[i].status) === 1 &&
        parseInt(state.audibles[i].room.id) === parseInt(id)
      ) {
        if (audioState === 'true') {
          playSound();
        } else if (state.activeTab !== 'room' + id) {
          playSound();
        }
      }
    }
  }
  if (type === 'target') {
    for (i = 0; i < state.audibles.length; i++) {
      if (
        state.audibles[i].target != null &&
        parseInt(state.audibles[i].status) === 1 &&
        parseInt(state.audibles[i].target.id) === parseInt(id)
      ) {
        if (audioState === 'true') {
          playSound();
        } else if (state.activeTab !== 'target' + id) {
          playSound();
        }
      }
    }
  }
  if (type === 'bot') {
    for (i = 0; i < state.audibles.length; i++) {
      if (
        state.audibles[i].bot != null &&
        parseInt(state.audibles[i].status) === 1 &&
        parseInt(state.audibles[i].bot.id) === parseInt(id)
      ) {
        if (audioState === 'true') {
          playSound();
        } else if (state.activeTab !== 'bot' + id) {
          playSound();
        }
      }
    }
  }
}
export function handlePing(type, id) {
  let match;
  let i;
  if (type === 'room') {
    match = false;
    for (i = 0; i < state.pings.length; i++) {
      if (
        state.pings[i].hasOwnProperty('type') &&
        state.pings[i].type === 'room' &&
        state.pings[i].id === id
      ) {
        match = true;
      }
    }
  }
  if (type === 'target') {
    match = false;
    for (i = 0; i < state.pings.length; i++) {
      if (
        state.pings[i].hasOwnProperty('type') &&
        state.pings[i].type === 'target' &&
        state.pings[i].id === id
      ) {
        match = true;
      }
    }
    if (match !== true) {
      let addon = [];
      addon['type'] = 'target';
      addon['id'] = id;
      addon['count'] = 0;
      state.pings.push(addon);
    }
    handleSound('target', id);
  }
  if (type === 'bot') {
    match = false;
    for (i = 0; i < state.pings.length; i++) {
      if (state.pings[i].hasOwnProperty('type') && state.pings[i].type === 'bot' && state.pings[i].id === id) {
        match = true;
      }
    }
    if (match !== true) {
      let addon = [];
      addon['type'] = 'bot';
      addon['id'] = id;
      addon['count'] = 0;
      state.pings.push(addon);
    }
    handleSound('bot', id);
  }
}
export function deletePing(type, id) {
  for (let i = 0; i < state.pings.length; i++) {
    if (state.pings[i].type === type && state.pings[i].id === id) {
      state.pings.splice(i, 1);
    }
  }
  return false;
}
export function checkPings(type, id) {
  if (type === 'room') {
    let currentRoom = find(state.pings, (o) => {
      if (o.type === 'room' && o.id === id) {
        return o.id;
      }
    });
    if (currentRoom) {
      return true;
    }
  } else if (type === 'target') {
    let currentTarget = find(state.pings, (o) => {
      if (o.type === 'target' && o.id === id) {
        return o.id;
      }
    });
    if (currentTarget) {
      return true;
    }
  } else if (type === 'bot') {
    let currentBot = find(state.pings, (o) => {
      if (o.type === 'bot' && o.id === id) {
        return o.id;
      }
    });
    if (currentBot) {
      return true;
    }
  }
  return false;
}
/* User defaults to System user */
export function createMessage(message, save = true, user_id = 1, receiver_id = null, bot_id = null) {
  // Prevent Users abusing BBCode size
  const regex = new RegExp(/\[size=[0-9]{3,}\]/);
  if (regex.test(message) === true) return;
  if (state.tab === 'userlist') return;
  axios
    .post('/api/chat/messages', {
      user_id: user_id,
      receiver_id: receiver_id,
      bot_id: bot_id,
      chatroom_id: state.room,
      message: message,
      save: save,
      targeted: state.target,
    })
    .then((response) => {
      if (state.activeTab.substring(0, 3) === 'bot' || state.activeTab.substring(0, 6) === 'target') {
        state.messages.push(response.data.data);
      }
      if (state.messages.length > state.config.message_limit) {
        each(state.messages, (m, i) => {
          if (state.target > 0) {
            if (m.receiver && m.receiver > 0) {
              state.messages.splice(i, 1);
              return false;
            }
          } else {
            if (!m.receiver || m.receiver === 0) {
              state.messages.splice(i, 1);
              return false;
            }
          }
        });
      }
    });
}
export function listenForChatter() {
  state.chatter = window.Echo.private(`chatter.${state.auth.id}`);
  state.chatter.listen('Chatter', (e) => {
    if (e.type === 'echo') {
      state.echoes = sortEchoes(e.echoes);
    } else if (e.type === 'audible') {
      state.audibles = e.audibles;
    } else if (e.type === 'new.message') {
      if (state.activeTab.substring(0, 3) !== 'bot' && state.activeTab.substring(0, 6) !== 'target')
        return false;
      if (e.message.bot && e.message.bot.id !== state.bot) return false;
      if (e.message.target && e.message.target.id !== state.target) return false;
      state.messages.push(e.message);
      if (state.bot && state.bot > 0) {
        handleMessage('bot', state.bot, e.message);
      } else {
        handleMessage('target', state.target, e.message);
      }
    } else if (e.type === 'new.bot') {
      state.messages.push(e.message);
      handleMessage('bot', state.bot);
    } else if (e.type === 'new.ping') {
      if (e.ping.type === 'bot') {
        handlePing('bot', e.ping.id);
      } else {
        handlePing('target', e.ping.id);
      }
    } else if (e.type === 'delete.message') {
      if (state.target < 1 && state.bot < 1) return false;
      let msgs = state.messages;
      let index = msgs.findIndex((msg) => msg.id === e.message.id);
      state.messages.splice(index, 1);
    } else if (e.type === 'typing') {
      if (state.target < 1) return false;
      if (state.activePeer === false) {
        state.activePeer = e.username;
      }
      setTimeout(() => {
        state.activePeer = false;
      }, 15000);
    }
  });
}
export function listenForEvents() {
  state.channel
    .here((users) => {
      state.connecting = false;
      state.users = users;
    })
    .listen('.new.message', (e) => {
      if (state.activeTab.substring(0, 4) !== 'room') return false;
      state.messages.push(e.message);
      handleMessage('room', state.room, e.message);
    })
    .listen('.new.ping', (e) => {
      handlePing('room', e.ping.id);
    })
    .listen('.edit.message', (e) => {})
    .listen('.delete.message', (e) => {
      if (state.target > 0 || state.bot > 0) return false;
      let msgs = state.messages;
      let index = msgs.findIndex((msg) => msg.id === e.message.id);
      state.messages.splice(index, 1);
    })
    .listenForWhisper('typing', (e) => {
      if (state.target > 0 || state.bot > 0) return false;
      if (state.activePeer === false) {
        state.activePeer = e;
      }
      setTimeout(() => {
        state.activePeer = false;
      }, 15000);
    });
}
export function attachAudible() {
  window.addEventListener('blur', function () {
    state.audio = true
  });
  window.addEventListener('focus', function () {
    state.audio = false
  });
}