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
});