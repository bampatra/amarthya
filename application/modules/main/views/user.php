

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Master User</h1>
    <br>

    <button class="btn btn-primary add-user" style="background: #a50000; color: white; width: 300px;"> Tambah User </button>
    <br> <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar User</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th style="display: none;"> ID User </th>
                        <th> Email </th>
                        <th> No. Telepon </th>
                        <th> Aktif </th>
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


<div class="modal fade" tabindex="-1" role="dialog" id="master-user-modal" style="z-index: 5000">
    <div class="modal-dialog" role="document" style="max-height: 90vh; overflow: scroll;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="master-user-form">
                    <div class="form-group" >
                        <label class="col-form-label">Email</label>
                        <input type="text" id="email_web_user"  name="email_web_user" class="form-control form-active-control">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">No. Telp</label>
                        <input type="text" id="telp_web_user" name="telp_web_user" class="form-control form-active-control">
                    </div>
                    <div class="form-group modal-button-save">
                        <label class="col-form-label">Password <span class="random_password" style="color: #a50000; font-size: 12px; margin-left: 10px; cursor: pointer; text-decoration: underline">(Generate Random Password)</span></label>
                        <input type="password" id="password_web_user" name="password_web_user" class="form-control" readonly>
                    </div>
                    <div class="form-group" >
                        <label> Aktif </label>
                        <input class="form-control form-active-control" type="checkbox" id="active_web_user" name="active_web_user">
                    </div>
                    <div class="form-group" >
                        <label> Admin </label>
                        <input class="form-control form-active-control" type="checkbox" id="is_admin" name="is_admin">
                    </div>

                    <input type="hidden" id="id_web_user" name="id_web_user" val="0">
                </form>
            </div>
            <div class="modal-footer">
                <div class="modal-button-view-only">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary edit-user">Edit</button>
                </div>
                <div class="modal-button-save">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save-user">Simpan</button>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="product-modal" style="z-index: 5001">
    <div class="modal-dialog" role="document" style="max-height: 90vh; overflow: scroll;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="dataTableProd" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th style="display: none;"> ID </th>
                        <th>Produk</th>
                    </tr>
                    </thead>
                    <tbody id="prod-content">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<style>
    tr:hover{
        cursor: pointer;
        background: #ffe1dd;
    }

    .btn-primary{
        background: #a50000;
        color: white;
        border: 1px solid white;
        transition: .2s;
    }

    .btn-primary:hover{
        background: white;
        color: #a50000;
        border: 1px solid #a50000;
    }
</style>

<!-- Page level custom scripts -->

<!-- <script src="<?php echo base_url('assets/js/startbootstrap/demo/datatables-demo.js');?>"></script>-->

<script>

    $('#collapseUser').addClass('show');
    $('#navbar-user').addClass('active');

    get_all_user();

    //get all products
    function get_all_user(){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();
        $.ajax({
            type        : 'GET', // define the type of HTTP verb we want to use (POST for our form)
            url         : admin_url + 'get_all_user', // the url where we want to POST// our data object
            dataType    : 'json',
            success     : function(data){
                html = '';
                data.forEach(function(data){

                    html += '<tr>'+
                        '<td style="display: none;">'+ data.id_web_user +'</td>';


                    if(data.is_admin == '0'){
                       html += ' <td>'+ data.email_web_user +'</td>';
                    } else {
                        html +=  ' <td>'+ data.email_web_user +' <span style="padding: 5px; background: #a50000; color: white; border-radius: 6px; font-size: 8px;"> ADMIN </span></td>';
                    }


                    html +=  ' <td>'+ data.telp_web_user +'</td>\n'+
                        ' <td>'+  active_status(data.active_web_user) +'</td>\n' +
                        '                    </tr>';
                })

                $('#dataTable').DataTable().destroy();
                $('#main-content').html(html);
                $('#dataTable').DataTable();

                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        })
    }


    // get pricelist by id
    $('#dataTable').on( 'click', 'tbody tr', function () {
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();
        $('body').addClass('modal-open');
        id_web_user = $('#dataTable').DataTable().row( this ).data()[0];
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : admin_url + 'get_user_by_id', // the url where we want to POST// our data object
            dataType    : 'json',
            data        : {id_web_user: id_web_user},
            success     : function(data){

                setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
                $('#id_web_user').val(htmlDecode(data.id_web_user));
                $('#email_web_user').val(htmlDecode(data.email_web_user));
                $('#telp_web_user').val(htmlDecode(data.telp_web_user));
                $('#password_web_user').val(data.password_web_user);

                if(data.active_web_user == '1'){
                    $('#active_web_user').prop('checked', true);
                } else {
                    $('#active_web_user').prop('checked', false);
                }

                if(data.is_admin == '1'){
                    $('#is_admin').prop('checked', true);
                } else {
                    $('#is_admin').prop('checked', false);
                }

                $('.form-active-control').prop('disabled', true);

                $('.modal-button-save').css('display', 'none');
                $('.modal-button-view-only').css('display', 'block');
                $('#master-user-modal').modal('toggle');

                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        })
    });

    $('.add-user').click(function (e) {
        e.preventDefault();
        $('#id_web_user').val(0);
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('.modal-button-view-only').css('display', 'none');
        $('#master-user-form').trigger('reset');
        $('.form-active-control').prop('disabled', false);
        $('#master-user-modal').modal('toggle');
        $('.modal-button-save').css('display', 'block');
    })

    $('.edit-user').click(function(e){
        setTimeout(function() {$('.modal-dialog').scrollTop(0);}, 200);
        $('.modal-button-save').css('display', 'block');
        $('.modal-button-view-only').css('display', 'none');
        $('.form-active-control').prop('disabled', false);
    })

    $('.save-user').click(function(e){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: admin_url + 'add_user', // the url where we want to POST// our data object
            dataType: 'json',
            data: $('#master-user-form').serialize(),
            success: function (response) {
                if(response.Status == "OK"){
                    get_all_user();
                    $('#master-user-modal').modal('hide');
                } else {
                    show_snackbar(response.Message);
                }

                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        })
    })

    $('.random_password').click(function(e){
        e.preventDefault();
        random_password = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < 8; i++ ) {
            random_password += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        $('#password_web_user').val(random_password);
    })


</script>
