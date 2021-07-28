import parcelFormValidation from './parcelFormValidation';

export default function submitNextParcelForm() {
    console.log('parcelhubParcelFormsToSubmit '+parcelhubParcelFormsToSubmit);
    if (parcelhubParcelFormsToSubmit.length > 0) {
        let firstFormNumber = parcelhubParcelFormsToSubmit.shift();
        let formDomLocation = "#ifp" + firstFormNumber + " form";
        let formAction = $(formDomLocation).attr('action');
        console.log(firstFormNumber+" A formAction: " + formAction);
        if (formAction.length > 1) {
            console.log("A sendingAllParcelForms: " + parcelhubSendingAllParcelForms);
            if (parcelFormValidation($(formDomLocation))) {
                $(formDomLocation).submit();
            } else {
                console.log("invalid form " + formDomLocation);
                if (parcelhubSendingAllParcelForms) submitNextParcelForm();
            }

        } else {
            if (parcelhubSendingAllParcelForms) submitNextParcelForm();
        }


    } else {
        parcelhubSendingAllParcelForms = false;
    }
}

