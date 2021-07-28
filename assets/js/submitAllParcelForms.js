import submitNextParcelForm from "./submitNextParcelForm";

export default function submitAllParcelForms() {

    parcelhubParcelFormsToSubmit = [...parcelhubParcelForms];
    if (parcelhubParcelFormsToSubmit.length>0) {
        console.log("parcelhubParcelFormsToSubmit: "+parcelhubParcelFormsToSubmit);
        parcelhubSendingAllParcelForms = true;
        submitNextParcelForm();
    }
}
