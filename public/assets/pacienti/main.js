$(document).ready(function() {
    $('#pacient_profile_form_asigurare').select2({
        width: '100%'
    });
});

$(document).ready(function() {
    $('#edit_pacient_form_asigurare').select2({
        width: '100%'
    });
});

$(document).ready(function() {
    $('#add_pacient_form_asigurare').select2({
        width: '100%'
    });
});

$(document).ready(function() {
    $('#pacienti_filters_asigurare').select2({
        width: '100%',
        placeholder: 'Filtrează după existența asigurării'
    });
});

$(document).ready(function() {
    $('#pacienti_filters_pacienti_medic').select2({
        width: '100%',
        // placeholder: 'Toți pacienții / Doar pacienții mei'
    });
});

function deletePacient(id) {
    Swal.fire({
        title: 'Ești sigur că dorești să ștergi acest pacient?',
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
                url: '/medic/stergere-pacient/' + id,
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