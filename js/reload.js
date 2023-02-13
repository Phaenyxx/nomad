function loadmain(_url){
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
}