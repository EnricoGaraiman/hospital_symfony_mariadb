$(document).ready(function () {
    // Sidebar
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

    // Sweet alert confirmation + errors
    if(alertType !== false) {
        Swal.fire({
            icon: alertType['type'],
            title: alertType['message'],
            showConfirmButton: false,
            timer: 2000
        })
    }

    // Request confirmation when submit form
    $('.request-confirmation').on('click', function (e) {
        e.preventDefault()
        Swal.fire({
            title: $(this).attr('message'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0c4079',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmă',
            cancelButtonText: 'Anulează',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                if ($(this).parents('form:first')[0].checkValidity())
                    $(this).parents('form:first').submit();
                else {
                    $(this).parents('form:first')[0].reportValidity()
                    Swal.fire({
                        icon: 'warning',
                        title: 'Completează toate câmpurile obligatorii',
                        showConfirmButton: false,
                        timer: 1000
                    })
                }
            }
        })
    })
});