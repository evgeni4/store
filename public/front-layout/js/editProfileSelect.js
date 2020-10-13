
var $countryId = $('#user_edit_form_country');
var $token = $('#user_edit_form__token');

$countryId.change(function (){
    $("#box-loader").addClass("box-loader");
    var $form = $(this).closest('formEdit')
    var data = {}
     data [$token.attr('name')] = $token.val()
     data [$countryId.attr('name')] = $countryId.val()
    $.post($form.attr('action'), data).then(function (response){
        $("#user_edit_form_state").replaceWith(
            $(response).find("#user_edit_form_state")
        )
        $("#box-loader").removeClass("box-loader")
    })
})
