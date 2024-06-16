<script>
    document.addEventListener("DOMContentLoaded", function() {
      var logout = document.getElementById("logout");

      logout.addEventListener("click", function(event) {
          // Prevent default link behavior
          event.preventDefault();

          // Execute SweetAlert code
          Swal.fire({
              title: "Are you sure you want to leave?",
              text: "There's more networking to be done",
              icon: "question",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              cancelButtonText: "No",
              confirmButtonText: "Yes"
          }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = '{{ route('Auth.logout') }}';
              }
          });
      });
    });
</script>

<script>
    function _delete(msg, route) {
        Swal.fire({
            title: "Delete",
            text: msg,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "No",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
              // Create a form element
              const form = document.createElement('form');
              form.method = 'POST';
              form.action = route;
              form.innerHTML = `
                  @csrf
                  @method('DELETE')
              `;
              // Append the form to the document body and submit it
              document.body.appendChild(form);
              form.submit();
            }
        });
    };

    function access_code(code) {
        Swal.fire({
            title: "The environment access code is",
            icon: "info",
            html: `
                <b><h3>
                <small class="text-muted">${code}</small>
                </b></h3>
            `,
            focusConfirm: true
        });
      }
</script>