export default function parcelFormValidation(jform) {
    let valid = true;
    let titleVal = jform.find('.ltr_title').first().val();
    console.log('ltr_title obj first:');
    console.log(jform.find('.ltr_title').first());
    if (!titleVal) {
        console.log('titleVal: ' + titleVal)
        jform.find('.ltr_title').first().addClass('is-invalid');
        valid = false;
    }

    let categoryVal = jform.find('.ltr_category').first().val();
    if (!categoryVal) {
        jform.find('.ltr_category').first().addClass('is-invalid');
        valid = false;
    }
    $( ".is-invalid" ).focus(function() {
        console.log( "focus on input.is-invalid1" );
        console.log(this);
        $(this).removeClass('is-invalid');
    });
    return valid;
}
