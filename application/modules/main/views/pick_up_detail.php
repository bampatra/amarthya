

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Formulir Pick Up</h1>
    <br>
    <!-- DataTales Example -->

    <div class="wrapper">
        <div class="one">
            <h6> Staff </h6>
            <div class="green-line"></div>
            <div id="staff_info" style="font-size: 14px">
                <input type="hidden" id="id_staff" value="<?php echo $pick_up[0]->id_staff ?>">
                <strong> <?php echo $pick_up[0]->nama_staff ?> (<?php echo $pick_up[0]->no_hp_staff ?>)</strong><br>
                <span style="font-size: 12px"><?php echo $pick_up[0]->alamat_staff ?></span>
            </div>
            <?php if ($pick_up[0]->status_pick_up == '0') echo "<span class=\"link pilih-staff\"> Edit Staff Pick Up </span>" ?>
            


        </div>
        <div class="two">
            <h6> Status Pick Up </h6>
            <div class="green-line"></div>
            <div class="form-group row">
                <label class="col-sm-12 col-form-label col-form-label-sm">
                    <?php if ($pick_up[0]->status_pick_up == '0'){?>
                    <div class="alert alert-danger alert-payment" role="alert">
                        <strong>BELUM PICK UP</strong>
                    </div>
                    <?php } else if ($pick_up[0]->status_pick_up == '1'){?>
                    <div class="alert alert-success alert-payment" role="alert">
                        <strong>SELESAI</strong>
                    </div>
                    <?php } else if ($pick_up[0]->status_pick_up == '2'){?>
                    <div class="alert alert-dark alert-payment" role="alert">
                        <strong>DIBATALKAN</strong>
                    </div>
                    <?php } ?>
                </label>
            </div>


            <?php if ($pick_up[0]->timestamp_pick_up != ''){?>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Waktu Pick Up</label>
                <label class="col-sm-9 col-form-label col-form-label-sm"><?php echo $pick_up[0]->timestamp_pick_up ?></label>
            </div>
            <?php }?>

        </div>
    </div>


    <div class="three">
        <h6> Detail Order </h6>
        <input type="hidden" value="<?php echo $pick_up[0]->id_order_vendor_m ?>" id="id_order_vendor_m">
        <input type="hidden" value="<?php echo $pick_up[0]->id_pick_up ?>" id="id_pick_up">
        <div id="order_vendor-data">
            <span style="font-size: 12px"><?php echo $pick_up[0]->custom_tgl_order_vendor ?></span><br>
            <strong><?php echo $pick_up[0]->no_order_vendor ?></strong><a href="<?php echo base_url('main/order_vendor_detail?no='.$pick_up[0]->no_order_vendor) ?>" target="_blank" style="font-size: 12px"> (Detail Order) </a><br>
            <span id="nama_vendor"><?php echo $pick_up[0]->nama_vendor ?></span><br>
            <span>Total Order: <?php echo "Rp. " . number_format($pick_up[0]->grand_total_order,2,',','.'); ?> </span>
            <br><span>Catatan Order: <?php echo $pick_up[0]->catatan_order_vendor ?></span>

        </div><br>
        <div id="item-lists">Memuat...</div>

    </div>

    <div class="wrapper" style="margin-top: 10px">
        <div class="one">
            <div class="form-group" >
                <label class="col-form-label">Catatan Pick Up</label>
                <textarea id="catatan_pick_up" name="catatan_pick_up" class="form-control form-active-control" <?php if ($pick_up[0]->status_pick_up != '0') echo "disabled" ?> > <?php echo $pick_up[0]->catatan_pick_up ?></textarea>
            </div>
            <br>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Tgl Pick Up</label>
                <div class="col-sm-9">
                    <input type="date" id="tgl_pick_up" name="tgl_pick_up" class="form-control form-control-sm form-active-control" value="<?php echo $pick_up[0]->custom_tgl_pick_up ?>" <?php if ($pick_up[0]->status_pick_up != '0') echo "disabled" ?> >
                </div>
            </div>
        </div>

        <div class="two">
            <div class="form-group" >
                <label class="col-form-label">Alamat Pick Up</label>
                <textarea id="alamat_pick_up" name="alamat_pick_up" class="form-control form-active-control" <?php if ($pick_up[0]->status_pick_up != '0') echo "disabled" ?> ><?php echo $pick_up[0]->alamat_pick_up ?></textarea>
            </div>
            <br>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label col-form-label-sm">No HP</label>
                <div class="col-sm-10">
                    <input type="number" id="no_hp_pick_up" name="no_hp_pick_up" class="form-control form-control-sm form-active-control" value="<?php echo $pick_up[0]->no_hp_pick_up ?>" <?php if ($pick_up[0]->status_pick_up != '0') echo "disabled" ?> >
                </div>
            </div>
        </div>

    </div>

    <br>

    <?php if ($pick_up[0]->status_pick_up == '0') echo "<button class=\"btn btn-danger delete\" style=\"width: 50%;  font-size: 14px;\">Hapus</button> <button class=\"btn btn-primary save\" style=\"width: 49%;  font-size: 14px;\">Update Pick Up</button><br><br>" ?>

    <br><br><br>


    <div class="modal fade" tabindex="-1" role="dialog" id="staff-modal" style="z-index: 5000">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-border_vendored" id="staffDataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th style="display: none;"> ID </th>
                                <th> Nama Staff </th>
                                <th> Posisi Staff </th>
                            </tr>
                            </thead>
                            <tbody id="main-content">

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

    var selected_staff = $('#id_staff').val();

    document.title = "Pick Up "+ $('#nama_vendor').html() +" - Amarthya Group";

    load_items($('#id_order_vendor_m').val());

    $('.delete').click(function(e){
        if(confirm("Data akan dihapus permanen. Yakin ingin menghapus data?")) {
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'delete_pick_up', // the url where we want to POST// our data object
                dataType: 'json',
                data: {
                    id_pick_up: $('#id_pick_up').val()
                },
                success: function (response) {
                    if(response.Status == "OK"){
                        show_snackbar(response.Message);
                        window.location.href = admin_url + 'pick_up_list';
                    } else if(response.Status == "ERROR" ){
                        show_snackbar(response.Message);
                    }

                    $('.loading').css("display", "none");
                    $('.Veil-non-hover').fadeOut();

                }
            })
        }
    })

    $('.save').click(function(e){

        if(confirm("Pastikan semua data sudah benar. Lanjutkan?")){
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'update_pick_up', // the url where we want to POST// our data object
                dataType: 'json',
                data: {
                    id_pick_up: $('#id_pick_up').val(),
                    alamat_pick_up: $('#alamat_pick_up').val(),
                    no_hp_pick_up: $('#no_hp_pick_up').val(),
                    tgl_pick_up: $('#tgl_pick_up').val(),
                    catatan_pick_up: $('#catatan_pick_up').val(),
                    id_staff: selected_staff
                },
                success: function (response) {
                    if(response.Status == "OK"){
                        show_snackbar(response.Message);
                    } else if(response.Status == "ERROR" ){
                        show_snackbar(response.Message);
                    }

                    $('.loading').css("display", "none");
                    $('.Veil-non-hover').fadeOut();
                }
            })
        }

    })


    $('.pilih-staff').click(function(){
        get_staff();
    })

    function load_items(id_order_vendor_m){
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : admin_url + 'get_order_vendor_s', // the url where we want to POST// our data object
            dataType    : 'json',
            data        : {id_order_vendor_m: id_order_vendor_m},
            success     : function(data){
                html_info_items = "";

                data.forEach(function(data){

                    html_info_items += '<div class="product-item">\n' +
                        '                    <table width="100%">\n' +
                        '                        <tr class="no-hover-style">\n' +
                        '                            <td>\n';


                    html_info_items += data.nama_product;




                    html_info_items +=  '            </td>\n' +
                        '                            <td style="text-align: right">\n' +
                        '                                <span style="font-size: 9px;">'+ data.qty_order_vendor +' x '+ convertToRupiah(data.harga_order_vendor) +' </span> <br>\n' +
                        '                                <strong style="font-size: 13px;">'+ convertToRupiah(data.total_order_vendor) +'</strong>\n' +
                        '                            </td>\n' +
                        '                        </tr>\n' +
                        '                    </table>\n' +
                        '                </div>';

                })

                $('#item-lists').html(html_info_items);

            }
        })
    }

    function get_staff(){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        $('#staffDataTable').DataTable().destroy();
        $('#staffDataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            searching: true,
            bInfo: false,
            language: {
                search: ""
            },
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_staff',
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "id_staff"},
                {"data": "nama_staff"},
                {"data": "nama_posisi"}

            ],
            initComplete: function (settings, json) {
                $('#staff-modal').modal('toggle');
                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        });
    }



    $('#staffDataTable').on( 'click', 'tbody tr', function () {
        id_staff = $('#staffDataTable').DataTable().row( this ).data().id_staff;
        no_hp_staff = $('#staffDataTable').DataTable().row( this ).data().no_hp_staff;
        nama_staff = $('#staffDataTable').DataTable().row( this ).data().nama_staff;
        alamat_staff = $('#staffDataTable').DataTable().row( this ).data().alamat_staff;


        html = '<strong>'+ nama_staff +' ('+ no_hp_staff +')</strong><br>' +
            '<span style="font-size: 12px">'+ alamat_staff +'</span>';

        selected_staff = id_staff;

        $('#staff_info').html(html)
        $('#staff-modal').modal('hide');
    });




</script>
