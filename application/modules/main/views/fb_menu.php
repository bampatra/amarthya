

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">F&B Menu</h1>
    <br> <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Menu</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form class="form-inline" style="margin-bottom: 3px;">
                    <div class="form-group" style="margin-right: 5px;">
                        <select id="kategori_menu" name="kategori_menu" class="form-control form-active-control form-control-sm">
                            <option value="all"> Semua Kategori </option>
                            <?php foreach ($kategori as $cat) { ?>
                                <option value="<?php echo $cat->id_kategori_eatery; ?>">
                                    <?php echo $cat->nama_kategori; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </form>

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr class="no-hover-style">
                        <th style="display: none;"> ID </th>
                        <th> Nama Menu </th>
                        <th> Kategori </th>
                        <th style="width: 15%"> HP </th>
                        <th style="width: 15%"> HJ </th>
                        <th style="width: 15%"> HJ Online </th>
                    </tr>
                    </thead>
                    <tbody id="main-content">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<style>
    tr:hover{
        cursor: pointer;
        background: rgba(20,143,143,0.5);
        transition: background-color 0.15s ease-in-out;
    }

</style>

<!-- Page level custom scripts -->

<!-- <script src="<?php echo base_url('assets/js/startbootstrap/demo/datatables-demo.js');?>"></script>-->

<script>

    $('#kategori_menu').change(function(){
        get_menu($(this).val());
    })

    get_menu();

    document.title = "F&B Menu - Amarthya Group";
    ;

    //get all products
    function get_menu(kategori = "all"){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        $('#dataTable').DataTable().destroy();
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            pageLength: 20,
            searching: true,
            bInfo: false,
            language: {
                search: ""
            },
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_menu_eatery?kategori=' + kategori,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "id_menu"},
                {"data": "nama_menu"},
                {"data": "nama_kategori"},
                {
                    "data": {"HP_menu":"HP_menu"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.HP_menu)
                    }
                },
                {
                    "data": {"HJ_menu":"HJ_menu"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.HJ_menu)
                    }
                },
                {
                    "data": {"HJ_online_menu":"HJ_online_menu"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.HJ_online_menu)
                    }
                }

            ],
            initComplete: function (settings, json) {
                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        });
    }



    $('#dataTable').on( 'click', 'tbody tr', function () {

        data = $('#dataTable').DataTable().row( this ).data();
        window.open(admin_url + 'fb_costing?menu=' + data.id_menu)


    });



</script>
