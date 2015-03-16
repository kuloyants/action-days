$(document).ready($(function() {
    $('#registerForm').trigger("reset");
    $("input,select").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function($form, event, errors) {
            // additional error messages or events
        },
        submitSuccess: function($form, event) {
            event.preventDefault(); // prevent default submit behaviour
            $.ajax({
                url: "?section=registration",
                type: "POST",
                data: $form.serialize(),
                cache: false,
                success: function(response) {
                    if ( "content" in response) {
                        $("#registration").replaceWith($(response.content));
                    }
                }
            })
        },
        filter: function() {
            return $(this).is(":visible");
        }
    });
}));


/*When clicking on Full hide fail/success boxes */
$('#name').focus(function() {
    $('#success').html('');
});
