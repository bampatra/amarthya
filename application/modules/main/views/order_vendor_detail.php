

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Detail Order Vendor</h1>
    <span>
        <?php echo $brand_order; ?>
    </span>
    <br><br>
    <!-- DataTales Example -->

    <div class="wrapper">
        <div class="one">
            <h6> Vendor </h6>
            <div class="green-line"></div>
            <div id="vendor_info" style="font-size: 14px">
                <strong> <?php echo $orders[0]->nama_vendor; echo " (".$orders[0]->no_hp_vendor.")"?></strong><br>
                <span style="font-size: 12px"> <?php echo $orders[0]->alamat_vendor; ?> </span>
                <?php
                    if($orders[0]->is_in_store == '1'){
                        echo "<br><br><p>In Store (Tanpa Pickup)</p>";
                    } else {
                        echo "<br><br><span style='font-size: 12px;'>Pick up oleh: ".$orders[0]->nama_staff."</span>";
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
                    <input value="<?php echo $orders[0]->custom_tgl_order_vendor; ?>" type="date" id="tgl_order_vendor" name="tgl_order_vendor" class="form-control form-control-sm form-active-control" <?php if($orders[0]->status_pick_up == '0' || $orders[0]->status_pick_up == '1'){ echo "disabled"; } ?>>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">No. Order</label>
                <label class="col-sm-9 col-form-label col-form-label-sm" id="no_order"><?php echo $orders[0]->no_order_vendor; ?></label>
            </div>
            <a target="_blank" href="<?php echo base_url('main/pdf_tt?no=').$orders[0]->no_order_vendor?>"><span class="link">Print Tanda Terima</span></a>
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

                            </td>
                            <td style="text-align: right">
                                <span style="font-size: 9px;"><?php echo $order->qty_order_vendor; ?> x <?php echo "Rp. " . number_format($order->harga_order_vendor,2,',','.'); ?></span> <br>
                                <strong style="font-size: 13px;"><?php echo "Rp. " . number_format($order->total_order_vendor,2,',','.'); ?></strong>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php }?>
        </div>
    </div>

    <div class="wrapper" style="margin-top: 10px">
        <div class="one" style="min-height: 240px;">
            <div class="form-group" >
                <label class="col-form-label">Catatan</label>
                <textarea id="catatan_order_vendor" name="catatan_order_vendor" class="form-control form-active-control" <?php if($orders[0]->status_pick_up == '0' || $orders[0]->status_pick_up == '1'){ echo "disabled"; } ?>><?php echo $orders[0]->catatan_order_vendor; ?></textarea>
            </div>
            <div class="form-group row payment-info">
                <label class="col-sm-4 col-form-label col-form-label-sm">Tipe Transaksi</label>
                <div class="col-sm-8 col-form-label-sm">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipe_order" id="REK" value="REK" <?php if($orders[0]->tipe_order == 'REK'){ echo "checked"; } ?>>
                        <label class="form-check-label" for="inlineRadio1">Rekening</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipe_order" id="TUNAI" value="TUNAI" <?php if($orders[0]->tipe_order == 'TUNAI'){ echo "checked"; } ?>>
                        <label class="form-check-label" for="inlineRadio1">Tunai</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipe_order" id="FREE" value="FREE" <?php if($orders[0]->tipe_order == 'FREE'){ echo "checked"; } ?> >
                        <label class="form-check-label" for="inlineRadio1">Free</label>
                    </div>
                </div>
            </div>

        </div>
        <div class="two">
            <table style="border-spacing: 0 10px; border-collapse:separate; width: 100%;">

                <tr class="no-hover-style">
                    <td style="width: 10%"> </td>
                    <td style="text-align: right;width: 45%;" valign="top">
                        Diskon
                    </td>
                    <td valign="top" style="text-align: right; width: 45%; font-size: 18px; font-weight: bold" id="diskon">
                        <input type="number" id="diskon_order_vendor" name="diskon_order_vendor" class="form-control form-control-sm" style="width: 80%; float:right; text-align: right;" value="<?php echo $orders[0]->diskon_order_vendor?>" <?php if($orders[0]->status_pick_up == '0' || $orders[0]->status_pick_up == '1'){ echo "disabled"; } ?>>
                    </td>
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
                        <?php if($orders[0]->is_paid_vendor == '0'){ ?>
                            <div class="alert alert-danger alert-payment" role="alert">
                                <strong>BELUM BAYAR</strong>
                            </div>
                        <?php } else if ($orders[0]->is_paid_vendor == '1'){ ?>
                            <div class="alert alert-success alert-payment" role="alert">
                                <strong>LUNAS</strong>
                            </div>
                        <?php } ?>
                    </td>
                </tr>

            </table>
            <div class="form-group row">
                <div class="col-sm-12">
                    <input type="checkbox" id="is_paid_vendor" <?php if($orders[0]->is_paid_vendor == '1'){ echo "checked"; } ?>>
                    Sudah Dibayar
                </div>

            </div>


            <div class="form-group row" id="ref_payment_form" style="<?php if($orders[0]->is_paid_vendor == '1'){ echo "visibility:visible"; } else {echo "visibility:hidden"; }?> ">
                <label class="col-sm-2 col-form-label col-form-label-sm">Ref. </label>
                <div class="col-sm-10">
                    <input type="text" id="payment_detail" name="payment_detail" class="form-control form-control-sm form-active-control" value="<?php echo $orders[0]->payment_detail ?>">
                </div>
            </div>
        </div>
    </div>
    <br>
    <button class="btn btn-danger delete" style="width: 50%;  font-size: 14px;">Hapus</button>
    <button class="btn btn-primary save" style="width: 49%;  font-size: 14px;">Update Pesanan</button>
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
        text-align: right
    }
</style>

<!-- Page level custom scripts -->

<!-- <script src="<?php echo base_url('assets/js/startbootstrap/demo/datatables-demo.js');?>"></script>-->

<script>

    var tipe_harga = 'HP';
    var temp_harga = 0, temp_product;
    var grand_total = '<?php echo ($orders[0]->grand_total_order + $orders[0]->diskon_order_vendor) ?>';
    var diskon = '<?php echo $orders[0]->diskon_order_vendor ?>';
    var selected_vendor, temp_product;

    document.title = "Order Vendor #"+ $('#no_order').html() +" - Amarthya Group";

    item_lists = [];

    tipe_order_radio = $('input[type=radio][name=tipe_order]');
    tipe_order = tipe_order_radio.val();
    tipe_order_radio.change(function() {
        tipe_order = this.value;
    });

    $('#diskon_order_vendor').keyup(function(){
        diskon = this.value;
        set_harga();
    })

    $('.delete').click(function(e){
        if(confirm("Data akan dihapus permanen. Yakin ingin menghapus data?")) {
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'update_status_order_vendor_m', // the url where we want to POST// our data object
                dataType: 'json',
                data: {
                    no_order: $('#no_order').html(),
                    status_order: 'delete'
                },
                success: function (response) {
                    if(response.Status == "OK"){
                        show_snackbar(response.Message);
                        window.location.href = admin_url + 'order_vendor_list';
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
                url: admin_url + 'update_order_vendor', // the url where we want to POST// our data object
                dataType: 'json',
                data: {
                    no_order: $('#no_order').html(),
                    catatan_order_vendor: $('#catatan_order_vendor').val(),
                    tgl_order_vendor: $('#tgl_order_vendor').val(),
                    is_paid_vendor: $('#is_paid_vendor').prop("checked"),
                    payment_detail: $('#payment_detail').val(),
                    diskon_order_vendor: $('#diskon_order_vendor').val(),
                    tipe_order: tipe_order
                },
                success: function (response) {
                    if(response.Status == "OK"){
                        show_snackbar("Data berhasil diupdate");
                    } else if(response.Status == "ERROR" ){
                        show_snackbar(response.Message);
                    }

                    $('.loading').css("display", "none");
                    $('.Veil-non-hover').fadeOut();

                }
            })
        }

    })

    $('#is_paid_vendor').click(function(){
        if(this.checked) {
            $('#ref_payment_form').css("visibility", "visible");
            $('#is-paid-alert').html('<div class="alert alert-success alert-payment" role="alert">\n' +
                '                            <strong>LUNAS</strong>\n' +
                '                        </div>');
        } else {
            $('#ref_payment_form').css("visibility", "hidden");
            $('#is-paid-alert').html('<div class="alert alert-danger alert-payment" role="alert">\n' +
                '                            <strong>BELUM DIBAYAR</strong>\n' +
                '                        </div>');
        }
    })


    function set_harga(){
        $('#grandtotal').html(convertToRupiah(grand_total - diskon));
    }



</script>
