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
                console.log("NTM");
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
                console.log("NTM");
            },
            error: function() {
                $('#content').text('Une erreur s\'est produite, veuillez recharger la page');
            }
        });
    }
};


function check(input,input2) {
    if (input.value != input2.value) {
        input.setCustomValidity('Les champs doivent être identiques');
    } else {
        input.setCustomValidity('');
    }
}

$(document).ready(function(){
});