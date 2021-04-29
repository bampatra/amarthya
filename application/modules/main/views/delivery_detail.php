

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Formulir Delivery</h1>
    <br>
    <!-- DataTales Example -->

    <div class="wrapper">
        <div class="one">
            <h6> Staff </h6>
            <div class="green-line"></div>
            <div id="staff_info" style="font-size: 14px">
                <input type="hidden" id="id_staff" value="<?php echo $delivery[0]->id_staff ?>">
                <strong> <?php echo $delivery[0]->nama_staff ?> (<?php echo $delivery[0]->no_hp_staff ?>)</strong><br>
                <span style="font-size: 12px"><?php echo $delivery[0]->alamat_staff ?></span>
            </div>
            <?php if ($delivery[0]->status_delivery == '0') echo "<span class=\"link pilih-staff\"> Edit Staff Delivery </span>" ?>
            


        </div>
        <div class="two">
            <h6> Status Delivery </h6>
            <div class="green-line"></div>
            <div class="form-group row">
                <label class="col-sm-12 col-form-label col-form-label-sm">
                    <?php if ($delivery[0]->status_delivery == '0'){?>
                    <div class="alert alert-danger alert-payment" role="alert">
                        <strong>BELUM DIANTAR</strong>
                    </div>
                    <?php } else if ($delivery[0]->status_delivery == '1'){?>
                    <div class="alert alert-warning alert-payment" role="alert">
                        <strong>OTW SIS</strong>
                    </div>
                    <?php } else if ($delivery[0]->status_delivery == '2'){?>
                    <div class="alert alert-success alert-payment" role="alert">
                        <strong>SELESAI</strong>
                    </div>
                    <?php } else if ($delivery[0]->status_delivery == '3'){?>
                    <div class="alert alert-dark alert-payment" role="alert">
                        <strong>DIBATALKAN</strong>
                    </div>
                    <?php } ?>
                </label>
            </div>

            <?php if ($delivery[0]->timestamp_otw != '0000-00-00 00:00:00'){?>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Waktu OTW</label>
                <label class="col-sm-9 col-form-label col-form-label-sm"><?php echo $delivery[0]->timestamp_otw ?></label>
            </div>
            <?php }?>

            <?php if ($delivery[0]->timestamp_delivery != ''){?>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Waktu Sampai</label>
                <label class="col-sm-9 col-form-label col-form-label-sm"><?php echo $delivery[0]->timestamp_delivery ?></label>
            </div>
            <?php }?>

        </div>
    </div>


    <div class="three">
        <h6> Detail Order </h6>
        <input type="hidden" value="<?php echo $delivery[0]->id_order_m ?>" id="id_order_m">
        <input type="hidden" value="<?php echo $delivery[0]->id_delivery ?>" id="id_delivery">
        <div id="order-data">
            <span style="font-size: 12px"><?php echo $delivery[0]->custom_tgl_order ?></span><br>
            <strong><?php echo $delivery[0]->no_order ?></strong><a href="<?php echo base_url('main/order_detail?no='.$delivery[0]->no_order) ?>" target="_blank" style="font-size: 12px"> (Detail Order) </a><br>
            <span id="nama_customer"><?php echo $delivery[0]->nama_customer ?></span><br>
            <span>Total Order: <?php echo "Rp. " . number_format($delivery[0]->grand_total_order,2,',','.'); ?> || Ongkir: <?php echo "Rp. " . number_format($delivery[0]->ongkir_order,2,',','.'); ?></span>
            <br><span>Catatan Order: <?php echo $delivery[0]->catatan_order ?></span>

        </div><br>
        <div id="item-lists">Memuat...</div>

    </div>

    <div class="wrapper" style="margin-top: 10px">
        <div class="one">
            <div class="form-group" >
                <label class="col-form-label">Catatan Delivery</label>
                <textarea id="catatan_delivery" name="catatan_delivery" class="form-control form-active-control" <?php if ($delivery[0]->status_delivery != '0') echo "disabled" ?> > <?php echo $delivery[0]->catatan_delivery ?></textarea>
            </div>
            <br>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Tgl Delivery</label>
                <div class="col-sm-9">
                    <input type="date" id="tgl_delivery" name="tgl_delivery" class="form-control form-control-sm form-active-control" value="<?php echo $delivery[0]->custom_tgl_delivery ?>" <?php if ($delivery[0]->status_delivery != '0') echo "disabled" ?> >
                </div>
            </div>
        </div>

        <div class="two">
            <div class="form-group" >
                <label class="col-form-label">Alamat Delivery</label>
                <textarea id="alamat_delivery" name="alamat_delivery" class="form-control form-active-control" <?php if ($delivery[0]->status_delivery != '0') echo "disabled" ?> ><?php echo $delivery[0]->alamat_delivery ?></textarea>
            </div>
            <br>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label col-form-label-sm">No HP</label>
                <div class="col-sm-10">
                    <input type="number" id="no_hp_delivery" name="no_hp_delivery" class="form-control form-control-sm form-active-control" value="<?php echo $delivery[0]->no_hp_delivery ?>" <?php if ($delivery[0]->status_delivery != '0') echo "disabled" ?> >
                </div>
            </div>
        </div>

    </div>

    <br>
    <?php if ($delivery[0]->status_delivery == '0') echo "<button class=\"btn btn-danger delete\" style=\"width: 50%;  font-size: 14px;\">Hapus</button><button class=\"btn btn-primary save\" style=\"width: 49%;  font-size: 14px;\">Update Delivery</button><br><br>" ?>

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
                        <table class="table table-bordered" id="staffDataTable" width="100%" cellspacing="0">
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

    document.title = "Delivery "+ $('#nama_customer').html() +" - Amarthya Group";

    load_items($('#id_order_m').val());

    $('.delete').click(function(e){
        if(confirm("Data akan dihapus permanen. Yakin ingin menghapus data?")) {
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'delete_delivery', // the url where we want to POST// our data object
                dataType: 'json',
                data: {
                    id_delivery: $('#id_delivery').val()
                },
                success: function (response) {
                    if(response.Status == "OK"){
                        show_snackbar(response.Message);
                        window.location.href = admin_url + 'delivery_list';
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
                url: admin_url + 'update_delivery', // the url where we want to POST// our data object
                dataType: 'json',
                data: {
                    id_delivery: $('#id_delivery').val(),
                    alamat_delivery: $('#alamat_delivery').val(),
                    no_hp_delivery: $('#no_hp_delivery').val(),
                    tgl_delivery: $('#tgl_delivery').val(),
                    catatan_delivery: $('#catatan_delivery').val(),
                    id_staff: selected_staff
                },
                success: function (response) {
                    if(response.Status == "OK"){
                        window.location.href = admin_url + 'delivery_list';
                    } else if(response.Status == "ERROR" ){
                        show_snackbar(response.Message);

                        $('.loading').css("display", "none");
                        $('.Veil-non-hover').fadeOut();
                    }
                }
            })
        }

    })


    $('.pilih-staff').click(function(){
        get_staff();
    })

    function load_items(id_order_m){
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : admin_url + 'get_order_s', // the url where we want to POST// our data object
            dataType    : 'json',
            data        : {id_order_m: id_order_m},
            success     : function(data){
                html_info_items = "";

                data.forEach(function(data){

                    html_info_items += '<div class="product-item">\n' +
                        '                    <table width="100%">\n' +
                        '                        <tr class="no-hover-style">\n' +
                        '                            <td>\n';

                    if(data.is_free == "1"){
                        html_info_items += data.nama_product + ' (FREE)';
                    } else {
                        html_info_items += data.nama_product;
                    }



                    html_info_items +=  '            </td>\n' +
                        '                            <td style="text-align: right">\n' +
                        '                                <span style="font-size: 9px;">'+ data.qty_order +' x '+ convertToRupiah(data.harga_order) +' </span> <br>\n' +
                        '                                <strong style="font-size: 13px;">'+ convertToRupiah(data.total_order) +'</strong>\n' +
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
