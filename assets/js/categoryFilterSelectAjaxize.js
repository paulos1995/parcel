import formatcategoryOption from './formatcategoryOption';
import formatcategorySelection from './formatcategorySelection';

export default function categoryFilterSelectAjaxize()
{
    console.log("categoryFilterSelectAjaxize()");
    $('select.category-ajax-filter-select').select2({
        theme: 'bootstrap4',
        ajax: {
            url: '/kadmin/category/find.json',
            dataType: 'json',
            delay: 250 // milliseconds
        },
        minimumInputLength: 2,
        placeholder: "",
        allowClear: true,
        templateResult: formatCategoryOption,
        templateSelection: formatCategorySelection
    });
}