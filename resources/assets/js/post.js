$(document).ready( function() {
    var postType;
    $('#postToFacebookImageButton').on('click', function(event){
        postType='image';
    });
    $('#postToFacebookGifButton').on('click', function(event){
        postType='gif';
    });
    $('#formPostOnFacebook').on('submit', function(event) {
        event.preventDefault();
        document.getElementById("postModalAlertPlaceholder").classList.remove('hidden');
        $('#postModalAlertPlaceholder').html(
            '<div class="alert alert-warning" role="alert">' +
            '<span><span class="alert-title">Sharing...</span> ' +
            '</span>' +
            '</div>'
        )
        $("#beforePostModal").animate({ scrollTop: 0 }, "slow");
        $("#postToFacebookCloseBtn").prop('disabled', true);
        var formData = {
            '_token': $('input[name="_token"]').val(),
            message: $('textarea[name="message"]').val(),
            data: canvas.toDataURL(),
            gif: getGifDataURL(),
            postToFacebook: $('input:checked[name="postToFacebook"]').val() != null
        }
        /***
         * POST to
         * - request authorization to post from user
         * - store png and gif in backend and storage
         *
         **/
        $.ajax({
            type     : "POST",
            url      : $(this).attr('action'),
            data     : formData,
            cache    : false,

            success  : function(response) {
                document.getElementById("postModalAlertPlaceholder").classList.remove('hidden');
                // Authorization
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
                                '<div class="alert alert-info" role="alert">' +
                                '<a class="close" data-dismiss="alert">&times;</a>' +
                                '<span><span class="alert-title">Facebook authorization completed.</span> ' +
                                'Please click the share button again to share.</a>' +
                                '</span>' +
                                '</div>'
                            )
                        } else {
                            $('#postModalAlertPlaceholder').html(
                                '<div class="alert alert-danger" role="alert">' +
                                '<a class="close" data-dismiss="alert">&times;</a>' +
                                '<span>' +
                                'Please authorize the app to post on your Facebook.</a>' +
                                '</span>' +
                                '</div>'
                            )
                            $("html, body").animate({ scrollTop: 0 }, "slow");
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
                            '<div class="alert alert-info" role="alert">' +
                            '<a class="close" data-dismiss="alert">&times;</a>' +
                            '<span><span class="alert-title">Post to your wall!</span> ' +
                            'Add this link so your friend can come and check out your post: ' +
                            '<a href="'+response.data.url+'">'+response.data.url+'</a>' +
                            '</span>' +
                            '</div>'
                        )
                        FB.ui({
                            method: 'feed',
                            link: postType == "image" ? response.data.pngUrl : response.data.gifUrl,
                            name: 'SCRIBL',
                            caption: 'SCRIBL',
                            description: 'Post to your wall! ' +
                            'Add this link so your friend can come and check out your post: ' +
                            response.data.url
                            //method: 'share',
                            //href: response.data.url,
                        }, function(response2){
                            var htmlString = '<div class="alert alert-success" role="alert">' +
                                '<a class="close" data-dismiss="alert">&times;</a>' +
                                '<span><span class="alert-title">Post completed!</span> Check your post at ' +
                                '<a href="'+response.data.url+'">'+response.data.url+'</a>';
                            if (response.data.fbId == null) {
                                htmlString +='</span></div>'
                            } else {
                                htmlString += ' and ' +
                                    '<a href="http://facebook.com/'+response.data.fbId+'">'+'http://facebook.com/'+response.data.fbId+'</a>' +
                                    '</span>' +
                                    '</div>'
                            }

                            $('#postModalAlertPlaceholder').html(htmlString)

                    });

                }
            },
            complete : function () {
                $("#postToFacebookCloseBtn").prop('disabled', false);
            }

        })
    });

});