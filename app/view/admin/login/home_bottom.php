<script>
    $(document).ready(function() {
        $("#login").submit(function(e) {
            e.preventDefault();
            var dataform = new FormData();
            dataform.append("email", $("#email").val());
            dataform.append("password", $("#password").val());

            $.ajax({
                type: "post",
                url: "<?= base_url("admin/login/proses") ?>",
                data: dataform,
                processData: false,
                contentType: false,
                success: function(response) {
                    var json = JSON.parse(response);
                    if (json.redirect) {
                        window.location.href = json.redirect;
                    } else {
                        swal.fire({
                            title: json.message,
                            icon: "error"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    swal.fire({
                        title: error,
                        icon: "error"
                    });
                }
            });
        });
    });
</script>