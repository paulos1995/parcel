import categorySelectAjaxize from "./categorySelectAjaxize";
import rebind from './rebind';
import submitNextParcelForm from "./submitNextParcelForm";
import makeUnikeFormFieldIds from "./makeUnikeFormFieldIds";
import parcelFormValidation from "./parcelFormValidation";

// newParcelFormsAjaxize
export default function newParcelFormsAjaxize() {
    $(".parcelDiv form").off('submit').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var form = this;
        if (parcelFormValidation($(form))) {

            $(form).find('fieldset').prop("disabled", true);
            $(form).find('.btnSubmitNormal').hide();
            $(form).find('.btnSubmitInProgress').show();

            $.ajax({
                url: this.action,
                type: 'POST',
                data: formData,
                success: function (data) {
                    let prntdv = $(form).closest('div');
                    let thisFormNumber = prntdv.data('formNumber');
                    if (data.includes('form-signin') && data.includes('form-signin')) {
                        console.log('Session expired. 1');
                        $(form).find('fieldset').prop("disabled", false);
                        $(form).find('.btnSubmitNormal').show();
                        $(form).find('.btnSubmitInProgress').hide();
                        $(form).find('.btnSubmitSuccess').hide();

                        let statusTarget = $(form).find('div.statusDiv').first();
                        console.log(statusTarget);
                        statusTarget.html("<p class='error-text text-danger text-sm-center'>Session expired. Please <a href='/login' target='_blank'>log in in new window</a>.</p>");
                    } else {
                        console.log('ajax fail s2. on ' + thisFormNumber);
                        let html = makeUnikeFormFieldIds(data, thisFormNumber + 1000);
                        prntdv.html(html);
                        categorySelectAjaxize(thisFormNumber);
                        if (parcelhubSendingAllParcelForms) submitNextParcelForm();
                        rebind();
                    }
                },
                error: function (data) {
                    console.log('ajax error1.');
                    $(form).find('fieldset').prop("disabled", false);
                    $(form).find('.btnSubmitNormal').show();
                    $(form).find('.btnSubmitInProgress').hide();
                    $(form).find('.btnSubmitSuccess').hide();

                    let statusTarget = $(form).closest('div.statusDiv');
                    statusTarget.html("<p class='text-danger'>Submit failed.</p>");
                    if (parcelhubSendingAllParcelForms) submitNextParcelForm();
                },
                cache: false,
                contentType: false,
                processData: false
            });

        }

    });
}