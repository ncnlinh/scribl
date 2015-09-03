$(document).ready( function() {

    $('#formPostOnFacebook').on('submit', function(event) {
        event.preventDefault();
        //.....
        //show some spinner etc to indicate operation in progress
        //.....
        var formData = {
            '_token': $('input[name="_token"]').val(),
            message: $('textarea[name="message"]').val(),
            data: canvas.toDataURL()
        }

        $.ajax({
            type     : "POST",
            url      : $(this).attr('action'),
            data     : formData,
            cache    : false,

            success  : function(response) {
                if ((response &&
                    response.success == false &&
                    response.error.code == "500" &&
                    response.error.facebookErrorCode &&
                    response.error.facebookErrorCode == "200") ||
                    (response &&
                        response.success == false &&
                        response.error.code == "405" && //not allowed
                        response.error.message == "need_authorization_publish_actions"
                    )) {
                    FB.login(function (response) {
                        if (response.authResponse) {
                            $('#postModalAlertPlaceholder').html(
                                '<div class="alert alert-warning" role="alert">' +
                                '<a class="close" data-dismiss="alert">&times;</a>' +
                                '<span><span class="alert-title">Facebook authorization completed.</span> ' +
                                'Please click the share button again to share.</a>' +
                                '</span>' +
                                '</div>'
                            )
                        } else {
                            $('#postModalAlertPlaceholder').html(
                                '<div class="alert alert-warning" role="alert">' +
                                '<a class="close" data-dismiss="alert">&times;</a>' +
                                '<span>' +
                                'Please authorize the app to post on your Facebook.</a>' +
                                '</span>' +
                                '</div>'
                            )
                        }
                    }, {scope: 'publish_actions'});
                }

                if (response &&
                    response.success == false &&
                    response.error.code == "403" &&
                    response.error.message == "Token mismatch") {
                    $('#postModalAlertPlaceholder').html(
                        '<div class="alert alert-danger" role="alert">' +
                        '<a class="close" data-dismiss="alert">&times;</a>' +
                        '<span><span class="alert-title">Session expired.</span> ' +
                        'Sorry for this, but you\'ll have to <a href="/">refresh the page.</a>' +
                        '</span>' +
                        '</div>'
                    )
                }

                if (response &&
                    response.success == true) {
                    $('#postModalAlertPlaceholder').html(
                        '<div class="alert alert-success" role="alert">' +
                            '<a class="close" data-dismiss="alert">&times;</a>' +
                            '<span><span class="alert-title">Post completed!</span> Check your post at ' +
                                '<a href="'+response.data.url+'">'+response.data.url+'</a> and ' +
                                '<a href="http://facebook.com/'+response.data.fbId+'">'+'http://facebook.com/'+response.data.fbId+'</a>' +
                            '</span>' +
                        '</div>'
                    )
                }
            }
        })
    });

});