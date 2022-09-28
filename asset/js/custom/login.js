$(document).ready(function(){
    // Boostrap form validation
    $('#frmLogin').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        submitButtons: 'button[type="submit"]',
        fields: {
            'txtUserName': {validators: {
                notEmpty: {message: 'Required'},
                regexp: {
                    regexp: /^[A-Za-z0-9_.-\s]+$/i,
                    message: 'Sorry,Invalid input!!!'
                }}},
            'txtPassword': {validators: {notEmpty: {message: 'Required'}}},
        }
    });
});

