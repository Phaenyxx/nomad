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

function moveplayer(direction) {
  var xhr = new XMLHttpRequest();
  xhr.open('POST', './php/game/move_player.php', true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  var data = 'direction=' + encodeURIComponent(direction);
  xhr.send(data);
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      loadmain('./php/game/game.php');
    }
  }
}


function getColor(id, type) {
  switch (type) {
    case 'biome':
      switch (id) {
        case 1:
          return 'green';
        case 2:
          return 'emerald';
        case 3:
          return 'green';
        case 4:
          return 'sand';
        case 5:
          return 'grey';
        default:
          return 'black';
      }
    case 'height':
      switch (id) {
        case 1:
          return 'red';
        case 2:
          return 'brick';
        case 3:
          return 'orange';
        case 4:
          return 'beige';
        case 5:
          return 'sand';
        default:
          return 'black';
      }
    default:
      return 'black';
  }
}

function checkHauteurDifference(td1, td2, threshold = 1) {
  var hauteur1 = parseInt(td1.getAttribute("hauteur"));
  var hauteur2 = parseInt(td2.getAttribute("hauteur"));

  return Math.abs(hauteur1 - hauteur2) > threshold;
}

function checkBorders(tableId) {
  var table = document.getElementById(tableId);
  var borderStyle = "";

  if (tableId === "map") {
    borderStyle = "3px dashed var(--nomad-dark)";
  } else if (tableId === "minimap") {
    borderStyle = "2px inset var(--nomad-red)";
  }


  for (var i = 1; i < table.rows.length; i++) {
    var currentRow = table.rows[i];

    for (var j = 0; j < currentRow.cells.length; j++) {
      var currentTd = currentRow.cells[j];

      // Check horizontal neighbors
      if (j > 0 && checkHauteurDifference(currentTd, currentRow.cells[j - 1])) {
        currentRow.cells[j - 1].style.borderRight = borderStyle;
        currentTd.style.borderLeft = borderStyle;
      }

      if (j < currentRow.cells.length - 1 && checkHauteurDifference(currentTd, currentRow.cells[j + 1])) {
        currentRow.cells[j + 1].style.borderLeft = borderStyle;
        currentTd.style.borderRight = borderStyle;
      }

      // Check vertical neighbors
      if (i > 1 && checkHauteurDifference(currentTd, table.rows[i - 1].cells[j])) {
        table.rows[i - 1].cells[j].style.borderBottom = borderStyle;
        currentTd.style.borderTop = borderStyle;
      }

      if (i < table.rows.length - 1 && checkHauteurDifference(currentTd, table.rows[i + 1].cells[j])) {
        table.rows[i + 1].cells[j].style.borderTop = borderStyle;
        currentTd.style.borderBottom = borderStyle;
      }
    }
  }
}

function mapPopup() {
  var popupDiv = document.getElementById("map-popup");

  if (!popupDiv) {
    var xhr = new XMLHttpRequest();
    var url = "./php/game/map.php";
    xhr.open("GET", url, true);
    xhr.send();
    xhr.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        var responseText = this.responseText;
        var popupDiv = document.createElement("div");
        popupDiv.setAttribute("id", "map-popup");
        popupDiv.innerHTML = responseText;
        document.body.appendChild(popupDiv);
        adjustMap("mini");
        popupDiv.classList.add("visible");
      }
    };
  } else {
    popupDiv.classList.remove("visible");
    setTimeout(function () {
      popupDiv.remove();
    }, 300);
  }
}

function adjustMap(mode) {
  const tableId = mode === 'mini' ? 'minimap' : 'map';
  const tdElements = document.querySelectorAll(`#${tableId} td`);
  console.log(tdElements);
  for (let i = 0; i < tdElements.length; i++) {
    const tdElement = tdElements[i];
    const id = parseInt(tdElement.getAttribute(mode === 'topo' ? 'hauteur' : 'biome'));
    const height = parseInt(tdElement.getAttribute('hauteur'));
    let color = height == 0 ? 'ocean' : getColor(id, mode === 'topo' ? 'height' : 'biome');
    const heightMultiplier = mode === 'topo' ? 4 : mode === 'mini' ? 2 : 4;
    const boxShadowValue = generateBoxShadowValue(height, heightMultiplier);
    const transformValue = generateTransformValue(height, heightMultiplier);
    tdElement.style.backgroundColor = `var(--nomad-${color})`;
    tdElement.style.transform = transformValue;
    tdElement.style.boxShadow = boxShadowValue;
  }
  checkBorders(tableId);
}

function generateBoxShadowValue(id, multiplier) {
  let boxShadowValue = '';
  for (let j = 1; j <= id * multiplier; j++) {
    const horizontalOffset = `${j}px`;
    const verticalOffset = `${j}px`;
    boxShadowValue += `${horizontalOffset} ${verticalOffset} 1px var(--nomad-black), `;
  }
  return boxShadowValue.slice(0, -2);
}

function generateTransformValue(id, heightMultiplier) {
  const translateValue = `${id * heightMultiplier * -1}px`;
  return `translate(${translateValue}, ${translateValue})`;
}

function hideMessageBox() {
  var messageBox = document.getElementById("message-box");
  messageBox.style.display = "none";
}

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

function print_message(input) {
  var msg = '<div id="message">' + input + '</div>';
  document.getElementById('message-box').style.display = 'block';
  document.getElementById('message-box').innerHTML = msg;
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