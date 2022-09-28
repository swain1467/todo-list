$(document).ready(function(){
    // Boostrap form validation
    $('#frmRegister').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        submitButtons: 'button[type="submit"]',
        fields: {
        	'txtFirstName': {validators: {
                notEmpty: {message: 'Required'},
                regexp: {
                    regexp: /^[A-Za-z_.-\s]+$/i,
                    message: 'Sorry,Invalid input!!!'
                }}},
            'txtLastName': {validators: {
                notEmpty: {message: 'Required'},
                regexp: {
                    regexp: /^[A-Za-z_.-\s]+$/i,
                    message: 'Sorry,Invalid input!!!'
                }}},
            'txtUserName': {validators: {
                notEmpty: {message: 'Required'},
                regexp: {
                    regexp: /^[A-Za-z0-9_.-\s]+$/i,
                    message: 'Sorry,Invalid input!!!'
                }}},
            'txtContactNo': {validators: {
                numeric: {message: 'Contact no be a mobile number'},
                stringLength: {
                    max: 12,
                    min: 10,
                    message: 'Contact no must between 10 to 12 integer'},
                notEmpty: {message: 'Required'}}},
            'txtEmailAddress':{validators: {
                email: {message: 'Must be a valid mail id'},
                notEmpty: {message: 'Required'}}},	
            'txtPassword': {validators: {notEmpty: {message: 'Required'}}},
        }
    });
});

