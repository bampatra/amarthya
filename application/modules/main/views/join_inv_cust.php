<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Join Invoice (Customer)</h1>
    <br>

    <div class="wrapper">

        <div class="three">
            <h6> Customer </h6>
            <div class="green-line"></div>
            <div id="customer_info" style="font-size: 14px">
            </div>
            <span class="link pilih-customer"> Edit Customer </span>
        </div>

        <div class="three">
            <h6> Daftar Pesanan Dipilih </h6>
            <div class="green-line"></div>

            <div id="pesanan_content">

            </div>

            <button class="btn btn-primary-empty add-order" style="width: 100%;  font-size: 11px; margin-top: 20px;">Tambah Pesanan</button>

        </div>


    </div>
    <br>

    <button class="btn btn-primary generate" style="width: 100%;  font-size: 14px;">Buat Join Invoice</button><br>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="order-modal" style="z-index: 5000">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="orderDataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr class="no-hover-style">
                            <th> Order </th>
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

<div class="modal fade" tabindex="-1" role="dialog" id="customer-modal" style="z-index: 5000">
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
                    <table class="table table-bordered" id="customerDataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th style="display: none;"> ID </th>
                            <th style="display: none;"> No HP </th>
                            <th style="display: none;"> Nama Customer </th>
                            <th style="display: none;"> Alamat Customer </th>
                            <th> Customer </th>
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
        text-align: left;
        display: inline-block;
        font-size: 9px;
    }

    .form-group{
        margin-bottom: 0;
    }

    span{
        font-size: 13px;
    }
</style>

<script>

    document.title = "Join Invoice - Amarthya Group";

    order_lists = [];
    selected_customer = 0;

    $('.add-order').click(function(){
        if(selected_customer == 0){
            show_snackbar('Pilih customer terlebih dahulu');
            return;
        }

        $('#order-modal').modal('toggle');
    })

    $('.pilih-customer').click(function(){
        get_customer();
    })

    function submit_post_via_hidden_form(url, params) {
        var f = $("<form target='_blank' method='POST' style='display:none;'></form>").attr({
            action: url
        }).appendTo(document.body);

        for (var i in params) {
            if (params.hasOwnProperty(i)) {
                $('<input type="hidden" />').attr({
                    name: i,
                    value: params[i]
                }).appendTo(f);
            }
        }

        f.submit();

        f.remove();
    }

    $('.generate').click(function(){

        submit_post_via_hidden_form(
            admin_url + 'pdf_join_inv',
            {
                id_customer: selected_customer,
                order_lists: JSON.stringify(order_lists)
            }
        );

        // $.ajax({
        //     type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
        //     url: admin_url + 'pdf_join_inv', // the url where we want to POST// our data object
        //     dataType: 'json',
        //     data: {
        //         id_customer: selected_customer,
        //         order_lists: order_lists
        //     },
        //     success: function (response) {
        //         if(response.Status == "ERROR" ){
        //             show_snackbar(response.Message);
        //         }
        //
        //         $('.loading').css("display", "none");
        //         $('.Veil-non-hover').fadeOut();
        //
        //     }
        // })
    })


    // ======== ONLY SHOW ORDER WITH STATUS 1-ACTIVE ========
    function get_order(){
        // $('.loading').css("display", "block");
        // $('.Veil-non-hover').fadeIn();

        $('#orderDataTable').DataTable().destroy();
        $('#orderDataTable').DataTable({
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
                url     : admin_url + 'get_order_m?customer=' + selected_customer,
                type    : 'POST'
            },
            createdRow: function ( row, data, index ) {
                // $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {
                    "data": {
                        "id_order_m":"id_order_m",
                        "no_order":"no_order",
                        "tgl_order":"tgl_order",
                        "grand_total_order":"grand_total_order",
                        "ongkir_order":"ongkir_order",
                        "is_paid": "is_paid"
                    },
                    mRender : function(data, type, full) {
                        let dateTimeParts= data.tgl_order.split(/[- :]/);
                        dateTimeParts[1]--;
                        const temp_date = new Date(...dateTimeParts);

                        if(data.is_paid == '0'){
                            payment_status = '<div class="alert alert-danger alert-payment" role="alert">\n' +
                                '                            <strong>BELUM BAYAR</strong>\n' +
                                '                        </div>';
                        } else {
                            payment_status = '<div class="alert alert-success alert-payment" role="alert">\n' +
                                '                            <strong>LUNAS</strong>\n' +
                                '                        </div>';
                        }

                        html = '<span style="font-size: 12px">'+ temp_date.getDate() + '/' + (temp_date.getMonth() + 1) + '/' + temp_date.getFullYear() +'</span><br>' +
                            '   <strong>'+ data.no_order +'</strong><br>' + payment_status + '<br>' +
                            '<span>Total Order: '+ convertToRupiah(data.grand_total_order) +' || Ongkir: '+ convertToRupiah(data.ongkir_order) +'</span>';
                        return html;
                    }
                }

            ],
            initComplete: function (settings, json) {
                // $('#order-modal').modal('toggle');
                // $('.loading').css("display", "none");
                // $('.Veil-non-hover').fadeOut();
            }
        });
    }

    $('#orderDataTable').on( 'click', 'tbody tr', function () {

        master_data = $('#orderDataTable').DataTable().row( this ).data();

        id_order_m = master_data.id_order_m;
        no_order = master_data.no_order;
        tgl_order = master_data.tgl_order;
        grand_total_order = parseFloat(master_data.grand_total_order);
        ongkir_order = parseFloat(master_data.ongkir_order);
        is_paid = master_data.is_paid;
        tipe_order = master_data.tipe_order;
        brand_order = master_data.brand_order;

        order_lists.push({
            id_order_m  : id_order_m,
            no_order    : no_order,
            tgl_order   : tgl_order,
            grand_total_order : grand_total_order,
            ongkir_order  : ongkir_order,
            is_paid     : is_paid,
            brand_order  : brand_order
        });

        refresh_list();
        $('#order-modal').modal('hide');
    });


    function refresh_list(){
        html = '';
        $('#item-lists').html(html);
        order_lists.forEach(function(item, index){

            let dateTimeParts= item.tgl_order.split(/[- :]/);
            dateTimeParts[1]--;
            const temp_date = new Date(...dateTimeParts);

            if(item.is_paid == '0'){
                payment_status = '<div class="alert alert-danger alert-payment" role="alert">\n' +
                    '                            <strong>BELUM BAYAR</strong>\n' +
                    '                        </div>';
            } else {
                payment_status = '<div class="alert alert-success alert-payment" role="alert">\n' +
                    '                            <strong>LUNAS</strong>\n' +
                    '                        </div>';
            }

            html += '<div class="product-item"">\n' +
                '            <table width="100%">\n' +
                '                <tr class="no-hover-style">\n' +
                '<td><span style="font-size: 12px">'+ temp_date.getDate() + '/' + (temp_date.getMonth() + 1) + '/' + temp_date.getFullYear() +'</span><br>' +
                '<strong>' + item.no_order +' </strong> ' + payment_status + '<br>' +
                '<span onclick="delete_item('+ index +')" class="link">Hapus</span>' +
                '</td>' +
                '                    <td style="text-align: right">\n' +
                '                        <strong style="font-size: 13px;">'+ convertToRupiah(item.grand_total_order) +'</strong><br>\n' +
                '                        <span style="font-size: 9px;">Ongkir: '+ convertToRupiah(item.ongkir_order) +'</span>\n' +
                '                    </td>\n' +
                '                </tr>\n' +
                '\n' +
                '            </table>\n' +
                '        </div>';


        })
        $('#pesanan_content').html(html);
    }

    function delete_item(index){
        if(confirm("Hapus order?")){

            order_lists.splice(index, 1);

            refresh_list();
        }
    }

    function get_customer(){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        $('#customerDataTable').DataTable().destroy();
        $('#customerDataTable').DataTable({
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
                url     : admin_url + 'get_customer',
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
                $('td', row).eq(1).css("display", "none");
                $('td', row).eq(2).css("display", "none");
                $('td', row).eq(3).css("display", "none");
            },
            columns: [
                {"data": "id_customer"},
                {"data": "no_hp_customer"},
                {"data": "nama_customer"},
                {"data": "alamat_customer"},
                {
                    "data": {
                        "nama_customer":"nama_customer",
                        "alamat_customer":"alamat_customer"
                    },
                    mRender : function(data, type, full) {
                        html = '<strong>' + data.nama_customer + '</strong><br>' +
                            '   <span>'+ data.alamat_customer +'</span>';

                        return html;

                    }
                }

            ],
            initComplete: function (settings, json) {
                $('#customer-modal').modal('toggle');
                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        });
    }

    $('#customerDataTable').on( 'click', 'tbody tr', function () {
        id_customer = $('#customerDataTable').DataTable().row( this ).data().id_customer;
        no_hp_customer = $('#customerDataTable').DataTable().row( this ).data().no_hp_customer;
        nama_customer = $('#customerDataTable').DataTable().row( this ).data().nama_customer;
        alamat_customer = $('#customerDataTable').DataTable().row( this ).data().alamat_customer;


        html = '<strong>'+ nama_customer +' ('+ no_hp_customer +')</strong><br>' +
            '<span style="font-size: 12px">'+ alamat_customer +'</span>';

        selected_customer = id_customer;

        $('#customer_info').html(html)
        $('#customer-modal').modal('hide');

        get_order();
    });

</script>

