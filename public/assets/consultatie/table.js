$(document).ready(function () {
    paginationProccessing();
    ajaxProccessingStage();

    // send on click or on change
    $('.btn-filters-search').on('click', function (e) {
        e.preventDefault();
        ajaxProccessingStage();
    })

    $('.btn-filters-delete').on('click', function (e) {
        $('#consultatii_filters_medic').val('').trigger("change");
        $('#consultatii_filters_pacient').val('').trigger("change");
        $('#consultatii_filters_medicament').val('').trigger("change");
        $('#consultatii_filters_data1').val('');
        $('#consultatii_filters_data2').val('');
        ajaxProccessingStage();
    })

    $('.items-per-page-select').on('change', function () {
        ajaxProccessingStage();
    })
});

// Send request with ajax
function ajaxProccessingStage() {
    $.ajax({
        url: '/medic/vizualizare-consultatii-json',
        dataType: 'json',
        data: getData(),
        beforeSend: function () {
            $('body').append('<div class="loader"></div>')
            $('#consultatii-view-table tbody').html('');
            $('.pagination').html('');
            $('.number-of-results').html('');
        },
        success: function (data) {
            // On success refresh table
            $('.loader').remove()
            $('#consultatii-view-table tbody').html('');
            tableTemplate(data['consultatii'], data['offset'], data['maxResult'], data['offset']);
            paginationTemplate(parseInt(data['pagina']), parseInt(data['numberOfPages']), parseInt(data['numberOfRows']), data['offset'], data['consultatii'].length);
            paginationProccessing();
        },
        error: function (jqXhr, textStatus, errorMessage) {
            console.log('Error: ' + errorMessage);
        }
    });
}

// Get data from request (filters, number of page, number of items per page)
function getData() {
    let url = new URL(window.location.href);
    let searchParams = new URLSearchParams(url.search);
    let data = {};
    data['filtre'] = {
        'medic': $('#consultatii_filters_medic').val() !== null ? $('#consultatii_filters_medic').val() : '',
        'pacient': $('#consultatii_filters_pacient').val() !== null ? $('#consultatii_filters_pacient').val() : '',
        'medicament': $('#consultatii_filters_medicament').val() !== null ? $('#consultatii_filters_medicament').val() : '',
        'data1': $('#consultatii_filters_data1').val() !== null ? $('#consultatii_filters_data1').val() : '',
        'data2': $('#consultatii_filters_data2').val() !== null ? $('#consultatii_filters_data2').val() : '',
    };
    data['itemi'] = $('.items-per-page-select option:selected').val();
    data['pagina'] = searchParams.get('pagina');
    return data;
}

// When user click on pagination, change url
function paginationProccessing() {
    let url = new URL(window.location.href);
    let searchParams = new URLSearchParams(url.search);
    if(searchParams.get('pagina') === null || searchParams.get('pagina') === '') {
        searchParams.set('pagina', 1);
        history.pushState(null, null, "?"+searchParams.toString());
    }
    $('.page-item').on('click', function () {
        searchParams.set('pagina', $(this).children().attr('value'));
        history.pushState(null, null, "?"+searchParams.toString());
        ajaxProccessingStage();
    });
}

// Dinamic pagination
function paginationTemplate(page, numberOfPages, numberOfRows, offset, numberOfResult) {
    let html = ``;
    if (page > 1) {
        html += `<li class="page-item">
                <a class="page-link" aria-label="Previous" value="${page-1}">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>`
    }
    let i;
    for (i = 1; i <= numberOfPages; i = i + 1) {
        if(page === i) {
            html += `<li class="page-item active"><a class="page-link" value="${i}">${i}</a></li>`
        }
        else {
            html += `<li class="page-item"><a class="page-link" value="${i}">${i}</a></li>`
        }
    }
    if(page < numberOfPages) {
        html += `<li class="page-item">
                <a class="page-link" aria-label="Next" value="${page+1}">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>`
    }
    $('.pagination').append(html);
    if(numberOfRows > 0) {
        $('.number-of-results').append(`Listare de la ${offset + 1} la ${offset + numberOfResult} dintr-un total de ${numberOfRows} rezultate.`);
    }
    else {
        $('.number-of-results').append(`Niciun rezultat`);
    }
}

// Generate table with users
function tableTemplate(consultatii, offset) {
    let html = ``;
    $.each(consultatii, function( index, consultatie ) {
        html += `<tr>
                        <td>${offset + index + 1}</td>
                        <td>${moment(consultatie['data']['timestamp'] * 1000).format('DD/MM/YYYY')}</td>
                        <td>${consultatie['medic']['prenumeMedic']} ${consultatie['medic']['numeMedic']}</td>
                        <td>${consultatie['pacient']['prenumePacient']} ${consultatie['pacient']['numePacient']}</td>
                        <td>`
        if(consultatie['medicament'] !== null) {
            html += `${consultatie['medicament']['denumire']}`;
        }
        else {
            html += `<span class="badge rounded-pill bg-warning">Nu există</span>`;
        }
        html += `</td><td>`
        if(consultatie['dozaMedicament'] !== null) {
            html += `${consultatie['dozaMedicament']}`;
        }
        else {
            html += `<span class="badge rounded-pill bg-warning">Nu există</span>`;
        }
        html += `</td><td>`
        if(consultatie['diagnostic'] !== '') {
            html += `${consultatie['diagnostic']}`;
        }
        else {
            html += `<span class="badge rounded-pill bg-warning">Nespecificat</span>`;
        }
        html += `</td><td>`
        html += `
                <a href="/medic/vizualizare-consultatie/${consultatie['id']}" class="btn-view"><i class="fas fa-eye"></i></a>
                <a href="/medic/actualizare-consultatie/${consultatie['id']}" class="btn-edit"><i class="fas fa-edit"></i></a>`;
        if(isGranted === true) {
            html += `<a class="btn-delete" onclick="deleteConsultatie('${consultatie['id']}')"><i class="fas fa-trash"></i></a>`;
        }
        html += `</td>
                    </tr>`;
    });
    $('#consultatii-view-table').children('tbody').append(html);
}