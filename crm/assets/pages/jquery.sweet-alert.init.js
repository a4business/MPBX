
/**
 * Theme: Adminto - Admin Dashboard
 * Author: Coderthemes
 * SweetAlert
 */

!function ($) {
    "use strict";

    var SweetAlert = function () {
    };

    //examples
    SweetAlert.prototype.init = function () {

        //Basic
        $('#sa-basic').on('click', function () {
            swal('Any fool can use a computer').catch(swal.noop)
        });

        //A title with a text under
        $('#sa-title').click(function () {
            swal(
                'The Internet?',
                'That thing is still around?',
                'question'
            )
        });

        //Success Message
        $('#sa-success').click(function () {
            swal(
                {
                    title: 'Good job!',
                    text: 'You clicked the button!',
                    type: 'success',
                    confirmButtonColor: '#4fa7f3'
                }
            )
        });

        //Warning Message
        $('#sa-warning').click(function () {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4fa7f3',
                cancelButtonColor: '#d57171',
                confirmButtonText: 'Yes, delete it!'
            }).then(function () {
                swal(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            })
        });

        //Parameter
        $('#sa-params').click(function () {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger m-l-10',
                buttonsStyling: false
            }).then(function () {
                swal(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                    )
                }
            })
        });

        //Custom Image
        $('#sa-image').click(function () {
            swal({
                title: 'Sweet!',
                text: 'Modal with a custom image.',
                imageUrl: 'assets/images/mail_confirm.png',
                imageHeight: 50,
                animation: false
            })
        });

        //Auto Close Timer
        $('#sa-close').click(function () {
            swal({
                title: 'Auto close alert!',
                text: 'I will close in 2 seconds.',
                timer: 2000
            }).then(
                function () {
                },
                // handling the promise rejection
                function (dismiss) {
                    if (dismiss === 'timer') {
                        console.log('I was closed by the timer')
                    }
                }
            )
        });

        //custom html alert
        $('#custom-html-alert').click(function () {
            swal({
                title: '<i>HTML</i> <u>example</u>',
                type: 'info',
                html: 'You can use <b>bold text</b>, ' +
                '<a href="//coderthemes.com/">links</a> ' +
                'and other HTML tags',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger m-l-10',
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!',
                cancelButtonText: '<i class="fa fa-thumbs-down"></i>'
            })
        });

        //Custom width padding
        $('#custom-padding-width-alert').click(function () {
            swal({
                title: 'Custom width, padding, background.',
                width: 600,
                padding: 100,
                background: '#fff url(//subtlepatterns2015.subtlepatterns.netdna-cdn.com/patterns/geometry.png)'
            })
        });

        //Ajax
        $('#ajax-alert').click(function () {
            swal({
                title: 'Submit email to run ajax request',
                input: 'email',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger m-l-10',
                preConfirm: function (email) {
                    return new Promise(function (resolve, reject) {
                        setTimeout(function () {
                            if (email === 'taken@example.com') {
                                reject('This email is already taken.')
                            } else {
                                resolve()
                            }
                        }, 2000)
                    })
                },
                allowOutsideClick: false
            }).then(function (email) {
                swal({
                    type: 'success',
                    title: 'Ajax request finished!',
                    html: 'Submitted email: ' + email
                })
            })
        });

        //chaining modal alert
        $('#chaining-alert').click(function () {
            swal.setDefaults({
                input: 'text',
                confirmButtonText: 'Next &rarr;',
                showCancelButton: true,
                animation: false,
                progressSteps: ['1', '2', '3']
            })

            var steps = [
                {
                    title: 'Question 1',
                    text: 'Chaining swal2 modals is easy'
                },
                'Question 2',
                'Question 3'
            ]

            swal.queue(steps).then(function (result) {
                swal.resetDefaults()
                swal({
                    title: 'All done!',
                    html: 'Your answers: <pre>' +
                    JSON.stringify(result) +
                    '</pre>',
                    confirmButtonText: 'Lovely!',
                    showCancelButton: false
                })
            }, function () {
                swal.resetDefaults()
            })
        });

        //Danger
        $('#dynamic-alert').click(function () {
            swal.queue([{
                title: 'Your public IP',
                confirmButtonText: 'Show my public IP',
                text: 'Your public IP will be received ' +
                'via AJAX request',
                showLoaderOnConfirm: true,
                preConfirm: function () {
                    return new Promise(function (resolve) {
                        $.get('https://api.ipify.org?format=json')
                            .done(function (data) {
                                swal.insertQueueStep(data.ip)
                                resolve()
                            })
                    })
                }
            }])
        });


    },
        //init
        $.SweetAlert = new SweetAlert, $.SweetAlert.Constructor = SweetAlert
}(window.jQuery),

//initializing
    function ($) {
        "use strict";
        $.SweetAlert.init()
    }(window.jQuery);