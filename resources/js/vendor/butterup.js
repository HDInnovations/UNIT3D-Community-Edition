var butterup = {
  options:{
    maxToasts: 5, // Max number of toasts that can be on the screen at once
    toastLife: 5000, // How long a toast will stay on the screen before fading away
    currentToasts: 0, // Current number of toasts on the screen
    stackedToasts: false // Whether to stack toasts
  },
  toast:function({title, message, type, location, icon, theme, customIcon, dismissable}){
    /* Check if the toaster exists. If it doesn't, create it. If it does, check if there are too many toasts on the screen.
    If there are too many, delete the oldest one and create a new one. If there aren't too many, create a new one. */
    if(document.getElementById('toaster') == null){
      // toaster doesn't exist, create it
      const toaster = document.createElement('div');
      toaster.id = 'toaster';
      if(location == null){
        toaster.className = 'toaster top-right';
      }else{
        toaster.className = 'toaster ' + location;
      }
      document.body.appendChild(toaster);

      // Create the toasting rack inside of the toaster
      if(document.getElementById('butterupRack') == null){
        const rack = document.createElement('ol');
        rack.id = 'butterupRack';
        rack.className = 'rack';
        toaster.appendChild(rack);
      }
    }else{
      const toaster = document.getElementById('toaster');
      // check what location the toaster is in
      toaster.classList.forEach(function(item){
        // remove any location classes from the toaster
        if(item.includes('top-right') || item.includes('top-center') || item.includes('top-left') || item.includes('bottom-right') || item.includes('bottom-center') || item.includes('bottom-left')){
          toaster.classList.remove(item);
        }
      });
      if(location == null){
        toaster.className = 'toaster top-right';
      }else{
        toaster.className = 'toaster ' + location;
      }
      const rack = document.getElementById('butterupRack');
    }

    // Check if there are too many toasts on the screen
    if(butterup.options.currentToasts >= butterup.options.maxToasts){
      // there are too many toasts on the screen, delete the oldest one
      var oldestToast = document.getElementById('butterupRack').firstChild;
      document.getElementById('butterupRack').removeChild(oldestToast);
      butterup.options.currentToasts--;
    }

    // Create the toast
    const toast = document.createElement('li');
    butterup.options.currentToasts++;
    toast.className = 'butteruptoast';
    // if the toast class contains a top or bottom location, add the appropriate class to the toast
    if(toaster.className.includes('top-right') || toaster.className.includes('top-center') || toaster.className.includes('top-left')){
      toast.className += ' toastDown';
    }
    if(toaster.className.includes('bottom-right') || toaster.className.includes('bottom-center') || toaster.className.includes('bottom-left')){
      toast.className += ' toastUp';
    }
    toast.id = 'butterupToast-' + butterup.options.currentToasts;
    if(type != null){
      toast.className += ' ' + type;
    }

    if(theme != null){
      toast.className += ' ' + theme;
    }

    // Add the toast to the rack
    document.getElementById('butterupRack').appendChild(toast);

    // check if the user wants an icon
    if(icon != null && icon == true){
      // add a div inside the toast with a class of icon
      const toastIcon = document.createElement('div');
      toastIcon.className = 'icon';
      toast.appendChild(toastIcon);
      if(type != null){
        // add the type class to the toast
        toast.className += ' ' + type;
        if(type == 'success'){
          toastIcon.innerHTML =
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">'+
            '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />'+
            '</svg>';
        }
        if(type == 'error'){
          toastIcon.innerHTML =
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">'+
            '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />'+
            '</svg>'
        }
        if(type == 'warning'){
          toastIcon.innerHTML =
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">'+
            '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />'+
            '</svg>'
        }
        if(type == 'info'){
          toastIcon.innerHTML =
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">'+
            '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />'+
            '</svg>'
        }
      }
    }

    // add a div inside the toast with a class of notif
    const toastNotif = document.createElement('div');
    toastNotif.className = 'notif';
    toast.appendChild(toastNotif);

    // add a div inside of notif with a class of desc
    const toastDesc = document.createElement('div');
    toastDesc.className = 'desc';
    toastNotif.appendChild(toastDesc);

    // check if the user added a title
    if(title != null){
      const toastTitle = document.createElement('div');
      toastTitle.className = 'title';
      toastTitle.innerHTML = title;
      toastDesc.appendChild(toastTitle);
    }

    // check if the user added a message
    if(message != null){
      const toastMessage = document.createElement('div');
      toastMessage.className = 'message';
      toastMessage.innerHTML = message;
      toastDesc.appendChild(toastMessage);
    }

    if(dismissable != null && dismissable == true){
      // Add a class to the toast to make it dismissable
      toast.className += ' dismissable';
      // when the item is clicked on, remove it from the DOM
      toast.addEventListener('click', function(){
        butterup.despawnToast(toast.id);
      });
    }

    // remove the entrance animation class after the animation has finished
    setTimeout(function(){
      toast.className = toast.className.replace(' toastDown', '');
      toast.className = toast.className.replace(' toastUp', '');
    }, 500);

    // despawn the toast after the specified time
    setTimeout(function(){
      butterup.despawnToast(toast.id);
    }, butterup.options.toastLife);

  },
  despawnToast(toastId){
    // fade out the toast and then remove it from the DOM
    var toast = document.getElementById(toastId);
    // does the toast exist?
    if(toast != null){
      toast.className += ' fadeOutToast';
      setTimeout(function(){
        // set the opacity to 0
        try{
          toast.style.opacity = '0';
          toast.parentNode.removeChild(toast);
          butterup.options.currentToasts--;
        }catch(e){
          // do nothing
        }
        // if this was the last toast on the screen, remove the toaster
        if(butterup.options.currentToasts == 0){
          var toaster = document.getElementById('toaster');
          toaster.parentNode.removeChild(toaster);
        }
      }, 500);
    }

  }
};
