$(document).ready(function () {
    paginationProccessing();
    ajaxProccessingStage();

    // send on click or on change
    $('.btn-filters-search').on('click', function (e) {
        e.preventDefault();
        ajaxProccessingStage();
    })

    $('.btn-filters-delete').on('click', function (e) {
        $('#medicament-search').val('');
        ajaxProccessingStage();
    })

    $('.items-per-page-select').on('change', function () {
        paginationProccessing(1);
        ajaxProccessingStage();
    })
});

// Send request with ajax
function ajaxProccessingStage() {
    $.ajax({
        url: '/medic/vizualizare-medicamente-json',
        dataType: 'json',
        data: getData(),
        beforeSend: function () {
            $('body').append('<div class="loader"></div>')
            $('#medicamente-view-table tbody').html('');
            $('.pagination').html('');
            $('.number-of-results').html('');
        },
        success: function (data) {
            // On success refresh table
            $('.loader').remove()
            $('#medicamente-view-table-view-table tbody').html('');
            tableTemplate(data['medicamente'], data['offset'], data['maxResult'], data['offset']);
            paginationTemplate(parseInt(data['pagina']), parseInt(data['numberOfPages']), parseInt(data['numberOfRows']), data['offset'], data['medicamente'].length);
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
        'medicament': $('#medicament-search').val() !== null ? $('#medicament-search').val() : '',
    };
    data['itemi'] = $('.items-per-page-select option:selected').val();
    data['pagina'] = searchParams.get('pagina');
    return data;
}

// When user click on pagination, change url
function paginationProccessing(page=null) {
    let url = new URL(window.location.href);
    let searchParams = new URLSearchParams(url.search);
    if(searchParams.get('pagina') === null || searchParams.get('pagina') === '' || page !== null) {
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

// Generate table with medicaments
function tableTemplate(medicamente, offset) {
    let html = ``;
    $.each(medicamente, function( index, medicament ) {
        html += `<tr>
                        <td>${offset + index + 1}</td>
                        <td>${medicament['denumire']}</td>
                        <td>`
        html += `
                <a href="/medic/actualizare-medicament/${medicament['id']}" class="btn-edit"><i class="fas fa-edit"></i></a>`;
        if(isGranted === true) {
            html += `<a class="btn-delete" onclick="deleteMedicament('${medicament['id']}')"><i class="fas fa-trash"></i></a>`;
        }
        html += `</td>
                    </tr>`;
    });
    $('#medicamente-view-table').children('tbody').append(html);
}