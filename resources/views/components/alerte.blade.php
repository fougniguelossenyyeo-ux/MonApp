@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: "{{ session('error') }}",
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    </script>
@endif
