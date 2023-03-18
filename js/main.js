function loadmain(url) {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', url, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      document.getElementById('content').innerHTML = xhr.responseText;
    } else {
      document.getElementById('content').textContent = 'Une erreur s\'est produite, veuillez recharger la page';
    }
    adjustMap("none");
  };
  xhr.onerror = function () {
    document.getElementById('content').textContent = 'Une erreur s\'est produite, veuillez recharger la page';
  };
  xhr.send();
};

function switch_form(type, url) {
  var logButton = document.getElementById('logbutton');
  var regButton = document.getElementById('regbutton');
  var formContainer = document.getElementById('form_container');

  if (type === 'login') {
    logButton.disabled = true;
    regButton.disabled = false;
  } else if (type === 'register') {
    logButton.disabled = false;
    regButton.disabled = true;
  }

  var xhr = new XMLHttpRequest();
  xhr.open('GET', url, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      formContainer.innerHTML = xhr.responseText;
    } else {
      document.getElementById('content').textContent = 'Une erreur s\'est produite, veuillez recharger la page';
    }
  };
  xhr.onerror = function () {
    document.getElementById('content').textContent = 'Une erreur s\'est produite, veuillez recharger la page';
  };
  xhr.send();
}

function print_message(messages) {
  console.log(messages);
  var msgs = '';
  for (var i = 0; i < messages.length; i++) {
    msgs += '<div id="message">' + messages[i] + '</div>';
  }

  document.getElementById('message-box').style.display = 'block';
  document.getElementById('message-box').innerHTML = msgs;
}

function hideMessageBox() {
  var messageBox = document.getElementById("message-box");
  messageBox.style.display = "none";
}

function check_match(input) {
  var input2;

  switch (input.name) {
    case 'email':
      input2 = document.getElementById('email-verif');
      break;
    case 'email-verif':
      input2 = document.getElementById('email');
      break;
    case 'password-verif':
      input2 = document.getElementById('password');
      break;
    case 'password':
      input2 = document.getElementById('password-verif');
      break;
  }

  if (input.value !== input2.value) {
    input.setCustomValidity('Les champs doivent être identiques');
    input2.setCustomValidity('Les champs doivent être identiques');
  } else {
    input.setCustomValidity('');
    input2.setCustomValidity('');
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const parentElement = document.getElementById("content");
  parentElement.addEventListener("click", function (event) {
    const toggleUploadButton = event.target.closest("#toggle-upload");
    if (toggleUploadButton) {
      const uploadInput = document.getElementById("upload-input");
      uploadInput.style.display = uploadInput.style.display === "none" ? "block" : "none";
      toggleUploadButton.textContent = uploadInput.style.display === "none" ? "Ajouter une image" : "Annuler";
    }
  });
});