

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Laporan Penjualan Produk</h1>
    <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data</h6>
        </div>
        <div class="card-body">
            <div class="alert alert-info" role="alert" style="font-size: 12px;">
                Menampilkan semua pembelian vendor dan penjualan customer (termasuk yang belum dibayar)
            </div>
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
                        <th style="min-width: 400px"> Nama Produk </th>
                        <th style="display: none;"> Brand </th>
                        <th style="min-width: 150px"> Purchase </th>
                        <th> Stok In </th>
                        <th style="min-width: 150px"> Sales </th>
                        <th> Stok Out </th>
                        <th> Satuan </th>
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

    document.title = "Penjualan Produk - Amarthya Group";

    //get all products
    function get_laporan_produk(){
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
                url     : admin_url + 'get_laporan_produk?start=' + start_date + '&end=' + end_date,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
                $('td', row).eq(2).css("display", "none");
            },
            columns: [
                {"data": "id_product"},
                {
                    "data": {
                        "brand_product":"brand_product",
                        "nama_product":"nama_product"
                    },
                    mRender : function(data, type, full) {

                        html = data.nama_product + '<br><span style="font-size: 10px;">';

                        if(data.brand_product == "KA"){
                            html += "Kedai Amarthya"
                        } else if (data.brand_product == "AF") {
                            html += "Amarthya Fashion"
                        } else if (data.brand_product == "AHF") {
                            html += "Amarthya Healthy Food"
                        } else if (data.brand_product == "AH") {
                            html += "Amarthya Herbal"
                        }

                        html += '</span>';

                        return html;

                    }
                },
                {"data": "brand_product"},
                {
                    "data": {"total_purchase":"total_purchase"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.total_purchase)
                    }
                },
                {"data": "stok_in"},
                {
                    "data": {"total_sales":"total_sales"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.total_sales)
                    }
                },
                {"data": "stok_out"},
                {"data": "satuan_product"},

            ],
            initComplete: function (settings, json) {
                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        });
    }


    $('#dataTable').on( 'click', 'tbody tr', function () {
        data = $('#dataTable').DataTable().row( this ).data();
        window.open(admin_url + 'stok_in_out?product=' + data.id_product)
    });





</script>
