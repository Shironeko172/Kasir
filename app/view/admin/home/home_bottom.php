<script>
    $(document).ready(function() {
        $(".table").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "<?= base_url("admin/home/getdata") ?>",
                type: "post"
            },
            "columns": [{
                    "data": "id",
                    "render": function(data, type, row, meta) {
                        return meta.row + 1
                    }
                },
                {
                    "data": "waktu"
                },
                {
                    "data": "pelaku"
                },
                {
                    "data": "kejadian"
                }
            ],
            "order": [
                [0, "desc"]
            ],
            "searching": true,
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 10,
        });
    });
</script>