
<div class="container-fluid mobile-only">

    <div class="desktop-and-tablet" style="height: 25px;"></div>

<!--    <select id="table_num" name="table_num" class="form-control form-active-control form-control-sm selectpicker" data-live-search="true">-->
<!--        <option value="none"> -- Pilih Table -- </option>-->
<!--    </select>-->

    <table style="width: 100%; background-color: white; text-align: center; margin-top: 20px; height: 35px;">
        <tr>
            <td style="width: 33.3%" class="category selected">Food</td>
            <td style="width: 33.3%; margin-left: 1px;" class="category">Drink</td>
            <td style="width: 33.3%; margin-left: 1px;" class="category">Others</td>
        </tr>

    </table>

    <br>

    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody id="main-content">

        </tbody>
    </table>

</div>

<style>
    .category.selected{
        border-bottom: 1px solid rgba(20,143,143,1);
        color: rgba(20,143,143,1);
        transition: border 0.15s ease-in-out, color 0.15s ease-in-out;
    }

    .category{
        border-bottom: 1px solid transparent;
        color: black;
    }

     .dataTable > thead > tr > th[class*="sort"]::after{display: none}
     .dataTable > thead > tr > th[class*="sort"]::before{display: none}

</style>


<script>
    document.title = "Order Portal - Amarthya Eatery";

    $('.category').click(function(){
        $('.category').removeClass('selected');
        $(this).addClass('selected');
    })

    $('#dataTable').DataTable().destroy();
    $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        lengthChange: false,
        searching: true,
        bInfo: false,
        bSort: false,
        ordering: false,
        orderable: false,
        language: {
            search: ""
        },
        pagingType: "simple",
        ajax: {
            url     : '<?php echo base_url('main/get_product?brand=AHF');?>',
            type    : 'POST',
        },
        createdRow: function ( row, data, index ) {
            // $('td', row).eq(0).css("display", "none");
        },
        columns: [
            {"data": "nama_product"}

        ],
        initComplete: function (settings, json) {
            $('.loading').css("display", "none");
            $('.Veil-non-hover').fadeOut();
        }
    });

</script>