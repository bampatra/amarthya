

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Pick Up per Staff</h1>
    <br>
    <!-- DataTales Example -->

    <div class="wrapper">
        <div class="one">
            <h6> Staff </h6>
            <div class="green-line"></div>
            <div id="staff_info" style="font-size: 14px">
                <div class="form-group row" >
                    <div class="col-sm-12">
                        <select id="id_staff" name="id_staff" class="form-control form-control-sm form-active-control selectpicker" data-live-search="true">
                            <option value="none"> -- Pilih Staff -- </option>
                            <?php foreach ($staffs as $staff) { ?>
                                <option value="<?php echo $staff->id_staff; ?>">
                                    <?php echo $staff->nama_staff; ?>
                                </option>


                            <?php } ?>
                        </select>
                    </div>
                </div>

            </div>
            


        </div>
        <div class="two">
            <h6> Periode </h6>
            <div class="green-line"></div>
            <form id="periode-form">
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Awal/Akhir</label>
                    <div class="col-sm-9">
                        <select id="awal_akhir_salary" name="awal_akhir_salary" class="form-control form-control-sm form-active-control" data-live-search="true">
                            <option value="AWAL">AWAL</option>
                            <option value="AKHIR">AKHIR</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Bulan</label>
                    <div class="col-sm-9">
                        <select id="bulan_salary" name="bulan_salary" class="form-control form-control-sm form-active-control" data-live-search="true" style="">
                           <?php
                               for ($i = 1; $i <= 12; $i++) {
                                   $timestamp = mktime(0, 0, 0, $i);
                                   $label = date("F", $timestamp);

                                   if(date("m") == $i){
                                       $html .= '<option selected value="' . $i . '">' . $label . '</option>"n"';
                                   } else {
                                       $html .= '<option value="' . $i . '">' . $label . '</option>"n"';
                                   }

                               }
                               //close the select tag
                               $html .= "</select>";

                               echo $html;

                           ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Tahun</label>
                    <div class="col-sm-9">
                        <input type="number" value="<?php echo date("Y"); ?>" id="tahun_salary" name="tahun_salary" class="form-control form-control-sm form-active-control">
                    </div>
                </div>
            </form>

        </div>
    </div>

    <br>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Delivery</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr class="no-hover-style">
                        <th> Tanggal </th>
                        <th> Order </th>
                        <th> Alamat Delivery </th>
                    </tr>
                    </thead>
                    <tbody id="main-content">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br><br><br><br><br>


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

    .product-item{
        border: 1px solid rgba(20,143,143,0.3);
        padding: 7px;
        margin-top:5px;
    }

    .alert-payment{
        padding: 0.2rem 0.75rem;
        margin: 0;
        text-align: left
    }

    .form-group{
        margin-bottom: 0;
    }

    span{
        font-size: 13px;
    }
</style>

<!-- Page level custom scripts -->

<!-- <script src="<?php echo base_url('assets/js/startbootstrap/demo/datatables-demo.js');?>"></script>-->

<script>

    var selected_order, selected_staff, selected_customer;

    document.title = "Laporan Pick Up per Staff - Amarthya Group";

    const urlParams = new URLSearchParams(location.search);
    if(urlParams.has('staff')){
        load_data(urlParams.get('staff'));
        $('#id_staff').val(urlParams.get('staff'));
    }

    $('#id_staff, #awal_akhir_salary, #bulan_salary, #tahun_salary').change(function(){
        if($('#id_staff').val() == "none"){
            return;
        }

        load_data($('#id_staff').val());
    })

    function load_data(staff){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        $('#dataTable').DataTable().destroy();
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            pageLength: 20,
            searching: false,
            bInfo: false,
            language: {
                search: ""
            },
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_laporan_pick_up?staff=' + staff + '&periode=' + $('#awal_akhir_salary').val() + '&bulan=' + $('#bulan_salary').val() + '&tahun=' + $('#tahun_salary').val(),
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                // $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {
                    "data": {"timestamp_pick_up":"timestamp_pick_up"},
                    mRender : function(data, type, full) {
                        let dateTimeParts= data.tgl_pick_up.split(/[- :]/);
                        dateTimeParts[1]--;
                        const temp_date = new Date(...dateTimeParts);

                        return (temp_date.getDate() < 10 ? '0' : '') + temp_date.getDate() + '/' +
                            (temp_date.getMonth() < 10 ? '0' : '') + (temp_date.getMonth() + 1) + '/' +
                            temp_date.getFullYear() + " " +
                            (temp_date.getHours() < 10 ? '0' : '') + temp_date.getHours() + ':' +
                            (temp_date.getMinutes() < 10 ? '0' : '') + temp_date.getMinutes() + ':' +
                            (temp_date.getSeconds() < 10 ? '0' : '') + temp_date.getSeconds();
                    }
                },
                {
                    "data": {
                        "no_order_vendor":"no_order_vendor",
                        "nama_vendor":"nama_vendor",
                        "grand_total_order":"grand_total_order"
                    },
                    mRender : function(data, type, full) {

                        html = '<strong>'+ data.no_order_vendor +'</strong><br>' +
                                '<span>'+ data.nama_vendor +'</span><br>' +
                            '   <span>Grand Total: '+ convertToRupiah(data.grand_total_order) +'</span>';


                        return html;
                    }
                },
                {
                    "data": {
                        "alamat_pick_up":"alamat_pick_up",
                        "no_hp_pick_up":"no_hp_pick_up"

                    },
                    mRender : function(data, type, full) {

                        html = data.alamat_pick_up + '<br>' + data.no_hp_pick_up

                        return html;
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
        window.open(admin_url + 'pick_up_detail?id=' + data.id_pick_up)
    })


</script>
