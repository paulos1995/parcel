import categorySelectAjaxize from "./categorySelectAjaxize";
import newParcelFormsAjaxize from "./newParcelFormsAjaxize";
import rebind from './rebind';
import makeUnikeFormFieldIds from './makeUnikeFormFieldIds';

//
export default function addParcelforms(numberOfForms) {
    console.log("addParcelforms("+numberOfForms+")");
    for (var nf = 0; nf < numberOfForms; nf++) {
        console.log(parcelhubParcelForms);
        parcelhubParcelForms.push(parcelhubFormNumber);
        let html = $("#template_form").html();
        html = makeUnikeFormFieldIds(html, parcelhubFormNumber);
        //console.log(html);
        var koddod = "<div id=\"ifp" + parcelhubFormNumber + "\" data-form-number=\"" + parcelhubFormNumber + "\"  class=\"parcelDiv\">" + html + "</div>";
        $("#forforms").append(koddod);
        categorySelectAjaxize(parcelhubFormNumber);
        newParcelFormsAjaxize();
        parcelhubFormNumber++;
    }
    rebind();
}
