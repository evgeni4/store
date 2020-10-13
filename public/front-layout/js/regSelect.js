// $(document).ready(function(){

// });
var $countryId = $('#registration_form_country');
var $token = $('#registration_form__token');

$countryId.change(function (){
    $("#box-loader").addClass("box-loader");
    var $form = $(this).closest('form')
    var data = {}
     data [$token.attr('name')] = $token.val()
     data [$countryId.attr('name')] = $countryId.val()
    $.post($form.attr('action'), data).then(function (response){
        $("#registration_form_state").replaceWith(
            $(response).find("#registration_form_state")
        )
        $("#box-loader").removeClass("box-loader")
    })
})
