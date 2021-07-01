

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">F&B Costing</h1>
    <!-- DataTales Example -->

        <div class="three">
            <h6> Detail Menu </h6>
            <div class="green-line mb-3"></div>

            <form id="header">

                <div class="form-row mb-2">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label col-form-label-sm">Nama Menu</label>
                            <div class="col-sm-8">
                                <input type="text" id="nama_menu"  name="nama_menu" class="form-control form-active-control form-control-sm" value="<?php if(isset($_GET['menu'])){ echo $master->nama_menu; }?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm">Kategori</label>
                            <div class="col-sm-8">
                                <select id="kategori_menu" name="kategori_menu" class="form-control form-active-control form-control-sm">
                                    <option value="none"> -- Pilih Kategori -- </option>
                                    <?php foreach ($kategori as $cat) { ?>

                                        <?php
                                            if(isset($_GET['menu'])) {
                                                if($master->kategori_menu == $cat->id_kategori_eatery){?>
                                                    <option value="<?php echo $cat->id_kategori_eatery; ?>" selected>
                                                        <?php echo $cat->nama_kategori; ?>
                                                    </option>
                                           <?php     } else { ?>
                                                    <option value="<?php echo $cat->id_kategori_eatery; ?>">
                                                        <?php echo $cat->nama_kategori; ?>
                                                    </option>
                                            <?php  }
                                            } else {
                                        ?>

                                        <option value="<?php echo $cat->id_kategori_eatery; ?>">
                                            <?php echo $cat->nama_kategori; ?>
                                        </option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label col-form-label-sm">Deskripsi</label>
                            <div class="col-sm-8">
                                <textarea id="deskripsi_menu"  name="deskripsi_menu" class="form-control form-active-control form-control-sm"><?php if(isset($_GET['menu'])){ echo $master->deskripsi_menu; }?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label col-form-label-sm">HJ</label>
                            <div class="col-sm-8">
                                <input type="number" id="HJ_menu"  name="HJ_menu" class="form-control form-active-control form-control-sm" value="<?php if(isset($_GET['menu'])){ echo $master->HJ_menu; }?>">
                            </div>
                        </div>

                    </div>
                </div>
            </form>

        </div>


    <div class="three">
        <h6> Ingredients </h6>
        <div class="green-line"></div>
        <div>

            <div class="form-group row mb-2">
                <div class="col-sm-5 first-column">
                    <label class="col-form-label col-form-label-sm">Nama Bahan</label>
                </div>
                <div class="col-sm-1 middle-column">
                    <label class="col-form-label col-form-label-sm">Qty</label>
                </div>
                <div class="col-sm-2 middle-column">
                    <label class="col-form-label col-form-label-sm">Satuan</label>
                </div>
                <div class="col-sm-3 middle-column">
                    <label class="col-form-label col-form-label-sm">Total Harga</label>
                </div>
                <div class="col-sm-1 last-column">

                </div>
            </div>

            <form id="ingredients">

                <?php if(isset($_GET['menu'])){ ?>

                    <?php foreach($bahanbahan as $bahan){ ?>
                        <div class="form-group row mb-2" id="each_ingredient">
                            <div class="col-sm-5 first-column">
                                <input type="text" name="nama_product[]" class="form-control form-control-sm form-active-control product" value="<?php echo $bahan->nama_product; ?>">
                                <input type="hidden" name="id_product[]" class="form-control form-control-sm form-active-control id_product" value="<?php echo $bahan->id_product; ?>">
                            </div>
                            <div class="col-sm-1 middle-column">
                                <input type="number" name="qty_bahan[]" class="form-control form-control-sm form-active-control qty" value="<?php echo $bahan->qty_bahan; ?>">
                            </div>
                            <div class="col-sm-2 middle-column">
                                <input disabled type="text" id="satuan" class="form-control form-control-sm form-active-control satuan" value="<?php echo $bahan->satuan_product; ?>">
                            </div>
                            <div class="col-sm-3 middle-column">
                                <input disabled type="number" name="total_harga[]" id="total_harga" class="form-control form-control-sm form-active-control total_harga" style="text-align: right;"  value="<?php echo $bahan->HP_product * $bahan->qty_bahan; ?>">
                                <input type="hidden" name="harga_satuan[]" class="form-control form-control-sm form-active-control harga_satuan" value="<?php echo $bahan->HP_product; ?>">
                            </div>
                            <div class="col-sm-1 last-column" style="text-align: center;">
                                <span class="link hapus"> Hapus </span>
                            </div>
                        </div>
                    <?php } ?>

                <?php } else {?>

                <div class="form-group row mb-2" id="each_ingredient">
                    <div class="col-sm-5 first-column">
                        <input type="text" name="nama_product[]" class="form-control form-control-sm form-active-control product">
                        <input type="hidden" name="id_product[]" class="form-control form-control-sm form-active-control id_product">
                    </div>
                    <div class="col-sm-1 middle-column">
                        <input type="number" name="qty_bahan[]" class="form-control form-control-sm form-active-control qty">
                    </div>
                    <div class="col-sm-2 middle-column">
                        <input disabled type="text" id="satuan" class="form-control form-control-sm form-active-control satuan">
                    </div>
                    <div class="col-sm-3 middle-column">
                        <input disabled type="number" name="total_harga[]" id="total_harga" class="form-control form-control-sm form-active-control total_harga" style="text-align: right;">
                        <input type="hidden" name="harga_satuan[]" class="form-control form-control-sm form-active-control harga_satuan">
                    </div>
                    <div class="col-sm-1 last-column" style="text-align: center;">
                        <span class="link hapus"> Hapus </span>
                    </div>
                </div>

                <?php } ?>

                <div id="newRow"></div>
            </form>

            <button class="btn btn-primary-empty add-item" style="width: 100%;  font-size: 11px; margin-top: 10px; margin-bottom: 20px">Tambah Bahan</button>

            <br>

            <div style="text-align: right; width: 99%">
                <span style="font-size: 14px"> Total Costing </span>
                <h2 id="total_costing">Rp. 0</h2>
            </div>

        </div>

 </div>

    <br>


    <?php if(!isset($_GET['menu'])){ ?>
        <button class="btn btn-primary save" style="width: 100%;  font-size: 14px;">Simpan Data</button>
    <?php } else {?>

        <button class="btn btn-danger delete" style="width: 50%;  font-size: 14px;">Hapus</button>
        <button class="btn btn-primary save" style="width: 49%;  font-size: 14px;">Update Data</button>
    <?php } ?>
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

    .first-column{
        padding-right: 0.2rem;
    }

    .middle-column{
        padding-right: 0.2rem;
        padding-left: 0.2rem;
    }

    .last-column{
        padding-left: 0.2rem;
    }

</style>

<!-- Page level custom scripts -->

<!-- <script src="<?php echo base_url('assets/js/startbootstrap/demo/datatables-demo.js');?>"></script>-->

<script>


    document.title = "F&B Costing - Amarthya Group";

    $(document).ready(function(){
        update_price();
    })

    $('.add-item').click(function(e){

        var html = '<div class="form-group row mb-2" id="each_ingredient">\n' +
            '                    <div class="col-sm-5 first-column">\n' +
            '                       <input type="text" name="nama_product[]" class="form-control form-control-sm form-active-control product">' +
            '                       <input type="hidden" name="id_product[]" class="form-control form-control-sm form-active-control id_product">' +
            '                    </div>\n' +
            '                    <div class="col-sm-1 middle-column">\n' +
            '                        <input type="number" name="qty_bahan[]" class="form-control form-control-sm form-active-control qty">\n' +
            '                    </div>\n' +
            '                    <div class="col-sm-2 middle-column">\n' +
            '                        <input disabled type="text" id="satuan" class="form-control form-control-sm form-active-control satuan">\n' +
            '                    </div>\n' +
            '                    <div class="col-sm-3 middle-column">\n' +
            '                        <input disabled type="number" name="total_harga[]" id="total_harga" class="form-control form-control-sm form-active-control total_harga" style="text-align: right;">\n' +
            '                        <input type="hidden" name="harga_satuan[]" class="form-control form-control-sm form-active-control harga_satuan">' +
            '                    </div>\n' +
            '                    <div class="col-sm-1 last-column" style="text-align: center;">\n' +
            '                        <span class="link hapus"> Hapus </span>\n' +
            '                    </div>\n' +
            '                </div>';

        $('#newRow').append(html);

        autocomplete_init();

        $('.qty').keyup(function(){
            qty = parseFloat($(this).val());
            price = parseFloat($(this).parent().parent().find('.middle-column').find('.harga_satuan').val());

            grand_total = qty * price;

            $(this).parent().parent().find('.middle-column').find('.total_harga').val(grand_total);

            update_price();

        })

    })

    $(document).on('click', '.hapus', function () {
        $(this).closest('#each_ingredient').remove();
        update_price();
    });

    $('.qty').keyup(function(){
        qty = parseFloat($(this).val());
        price = parseFloat($(this).parent().parent().find('.middle-column').find('.harga_satuan').val());

        grand_total = qty * price;

        $(this).parent().parent().find('.middle-column').find('.total_harga').val(grand_total);

        update_price();

    })

    autocomplete_init();

    function autocomplete_init(){
        $( ".product" ).autocomplete({
            minLength: 2,
            source: function( request, response ) {
                // Fetch data
                $.ajax({
                    url: admin_url + 'get_suggest_bahan',
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                        response(data);
                    }
                });
            },
            select: function (event, ui) {

                // Set selection
                $(this).val(ui.item.label);
                $(this).parent().find('.id_product').val(ui.item.id);
                $(this).parent().parent().find('.middle-column').find('.qty').val(1);
                $(this).parent().parent().find('.middle-column').find('.satuan').val(ui.item.satuan);
                $(this).parent().parent().find('.middle-column').find('.total_harga').val(ui.item.price);
                $(this).parent().parent().find('.middle-column').find('.harga_satuan').val(ui.item.price);

                update_price();

            },
            focus: function(event, ui) {
                $(this).val(ui.item.label);
            }
        });
    }

    function update_price(){
        data = $("input[name='total_harga[]']").map(function(){return $(this).val();}).get();
        total = 0.0;

        data.forEach(function(item){
            total += parseFloat(item) || 0;
        })

        $('#total_costing').html(convertToRupiah(total));
    }

    <?php if(isset($_GET['menu'])){ ?>
    $('.delete').click(function(){
        if(confirm("Data akan dihapus permanen. Lanjutkan?")){
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'delete_menu', // the url where we want to POST// our data object
                dataType: 'json',
                data: {id_menu: <?php echo $_GET['menu']; ?>},
                success: function (response) {
                    if(response.Status == "OK"){
                        show_snackbar(response.Message);
                        window.location.href = admin_url + 'fb_menu';
                    } else if(response.Status == "ERROR" ){
                        show_snackbar(response.Message);
                    }

                    $('.loading').css("display", "none");
                    $('.Veil-non-hover').fadeOut();

                }
            })
        }
    })
    <?php } ?>

    $('.save').click(function(e){

        if(confirm("Pastikan semua data sudah benar. Lanjutkan?")){
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

    <?php if(isset($_GET['menu'])){ ?>
                $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: admin_url + 'update_menu', // the url where we want to POST// our data object
                    dataType: 'json',
                    data: $('form').serialize() + '&id_menu=' + <?php echo $_GET['menu']; ?>,
                    success: function (response) {
                        show_snackbar(response.Message);

                        if(response.Status == "OK"){

                        }

                        $('.loading').css("display", "none");
                        $('.Veil-non-hover').fadeOut();
                    }
                })

    <?php } else {?>
                $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: admin_url + 'save_menu', // the url where we want to POST// our data object
                    dataType: 'json',
                    data: $('form').serialize(),
                    success: function (response) {
                        show_snackbar(response.Message);

                        if(response.Status == "OK"){
                            window.location.href = admin_url + 'fb_menu';
                        }

                        $('.loading').css("display", "none");
                        $('.Veil-non-hover').fadeOut();
                    }
                })
    <?php } ?>

        }
    })






</script>
