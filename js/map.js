function moveplayer(direction) {
    mapPopup("move");
    var xhr = new XMLHttpRequest();
    xhr.open('POST', './php/game/move_player.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    var data = 'direction=' + encodeURIComponent(direction);
    xhr.send(data);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            setTimeout(function () {
                loadmain('./php/game/game.php');
            }, 280);
        }
    }
}


function getColor(id) {
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
            if (j > 0 && checkHauteurDifference(currentTd, currentRow.cells[j - 1])) {
                currentRow.cells[j - 1].style.borderRight = borderStyle;
                currentTd.style.borderLeft = borderStyle;
            }
            if (j < currentRow.cells.length - 1 && checkHauteurDifference(currentTd, currentRow.cells[j + 1])) {
                currentRow.cells[j + 1].style.borderLeft = borderStyle;
                currentTd.style.borderRight = borderStyle;
            }
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

function mapPopup(mode = "keep") {
    var popupDiv = document.getElementById("map-popup");
    var targetElement = document.getElementById("map");

    if (!popupDiv && mode == "keep") {
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
                targetElement.insertAdjacentElement("afterend", popupDiv);
                adjustMap("mini");
                setTimeout(function () {
                    popupDiv.classList.add("flip-in");
                    targetElement.classList.add("flip-out");
                }, 10);
            }
        };
    } else if (popupDiv) {
        if (popupDiv.classList.contains("flip-in")) {
            popupDiv.classList.remove("flip-in");
            targetElement.classList.remove("flip-out");
        } else {
            popupDiv.classList.add("flip-in");
            targetElement.classList.add("flip-out");
        }
    }
}

function adjustMap(mode) {
    const tableId = mode === 'mini' ? 'minimap' : 'map';
    const tdElements = document.querySelectorAll(`#${tableId} td`);
    console.log(tdElements);
    for (let i = 0; i < tdElements.length; i++) {
        const tdElement = tdElements[i];
        const id = parseInt(tdElement.getAttribute('biome'));
        const height = parseInt(tdElement.getAttribute('hauteur'));
        let color = height == 0 ? 'ocean' : getColor(id, 'biome');
        const heightMultiplier = mode === 'mini' ? 2 : 4;
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