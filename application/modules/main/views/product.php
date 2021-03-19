

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Produk</h1>
    <br>

    <button class="btn btn-primary add" style="background: #a50000; color: white; width: 300px;"> Tambah Produk </button>
    <br> <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th style="display: none;"> ID </th>
                        <th> Nama Produk </th>
                        <th> HJ </th>
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


<div class="modal fade" tabindex="-1" role="dialog" id="input-product-modal" style="z-index: 5000">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="product-form">
                    <div class="form-group" >
                        <label class="col-form-label">Nama Product</label>
                        <input type="text" id="nama_product"  name="nama_product" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-namaproduct">Data tidak valid</div>
                    </div>

                    <div class="form-group" >
                        <label class="col-form-label">SKU Product</label>
                        <input type="text" id="SKU_product"  name="SKU_product" class="form-control form-active-control">
                    </div>


                    <div class="form-group" >
                        <label class="col-form-label">Satuan</label>
                        <input type="text" id="satuan_product"  name="satuan_product" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-satuanproduct">Data tidak valid</div>
                    </div>

                    <div class="form-group" >
                        <label class="col-form-label">HP</label>
                        <input type="number" id="HP_product"  name="HP_product" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-HP">Data tidak valid</div>
                    </div>

                    <div class="form-group" >
                        <label class="col-form-label">HR</label>
                        <input type="number" id="HR_product"  name="HR_product" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-HR">Data tidak valid</div>
                    </div>

                    <div class="form-group" >
                        <label class="col-form-label">HJ</label>
                        <input type="number" id="HJ_product"  name="HJ_product" class="form-control form-active-control">
                        <div class="invalid-feedback invalid-HJ">Data tidak valid</div>
                    </div>


                    <input type="hidden" id="id_product" name="id_product" val="0">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save">Simpan</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="detail-product-modal" style="z-index: 5000">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="nama_product"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Stok: <span class="stok_product">0</span><br>
                <span class="edit" style="color: blue; text-decoration: underline; font-size: 11px;">Edit Produk</span>
                <br><br>
                <table>
                    <tr>
                        <td> HP</td>
                        <td class="HP_product"></td>
                    </tr>
                    <tr>
                        <td> HJ</td>
                        <td class="HJ_product"></td>
                    </tr>
                    <tr>
                        <td> HR</td>
                        <td class="HR_product"></td>
                    </tr>
                </table>

                <br>
                <p>Stok In/Out</p>

<!--                Table stok in out -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="inOutDataTable" width="100%" cellspacing="0" style="font-size: 11px">
                        <thead>
                            <tr>
                                <th> Tanggal </th>
                                <th> Tipe </th>
                                <th> Stok </th>
                                <th> Expired </th>
                            </tr>
                        </thead>
                        <tbody id="in-out-content">
                            <tr>
                                <th> 04/03/2021 </th>
                                <th> IN </th>
                                <th> 40 </th>
                                <th> 2020-03-10 </th>
                            </tr>
                            <tr>
                                <th> 04/03/2021 </th>
                                <th> OUT </th>
                                <th> 5 </th>
                                <th> </th>
                            </tr>
                            <tr>
                                <th> 05/03/2021 </th>
                                <th> OUT </th>
                                <th> 5 </th>
                                <th> </th>
                            </tr>
                            <tr>
                                <th> 06/03/2021 </th>
                                <th> OUT </th>
                                <th> 3 </th>
                                <th> </th>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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

    $('#collapseUser').addClass('show');
    $('#navbar-user').addClass('active');
    detail_toggled = false;

    get_product();

    //get all products
    function get_product(){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();
        $.ajax({
            type        : 'GET', // define the type of HTTP verb we want to use (POST for our form)
            url         : admin_url + 'get_product', // the url where we want to POST// our data object
            dataType    : 'json',
            success     : function(data){
                html = '';
                data.forEach(function(data){

                    html += '<tr>'+
                        '<td style="display: none;">'+ data.id_product +'</td>';

                    html += ' <td>'+ data.nama_product +'<br> <span style="font-size: 10px">Stok: '+ '0' +'</span></td>' +
                            ' <td style="width:42%">'+ convertToRupiah(data.HJ_product) +'</td>' +
                        '</tr>';
                })

                $('#dataTable').DataTable().destroy();
                $('#main-content').html(html);
                $('#dataTable').DataTable({
                    "lengthChange": false
                });

                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        })
    }

    function in_out_init(){

        $('#inOutDataTable').DataTable().destroy();
        $('#inOutDataTable').DataTable({
            "lengthChange": false,
            "searching": false,
            "bInfo": false,
            "columnDefs": [ {
                "targets": [1,2],
                "orderable": false
            },
                {
                    "targets":0,
                    "type":"date-eu"
                }
            ]
        });

    }


    $('#dataTable').on( 'click', 'tbody tr', function () {
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();
        $('body').addClass('modal-open');
        id_product = $('#dataTable').DataTable().row( this ).data()[0];
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : admin_url + 'get_product_by_id', // the url where we want to POST// our data object
            dataType    : 'json',
            data        : {id_product: id_product},
            success     : function(data){

                setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
                $('#id_product').val(htmlDecode(data.id_product));
                $('#nama_product').val(htmlDecode(data.nama_product));
                $('#SKU_product').val(htmlDecode(data.SKU_product));
                $('#satuan_product').val(htmlDecode(data.satuan_product));
                $('#HP_product').val(htmlDecode(data.HP_product));
                $('#HJ_product').val(htmlDecode(data.HJ_product));
                $('#HR_product').val(htmlDecode(data.HR_product));



                $('#detail-product-modal').modal('toggle');

                //inside detail-product-modal
                $('.nama_product').html(htmlDecode(data.nama_product));
                $('.HP_product').html(convertToRupiah(htmlDecode(data.HP_product)));
                $('.HJ_product').html(convertToRupiah(htmlDecode(data.HJ_product)));
                $('.HR_product').html(convertToRupiah(htmlDecode(data.HR_product)));

                in_out_init();

                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        })
    });

    $('.add').click(function (e) {
        e.preventDefault();
        $('.invalid-feedback').css('display', 'none');
        $('#id_product').val(0);
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('#product-form').trigger('reset');
        $('#input-product-modal').modal('toggle');
        detail_toggled = false;
    })

    $('.edit').click(function(e){
        $('.invalid-feedback').css('display', 'none');
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('#detail-product-modal').modal('hide');
        $('#detail-product-modal').on('hidden.bs.modal', function () {
            $('#input-product-modal').modal('toggle');
        })
        detail_toggled = true;
    })

    // if(detail_toggled){
    //     $('#input-product-modal').on('hidden.bs.modal', function () {
    //         $('#detail-product-modal').modal('toggle');
    //     })
    // }


    $('.save').click(function(e){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: admin_url + 'add_product', // the url where we want to POST// our data object
            dataType: 'json',
            data: $('#product-form').serialize(),
            success: function (response) {
                $('.invalid-feedback').css('display', 'none');
                if(response.Status == "OK"){
                    get_product();
                    $('#input-product-modal').modal('hide');
                } else if(response.Status == "FORMERROR") {
                    response.Error.forEach(function(error){
                        $('.'+ error +'').css('display', 'block');
                    })
                } else if(response.Status == "EXIST") {
                    show_snackbar('Nama Customer sudah terdaftar');
                } else if(response.Status == "ERROR" ){
                    show_snackbar(response.Message);
                }

                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        })
    })



</script>
