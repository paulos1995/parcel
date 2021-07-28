import userSelectAjaxize from "./userSelectAjaxize";
import categoryFilterSelectAjaxize from "./categoryFilterSelectAjaxize";
import addParcelforms from "./addParcelforms";
import newParcelFormsAjaxize from "./newParcelFormsAjaxize";
import submitAllParcelForms from "./submitAllParcelForms";

var parcelhubSendingAllParcelForms = false;
global.parcelhubSendingAllParcelForms = parcelhubSendingAllParcelForms;

// rebind
export default function rebind() {

    $('select.select2').select2({
        theme: 'bootstrap4',
    });

    userSelectAjaxize();
    categoryFilterSelectAjaxize();
    /*
     * Hacky fix for a bug in select2 with jQuery 3.6.0's new nested-focus "protection"
     * see: https://github.com/select2/select2/issues/5993
     * see: https://github.com/jquery/jquery/issues/4382
     *
     * TODO: Recheck with the select2 GH issue and remove once this is fixed on their side
     */

    $(document).on("select2:open", () => {
        document.querySelector(".select2-container--open .select2-search__field").focus()
    })


    $(function () {
        $("table.sortable").tablesorter({
            theme: "bootstrap"
        });
    });

    $('form.deleteForm').off();
    $("form.deleteForm").submit(function (event) {
        var confirmed = confirm('Are you sure you want to delete this item?');
        if (!confirmed) event.preventDefault();
        return confirmed;
    });

    $('.oneMoreForm').off().on("click", function (event, errorMessage) {
        addParcelforms(1);
    });
    $('.fiveMoreForms').off().on("click", function (event, errorMessage) {
        addParcelforms(5);
    });

    $('.submitAllForms').off().on("click", function (event, errorMessage) {
        submitAllParcelForms();
        $('.oneMoreForm').hide();
        $('.fiveMoreForms').hide();
    });


    newParcelFormsAjaxize();


    $(".custom-file-input").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

}
