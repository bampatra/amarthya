

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Riwayat Belanja <span style="font-size: 16px; color: rgba(20,143,143,1)"><?php echo $person->nama_vendor ?> (Vendor)</span></h1>
    <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="form-inline" style="margin-bottom: 5px;">
                <div class="form-group">
                    Dari tanggal
                    <input type="date" id="start_date" name="start_date" style="margin-right: 5px; margin-left: 5px" class="form-control form-control-sm" value="<?php echo date('Y')?>-01-01"> sampai tanggal
                    <input type="date" id="end_date" name="end_date" style="margin-right: 5px; margin-left: 5px" class="form-control form-control-sm" value="<?php echo date('Y-m-d')?>">
                    <button id="save_setting" class="btn btn-primary btn-sm" style="margin-right: 5px">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="no-hover-style">
                                <th style="display: none;"> ID </th>
                                <th width="25%"> Tanggal </th>
                                <th width="30%"> No Order </th>
                                <th> Total </th>
                            </tr>
                            </thead>
                            <tbody id="main-content">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Produk Top 10</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                            <tr class="no-hover-style">
                                <th> Product </th>
                                <th> Qty </th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach($top_10s as $data){
                                    echo "<tr><td>".$data->nama_product."</td><td>".$data->total_qty_order."</td></tr>";
                                }?>

                            </tbody>
                        </table>
                    </div>
                </div>
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

    document.title = "Riwayat Belanja (<?php echo $person->nama_vendor ?>) - Amarthya Group";

    $('.btn').click(function(e){
        e.preventDefault();
    })

    $('#save_setting').click(function(e){
        e.preventDefault();
        get_data();
    })

    get_data();

    //get all products
    function get_data(){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        start_date = $('#start_date').val()
        end_date = $('#end_date').val()

        const urlParams = new URLSearchParams(location.search);
        if(urlParams.has('vendor')){
            vendor = urlParams.get('vendor');
        } else {
            vendor = 0
        }


        $('#dataTable').DataTable().destroy();
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            pageLength: 20,
            searching: false,
            ordering: false,
            bInfo: false,
            language: {
                search: ""
            },
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_riwayat_belanja_vendor?start=' + start_date + '&end=' + end_date + '&vendor=' + vendor ,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                // $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {
                    "data": {"tgl_order_vendor":"tgl_order_vendor"},
                    mRender : function(data, type, full) {
                        let dateTimeParts= data.tgl_order_vendor.split(/[- :]/);
                        dateTimeParts[1]--;
                        const temp_date = new Date(...dateTimeParts);

                        return (temp_date.getDate() < 10 ? '0' : '') + temp_date.getDate() + '/' +
                            (temp_date.getMonth() < 10 ? '0' : '') + (temp_date.getMonth() + 1) + '/' +
                            temp_date.getFullYear()
                    }
                },
                {"data": "no_order_vendor"},
                {
                    "data": {
                        "grand_total_order":"grand_total_order",
                        "is_paid_vendor":"is_paid_vendor"
                    },
                    mRender : function(data, type, full) {
                        if(data.is_paid_vendor == '1'){
                            return convertToRupiah(data.grand_total_order)
                        } else {
                            html = convertToRupiah(data.grand_total_order) + ' <span style="font-size:11px; color: rgba(20,143,143,1)">(Belum Bayar)</span>'
                            return html;
                        }

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
        var data = $('#dataTable').DataTable().row( this ).data();
        window.open(admin_url + 'order_vendor_detail?no=' + data.no_order_vendor)
    })


</script>
