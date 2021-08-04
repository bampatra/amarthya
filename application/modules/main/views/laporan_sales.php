

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Laporan Sales per Customer</h1>
    <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data</h6>
        </div>
        <div class="card-body">
            <form class="form-inline" style="margin-bottom: 10px;">
                <div class="form-group">
                    Dari tanggal
                    <input type="date" id="start_date" name="start_date" style="margin-right: 5px; margin-left: 5px" class="form-control form-control-sm" value="<?php echo date('Y')?>-01-01"> sampai tanggal
                    <input type="date" id="end_date" name="end_date" style="margin-right: 5px; margin-left: 5px" class="form-control form-control-sm" value="<?php echo date('Y-m-d')?>">
                    <button class="btn btn-primary btn-sm apply" style="margin-right: 5px">Terapkan</button>
                    <button id="export_excel" class="btn btn-warning btn-sm">Export Excel</button>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr class="no-hover-style">
                        <th style="display: none;"> ID </th>
                        <th style="min-width: 400px"> Nama Customer </th>
                        <th> Total Pesanan </th>
                        <th> Ongkir </th>
                        <th> Total Belanja </th>
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


    get_laporan_sales();

    $('.apply').click(function(e){
        e.preventDefault();
        get_laporan_sales();
    })

    $('#export_excel').click(function(e){
        e.preventDefault();

        start_date = $('#start_date').val()
        end_date = $('#end_date').val()

        window.open(admin_url + 'excel_laporan_sales?start=' + start_date + '&end=' + end_date)
    })

    document.title = "Laporan Sales per Customer - Amarthya Group";

    //get all products
    function get_laporan_sales(){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        start_date = $('#start_date').val()
        end_date = $('#end_date').val()

        $('#dataTable').DataTable().destroy();
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            pageLength: 20,
            lengthChange: false,
            searching: true,
            order: [1, 'asc'],
            bInfo: false,
            language: {
                search: ""
            },
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_laporan_sales?start=' + start_date + '&end=' + end_date,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "id_customer"},
                {"data": "nama_customer"},
                {
                    "data": {"total_order":"total_order"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.total_order)
                    }
                },
                {
                    "data": {"ongkir_order":"ongkir_order"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.ongkir_order)
                    }
                },
                {
                    "data": {"total_belanja":"total_belanja"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.total_belanja)
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
        window.open(admin_url + 'riwayat_belanja?customer=' + data.id_customer)
    });



</script>
