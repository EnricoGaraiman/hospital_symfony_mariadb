$('#consultatie_form_pacient, #consultatii_filters_pacient').select2({
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

$('#consultatie_form_medicament, #consultatii_filters_medicament').select2({
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

$('#consultatii_filters_medic').select2({
    delay: 100,
    allowClear: true,
    placeholder: 'Selectează un medic',
    width: '100%',
    minimumInputLength: 1,
    ajax: {
        url: '/medic/medici-consultatie',
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

function deleteConsultatie(id) {
    Swal.fire({
        title: 'Ești sigur că dorești să ștergi acestă consultație?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0c4079',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmă',
        cancelButtonText: 'Anulează',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/medic/stergere-consultatie/' + id,
                dataType: 'json',
                success: function (data) {
                    ajaxProccessingStage();
                    Swal.fire({
                        icon: data['type'],
                        title: data['message'],
                        showConfirmButton: false,
                        timer: 2000
                    })
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    console.log('Error: ' + errorMessage);
                }
            });
        }
    })
}