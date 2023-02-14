function storageAvailable(type) {
    try {
        var storage = window[type],
            x = '__storage_test__';
        storage.setItem(x, x);
        storage.removeItem(x);
        return true;
    }
    catch(e) {
        return e instanceof DOMException && (
            // everything except Firefox
            e.code === 22 ||
            // Firefox
            e.code === 1014 ||
            // test name field too, because code might not be present
            // everything except Firefox
            e.name === 'QuotaExceededError' ||
            // Firefox
            e.name === 'NS_ERROR_DOM_QUOTA_REACHED') &&
            // acknowledge QuotaExceededError only if there's something already stored
            storage.length !== 0;
    }
}

function loadmain(_url){
    // if (storageAvailable('sessionStorage'))
    // {
    //     if ((_url === './html/game.php' || _url === './html/forums.php' || _url === './html/settings.html') && sessionStorage.getItem('logged_in') != true) {
    //         _url = './html/login.html';
    //     }
    // }
    $.ajax({
        url : _url,
        type : 'post',
        success: function(data) {
         $('#content').html(data);
        },
        error: function() {
         $('#content').text('Une erreur s\'est produite, veuillez recharger la page');
        }
    });
    if (storageAvailable('sessionStorage'))
    {
        sessionStorage.setItem('page_url', _url);
    }
}

function switch_form(type){
    if (type == 'login') {
        $("#logbutton").prop("disabled", true);
        $("#regbutton").prop("disabled", false);
        $("#login-form").show();
        $("#register-form").hide();
    }
    else if (type == 'register') {
        $("#logbutton").prop("disabled", false);
        $("#regbutton").prop("disabled", true);
        $("#login-form").hide();
        $("#register-form").show();
    }
};

$(document).ready(function(){
    var page_url = './html/accueil.html';
    if (storageAvailable('sessionStorage'))
    {
        if (sessionStorage.getItem('page_url') !== 'null') {
            page_url = sessionStorage.getItem('page_url');
        }
    }
    loadmain(page_url);
});