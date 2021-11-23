
function deleteMedicament(id) {
    Swal.fire({
        title: 'Ești sigur că dorești să ștergi acest medicament?',
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
                url: '/medic/stergere-medicament/' + id,
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