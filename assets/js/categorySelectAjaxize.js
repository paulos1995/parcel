import formatCategoryOption from './formatCategoryOption';
import formatCategorySelection from './formatCategorySelection';

export default function categorySelectAjaxize(parcelhubFormNumber)
{
    console.log("categorySelectAjaxize("+parcelhubFormNumber+")");
    $('#ifp' + parcelhubFormNumber + ' select.category-ajax-select').select2({
        theme: 'bootstrap4',
        ajax: {
            url: '/kadmin/category/find.json',
            dataType: 'json',
            delay: 250 // milliseconds
        },
        minimumInputLength: 2,
        templateResult: formatCategoryOption,
        templateSelection: formatCategorySelection
    });
}