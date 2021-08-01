

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Laporan Penjualan Menu Eatery</h1>
    <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data</h6>
        </div>
        <div class="card-body">
            <div class="alert alert-info" role="alert" style="font-size: 12px;">
                Menampilkan semua order (termasuk yang belum dibayar)
            </div>
            <form class="form-inline" style="margin-bottom: 10px;">
                <div class="form-group">
                    Dari tanggal
                    <input type="date" id="start_date" name="start_date" style="margin-right: 5px; margin-left: 5px" class="form-control form-control-sm" value="<?php echo date('Y')?>-01-01"> sampai tanggal
                    <input type="date" id="end_date" name="end_date" style="margin-right: 5px; margin-left: 5px" class="form-control form-control-sm" value="<?php echo date('Y-m-d')?>">
                    <select id="kategori_menu" name="kategori_menu" style="margin-right: 5px; margin-left: 5px" class="form-control form-active-control form-control-sm">
                        <option value="all"> Semua Kategori </option>
                        <?php foreach ($kategori as $cat) { ?>
                            <option value="<?php echo $cat->id_kategori_eatery; ?>">
                                <?php echo $cat->nama_kategori; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <button class="btn btn-primary btn-sm apply" style="margin-right: 5px">Terapkan</button>
<!--                    <button id="export_excel" class="btn btn-warning btn-sm">Export Excel</button>-->
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr class="no-hover-style">
                        <th style="display: none;"> ID </th>
                        <th style="min-width: 400px"> Nama Menu </th>
                        <th> Total Qty </th>
                        <th> Total Sales </th>
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


    get_laporan_produk();

    $('.apply').click(function(e){
        e.preventDefault();
        get_laporan_produk();
    })

    $('#export_excel').click(function(e){
        e.preventDefault();

        start_date = $('#start_date').val()
        end_date = $('#end_date').val()

        window.open(admin_url + 'excel_laporan_produk?start=' + start_date + '&end=' + end_date)
    })

    document.title = "Penjualan Menu Eatery - Amarthya Group";

    //get all products
    function get_laporan_produk(){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        start_date = $('#start_date').val()
        end_date = $('#end_date').val()
        kategori = $('#kategori_menu').val()

        $('#dataTable').DataTable().destroy();
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            pageLength: 20,
            lengthChange: false,
            searching: true,
            bInfo: false,
            order: [2, 'asc'],
            language: {
                search: ""
            },
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_laporan_menu?start=' + start_date + '&end=' + end_date + '&kategori=' + kategori,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "id_menu"},
                {"data": "nama_menu"},
                {"data": "total_qty"},
                {
                    "data": {"total_order":"total_order"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.total_order)
                    }
                }

            ],
            initComplete: function (settings, json) {
                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        });
    }






</script>
