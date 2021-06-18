

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Detail Order</h1>
    <span>

        <?php echo $brand_order; ?>
    </span>
    <br><br>
    <!-- DataTales Example -->

    <div class="wrapper">
        <div class="one">
            <h6> Customer </h6>
            <div class="green-line"></div>
            <div id="customer_info" style="font-size: 14px">
                <strong> <?php echo $orders[0]->nama_customer; echo " (".$orders[0]->no_hp_customer.")"?></strong><br>
                <span style="font-size: 12px"> <?php echo $orders[0]->alamat_customer; ?> </span>
                <?php
                if($orders[0]->is_in_store == '1'){
                    echo "<br><br><p>In Store (Tanpa Delivery)</p>";
                } else {
                    echo "<br><br><span style='font-size: 12px;'>Delivery oleh: ".$orders[0]->nama_staff."</span>";
                }
                ?>

            </div>



        </div>
        <div class="two">
            <h6> Informasi Pesanan </h6>
            <div class="green-line"></div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Tgl Order</label>
                <div class="col-sm-9">
                    <input value="<?php echo $orders[0]->custom_tgl_order; ?>" type="date" id="tgl_order" name="tgl_order" class="form-control form-control-sm form-active-control" <?php if($orders[0]->status_delivery == '0' || $orders[0]->status_delivery == '1' || $orders[0]->status_delivery == '2'){ echo "disabled"; } ?>>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">No. Order</label>
                <label class="col-sm-9 col-form-label col-form-label-sm" id="no_order"><?php echo $orders[0]->no_order; ?></label>
            </div>
            <a target="_blank" href="<?php echo base_url('main/pdf_order?no=').$orders[0]->no_order?>"><span class="link">Print Invoice</span></a>
        </div>
    </div>


    <div class="three" >
        <h6> Detail Pesanan </h6>
        <div id="item-lists">
            <?php foreach($orders as $order) { ?>
                <div class="product-item">
                       <table width="100%">
                            <tr class="no-hover-style">
                                <td>
                                    <?php echo $order->nama_product; ?>
                                    <?php if($order->is_free == '1'){ echo "(FREE)"; } ?>

                                </td>
                                <td style="text-align: right">
                                        <span style="font-size: 9px;"><?php echo $order->qty_order; ?> x <?php echo "Rp. " . number_format($order->harga_order,2,',','.'); ?></span> <br>
                                        <strong style="font-size: 13px;"><?php echo "Rp. " . number_format($order->total_order,2,',','.'); ?></strong>
                                </td>
                            </tr>
                       </table>
                    </div>
            <?php }?>
        </div>
    </div>

    <div class="wrapper" style="margin-top: 10px">
        <div class="one">
            <div class="form-group" >
                <label class="col-form-label">Catatan</label>
                <textarea id="catatan_order" name="catatan_order" class="form-control form-active-control" <?php if($orders[0]->status_delivery == '0' || $orders[0]->status_delivery == '1' || $orders[0]->status_delivery == '2'){ echo "disabled"; } ?>> <?php echo $orders[0]->catatan_order; ?> </textarea>
            </div>
            <div class="form-group row">
                <div class="col-sm-9">
                    <input type="checkbox" id="is_tentative" <?php if($orders[0]->is_tentative == '1'){ echo "checked"; } ?> <?php if($orders[0]->status_delivery == '0' || $orders[0]->status_delivery == '1' || $orders[0]->status_delivery == '2'){ echo "disabled"; } ?>>
                    TENTATIVE
                </div>

            </div>

        </div>
        <div class="two">
            <table style="border-spacing: 0 10px; border-collapse:separate; width: 100%;">
                <tr class="no-hover-style">
                    <td style="width: 10%"> <span class="link payment-detail"> Edit </span> </td>
                    <td style="text-align: right;width: 45%; font-size:13px" valign="top">
                        Subtotal
                    </td>
                    <td valign="top" style="text-align: right; width: 45%; font-size:13px" id="subtotal"> <?php echo "Rp. " . number_format($orders[0]->subtotal_order,2,',','.'); ?> </td>
                    <input type="hidden" id="subtotal_raw_number" value="<?php echo $orders[0]->subtotal_order; ?>">
                </tr>
                <tr class="no-pointer">
                    <td style="width: 10%"> </td>
                    <td style="text-align: right;width: 45%; font-size:13px" valign="top" id="ongkir-type">
                        <?php if($orders[0]->is_ongkir_kas == '1'){ echo "Ongkos Kirim (dari kas)"; } else { echo "Ongkos Kirim"; } ?>
                    </td>
                    <td valign="top" style="text-align: right; width: 45%; font-size:13px" id="ongkir"> <?php echo "Rp. " . number_format($orders[0]->ongkir_order,2,',','.'); ?> </td>
                </tr>
                <tr class="no-pointer">
                    <td style="width: 10%"> </td>
                    <td style="text-align: right;width: 45%; font-size:13px" valign="top">
                        Diskon
                    </td>
                    <td valign="top" style="text-align: right; width: 45%; font-size:13px" id="diskon"> <?php echo "Rp. " . number_format($orders[0]->diskon_order,2,',','.'); ?> </td>
                </tr>
                <tr class="no-pointer">
                    <td style="width: 10%"> </td>
                    <td style="text-align: right;width: 45%;" valign="top">
                        Grand Total
                    </td>
                    <td valign="top" style="text-align: right; width: 45%; font-size: 18px; font-weight: bold" id="grandtotal"> <?php echo "Rp. " . number_format($orders[0]->grand_total_order,2,',','.'); ?> </td>
                </tr>
                <tr class="no-pointer">
                    <td colspan="3" id="is-paid-alert">
                        <?php if($orders[0]->is_paid == '0'){ ?>
                        <div class="alert alert-danger alert-payment" role="alert">
                                <strong>BELUM BAYAR</strong>
                            </div>
                        <?php } else if ($orders[0]->is_paid == '1'){ ?>
                        <div class="alert alert-success alert-payment" role="alert">
                            <strong>LUNAS</strong>
                        </div>
                        <?php } ?>
                    </td>
                </tr>


            </table>
        </div>
    </div>
    <br>
    <button class="btn btn-danger delete" style="width: 50%;  font-size: 14px;">Hapus</button>
    <button class="btn btn-primary save" style="width: 49%;  font-size: 14px;">Update Pesanan</button>
    <br><br><br><br><br>


    <div class="modal fade" tabindex="-1" role="dialog" id="payment-modal" style="z-index: 5000">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Ongkos Kirim</label>
                        <div class="col-sm-9">
                            <input type="number" id="ongkir_order" name="ongkir_order" class="form-control form-control-sm form-active-control" value="<?php echo $orders[0]->ongkir_order; ?>" <?php if($orders[0]->status_delivery == '0' || $orders[0]->status_delivery == '1' || $orders[0]->status_delivery == '2'){ echo "disabled"; } ?>>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm"></label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="is_ongkir_kas" <?php if($orders[0]->is_ongkir_kas == '1'){ echo "checked"; } ?> <?php if($orders[0]->status_delivery == '0' || $orders[0]->status_delivery == '1' || $orders[0]->status_delivery == '2'){ echo "disabled"; } ?>>
                            Ongkir dari Kas
                        </div>

                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Diskon</label>
                        <div class="col-sm-9">
                            <input type="number" id="diskon_order" name="diskon_order" class="form-control form-control-sm form-active-control" value="<?php echo $orders[0]->diskon_order; ?>" <?php if($orders[0]->status_delivery == '0' || $orders[0]->status_delivery == '1' || $orders[0]->status_delivery == '2'){ echo "disabled"; } ?>>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm"></label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="is_paid" <?php if($orders[0]->is_paid == '1'){ echo "checked"; } ?> >
                            Sudah Dibayar
                        </div>

                    </div>


                    <div class="form-group row" id="ref_payment_form" style="display: <?php if($orders[0]->is_paid == '1'){ echo "block"; } else { echo "none"; } ?>">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Ref. Payment</label>
                        <div class="col-sm-12">
                            <input type="text" id="payment_detail" name="payment_detail" class="form-control form-control-sm form-active-control" value="<?php echo $orders[0]->payment_detail ?>" >
                        </div>
                    </div>
                    <div class="form-group row payment-info" style="display:<?php if($orders[0]->is_paid == '1'){ echo "block"; } else { echo "none"; } ?>">
                        <label class="col-sm-6 col-form-label col-form-label-sm">Tipe Transaksi</label>
                        <div class="col-sm-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe_order" id="REK" value="REK" <?php if($orders[0]->tipe_order == 'REK'){ echo "checked"; } ?>>
                                <label class="form-check-label" for="inlineRadio1">Rekening</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe_order" id="TUNAI" value="TUNAI" <?php if($orders[0]->tipe_order == 'TUNAI'){ echo "checked"; } ?>>
                                <label class="form-check-label" for="inlineRadio1">Tunai</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe_order" id="FREE" value="FREE" <?php if($orders[0]->tipe_order == 'FREE'){ echo "checked"; } ?>>
                                <label class="form-check-label" for="inlineRadio1">Free</label>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary save-payment">Simpan</button>
                </div>
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
        text-align: right
    }
</style>

<!-- Page level custom scripts -->

<!-- <script src="<?php echo base_url('assets/js/startbootstrap/demo/datatables-demo.js');?>"></script>-->

<script>

    document.title = "Pesanan #"+ $('#no_order').html() +" - Amarthya Group";

    tipe_order_radio = $('input[type=radio][name=tipe_order]');

    tipe_order = tipe_order_radio.val();

    tipe_order_radio.change(function() {
        tipe_order = this.value;
    });

    $('.delete').click(function(e){
        if(confirm("Data akan dihapus permanen. Yakin ingin menghapus data?")) {
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'update_status_order_m', // the url where we want to POST// our data object
                dataType: 'json',
                data: {
                    no_order: $('#no_order').html(),
                    status_order: 'delete'
                },
                success: function (response) {
                    if(response.Status == "OK"){
                        show_snackbar(response.Message);
                        window.location.href = admin_url + 'order_list';
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
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: admin_url + 'update_order_m', // the url where we want to POST// our data object
            dataType: 'json',
            data: {
                no_order: $('#no_order').html(),
                catatan_order: $('#catatan_order').val(),
                tgl_order: $('#tgl_order').val(),
                ongkir_order: $('#ongkir_order').val(),
                is_ongkir_kas: $('#is_ongkir_kas').prop("checked"),
                diskon_order: $('#diskon_order').val(),
                is_paid: $('#is_paid').prop("checked"),
                payment_detail: $('#payment_detail').val(),
                is_tentative: $('#is_tentative').prop("checked"),
                tipe_order: tipe_order
            },
            success: function (response) {
                if(response.Status == "OK"){
                    // window.location.href = admin_url + 'order_list';
                    show_snackbar(response.Message);
                } else if(response.Status == "ERROR" ){
                    show_snackbar(response.Message);
                }

                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();

            }
        })
    })

    var subtotal = 0, ongkir = 0, diskon = 0;

    $('document').ready(function(){

        subtotal = parseFloat($('#subtotal_raw_number').val());
        ongkir = parseFloat($('#ongkir_order').val());
        diskon = parseFloat($('#diskon_order').val());

    });

    item_lists = [];


    $('#collapseUser').addClass('show');
    $('#navbar-user').addClass('active');

    $('.payment-detail').click(function(){
        $('#payment-modal').modal('toggle');
    })

    $('#is_paid').click(function(){
        if(this.checked) {
            $('.payment-info').css("display", "block");
            $('#is-paid-alert').html('<div class="alert alert-success alert-payment" role="alert">\n' +
                '                            <strong>LUNAS</strong>\n' +
                '                        </div>');
        } else {
            $('.payment-info').css("display", "none");
            $('#is-paid-alert').html('<div class="alert alert-danger alert-payment" role="alert">\n' +
                '                            <strong>BELUM DIBAYAR</strong>\n' +
                '                        </div>');
        }
    })

    $('#is_ongkir_kas').click(function() {
        if(this.checked) {
            $('#ongkir-type').html('Ongkos Kirim (dari kas)');
        } else {
            $('#ongkir-type').html('Ongkos Kirim');
        }
        set_harga();
    })

    $('.save-payment').click(function(){
        if($('#ongkir_order').val() != ""){
            ongkir = $('#ongkir_order').val()
        } else {
            ongkir = 0;
        }

        if($('#diskon_order').val() != ""){
            diskon = $('#diskon_order').val()
        } else {
            diskon = 0;
        }

        set_harga();
        $('#payment-modal').modal('hide');
    })

    function set_harga(){
        $('#subtotal').html(convertToRupiah(subtotal));
        $('#ongkir').html(convertToRupiah(ongkir));
        $('#diskon').html(convertToRupiah(diskon));

        if($('#is_ongkir_kas').prop("checked")) {
            grandtotal = parseFloat(subtotal) - parseFloat(diskon);
        } else {
            grandtotal = parseFloat(subtotal) + parseFloat(ongkir) - parseFloat(diskon);
        }

        $('#grandtotal').html(convertToRupiah(grandtotal));
    }





</script>
