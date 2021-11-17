$(document).ready(function() {
    $('#edit_medic_form_administrator').select2({
        width: '100%'
    });
});

$(document).ready(function() {
    $('#add_medic_form_administrator').select2({
        width: '100%'
    });
});

function deleteMedic(id) {
    Swal.fire({
        title: 'Ești sigur că dorești să ștergi acest medic?',
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
                url: '/medic/stergere/' + id,
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