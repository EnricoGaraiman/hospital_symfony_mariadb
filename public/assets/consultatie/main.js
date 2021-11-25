$('#consultatie_form_pacient').select2({
    delay: 100,
    allowClear: true,
    placeholder: 'Selectează un pacient',
    width: '100%',
    minimumInputLength: 1,
    ajax: {
        url: '/medic/pacienti-consultatie',
        processResults: function (data) {
            return {
                results: data
            };
        },
        data: function (params) {
            return {
                search: params.term,
                type: 'public'
            }
        }
    }
})

$('#consultatie_form_medicament').select2({
    delay: 100,
    allowClear: true,
    placeholder: 'Selectează un medicament',
    width: '100%',
    minimumInputLength: 1,
    ajax: {
        url: '/medic/medicamente-consultatie',
        processResults: function (data) {
            return {
                results: data
            };
        },
        data: function (params) {
            return {
                search: params.term,
                type: 'public'
            }
        }
    }
})