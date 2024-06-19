<html>
<head>
    <link rel="stylesheet" href="{{ url('css/main/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/main/style.css') }}">
    <link rel="stylesheet" href="{{ url('css/main/toastr.css') }}">
</head>
<body style="background-color: black">
    <script src="{{ url('js/main/sweetalert2@11.js') }}"></script>
    <script src="{{ url('js/main/jquery.min.js') }}"></script>
    <script src="{{ url('js/main/toastr.min.js') }}"></script>
    
    @include('template/scripts/toastr')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: 'Enter Access Code',
                html: '<form id="join-form" method="POST" action="/JoinEnvironment/{{$id}}">' +
                        '@csrf' +
                        '<input type="text" id="access-code" name="access_code" class="swal2-input" placeholder="Access Code">' +
                        '</form>',
                focusConfirm: false,
                allowOutsideClick: false,
                preConfirm: () => {
                    const form = Swal.getPopup().querySelector('#join-form');
                    const accessCode = form.querySelector('#access-code').value;
                    if (!accessCode) {
                        Swal.showValidationMessage('Access code is required');
                    } else {
                        form.submit();
                    }
                }
            });
        });
    </script>
</body>
</html>
