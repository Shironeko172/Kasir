<script>
    $(document).ready(function() {
        $("#login").submit(function(e) {
            e.preventDefault();
            var dataform = new FormData();
            dataform.append("email", $("#email").val());

            $.ajax({
                type: "post",
                url: "<?= base_url("member/reset/reset") ?>",
                data: dataform,
                processData: false,
                contentType: false,
                success: function(response) {
                    swal.fire({
                        title: "Email Telah Di Kirimkan",
                        icon: "success"
                    });
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