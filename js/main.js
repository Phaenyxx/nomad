function loadmain(_url){
    $.ajax({
        url : _url,
        type : 'post',
        success: function(data) {
            console.log("Bip!");
            $('#content').html(data);
        },
        error: function() {
            $('#content').text('Une erreur s\'est produite, veuillez recharger la page');
        }
    });
}

function switch_form(type, _url){
    if (type == 'login') {
        $("#logbutton").prop("disabled", true);
        $("#regbutton").prop("disabled", false);
        $.ajax({
            url : _url,
            type : 'post',
            success: function(data) {
                $('#form_container').html(data);
            },
            error: function() {
                $('#content').text('Une erreur s\'est produite, veuillez recharger la page');
            }
        });
    }
    else if (type == 'register') {
        $("#logbutton").prop("disabled", false);
        $("#regbutton").prop("disabled", true);
        $.ajax({
            url : _url,
            type : 'post',
            success: function(data) {
                $('#form_container').html(data);
            },
            error: function() {
                $('#content').text('Une erreur s\'est produite, veuillez recharger la page');
            }
        });
    }
};

function print_message(input) {
    var msg = "<div id=\"message\">" + input + "</div>";
    $('#message-box').show().html(msg);
};

function check_match(input) {
    var input2;
    switch (input.name) {
        case 'email':
        input2 = $("#email-verif")[0];
        break;
        case 'email-verif':
        input2 = $("#email")[0];
        break;
        case 'password-verif':
        input2 = $("#password")[0];
        break;
        case 'password':
        input2 = $("#password-verif")[0];
        break;
    }
    
    if (input.value != input2.value) {
        input.setCustomValidity('Les champs doivent être identiques');
        input2.setCustomValidity('Les champs doivent être identiques')
    } else {
        input.setCustomValidity('');
        input2.setCustomValidity('');
    }
};

$(document).ready(function(){
});