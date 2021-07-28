// formatcategoryOption

export default function (categoryInfo) {
    if (categoryInfo.loading) {
        return categoryInfo.text;
    }

    var $container = $(
        "<div class='sel-row'>" +
        " <span class='select2-result-repository__title'></span>" +
        " <span class='select2-result-repository__scan badge'></span>" +
        " <span class='select2-result-repository__location badge badge-info'></span>" +
        "</div>"
    );

    $container.find(".select2-result-repository__title").text(categoryInfo.name);
    if (categoryInfo.scan) {
        $container.find(".select2-result-repository__scan").text('scan');
    }
    if (categoryInfo.scan) $container.find(".select2-result-repository__scan").addClass('badge-success');
    else  $container.find(".select2-result-repository__scan").addClass('badge-secondary');

    $container.find(".select2-result-repository__location").text(categoryInfo.locationName);

    return $container;
}