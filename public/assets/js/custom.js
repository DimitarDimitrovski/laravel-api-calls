const csrf = document.head.querySelector('meta[name="csrf-token"]');

$(document).ready(function() {
    $('.upload').on('click', function () {
        let fd = new FormData();
        let files = $('#file')[0].files;
        let uri = $(this).data('url');
        console.log(files);

        if(files.length > 0 ) {
            fd.append('file', files[0]);

            $.ajax({
                url: uri,
                type: 'POST',
                headers: {'X-CSRF-TOKEN': csrf.content},
                data: fd,
                processData: false,
                contentType: false,
                beforeSend: function() { $('#spinner').show(); },
                success: function(response) {
                    if(response.status === 'success') {
                        $('#spinner').hide();
                        $('.form-inline').append('<div class="alert alert-success" role="alert">' + response.message + '</div>');
                        $('.alert-success').delay(2500).fadeOut(400, function() {
                            $(this).remove();
                        });
                        location.reload();
                    } else {
                        $('#spinner').hide();
                        $('.form-inline').append('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
                        $('.alert-danger').delay(2500).fadeOut(400, function() {
                            $(this).remove();
                        });
                    }
                },
            });
        } else {
            alert("Please select a file.");
        }
    })

    $('body').on('click', '.delete-item', function () {
        let uri = $(this).data('url');
        let type = $(this).data('type');

        $.ajax({
            url: uri,
            method: 'DELETE',
            data: {
                _token: csrf.content,
                type: type
            },
            success: function (response) {
                if (response.status === 'success') {
                    $('.container #message').append('<div class="alert alert-success" role="alert">' + response.message + '</div>');
                    $('.alert-success').delay(2500).fadeOut(400, function() {
                        $(this).remove();
                    });
                    if(type === 'user_image') {
                        $('.kitty-grid').load(' .kitty-grid .kitty-container');
                    }
                    else if(type === 'favourite') {
                        $('.kitty-grid.favourites').load(' .kitty-grid .favourites-content');
                    } else {
                        $('.kitty-grid.votes').load(' .kitty-grid .votes-content');
                    }
                } else {
                    $('.container #message').append('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
                    $('.alert-success').delay(2500).fadeOut(400, function() {
                        $(this).remove();
                    });
                }
            }
        })
    })

    $('.random').on('click', function() {
        $('.kitty-grid.new').load(' .kitty-grid .new-kitties');
    })

    $('body').on('click', '.favourite-item', function() {
        let uri = $(this).data('url');

        $.ajax({
            url: uri,
            method: 'POST',
            data: {
                _token: csrf.content
            },
            success: function (response) {
                if (response.status === 'success') {
                    $('.container #message').append('<div class="alert alert-success" role="alert">' + response.message + '</div>');
                    $('.alert-success').delay(2500).fadeOut(400, function() {
                        $(this).remove();
                    });
                    $('.kitty-grid.favourites').load(' .kitty-grid .favourites-content');
                } else {
                    $('.container #message').append('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
                    $('.alert-danger').delay(2500).fadeOut(400, function() {
                        $(this).remove();
                    });
                }
            }
        })
    })

    $('body').on('click', '.vote', function() {
        let uri = $(this).data('url');
        let vote = 1;

        if($(this).hasClass('dislike')) {
            vote = 0;
        }

        $.ajax({
            url: uri,
            method: 'POST',
            data: {
                _token: csrf.content,
                vote: vote
            },
            success: function (response) {
                if (response.status === 'success') {
                    $('.container #message').append('<div class="alert alert-success" role="alert">' + response.message + '</div>');
                    $('.alert-success').delay(2500).fadeOut(400, function() {
                        $(this).remove();
                    });
                    $('.kitty-grid.votes').load(' .kitty-grid .votes-content');
                } else {
                    $('.container #message').append('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
                    $('.alert-danger').delay(2500).fadeOut(400, function() {
                        $(this).remove();
                    });
                }
            }
        })
    })
})
