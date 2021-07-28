export default function makeUnikeFormFieldIds(html, parcelhubFormNumber) {

        html = html.replaceAll('parcel__token', 'parcel__token' + parcelhubFormNumber);
        html = html.replaceAll('parcel_category', 'parcel_category' + parcelhubFormNumber);
        html = html.replaceAll('parcel_file', 'parcel_File' + parcelhubFormNumber);
        html = html.replaceAll('parcel_status', 'parcel_status' + parcelhubFormNumber);
        html = html.replaceAll('parcel_title', 'parcel_title' + parcelhubFormNumber);
        html = html.replaceAll('parcel_barcodeNumber', 'parcel_barcodeNumber' + parcelhubFormNumber);
        return html;

}
