

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
            <br class="desktop-only">
          <div class=" align-items-center justify-content-between mb-4">
              <?php if($this->session->userdata('is_admin') == "1") { ?>
              <div class="row">


                  <!-- Earnings (Monthly) Card Example -->
                  <div class="col-xl-6 col-md-6 mb-4" onclick="window.open('<?php echo base_url('main/laporan_transaksi')?>')">
                      <div class="card border-left-warning shadow h-100 py-2">
                          <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                  <div class="col mr-2">
                                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Penjualan Hari Ini (tanpa ongkir)</div>
                                      <div class="h6 mb-0 font-weight-bold text-gray-800"><?php echo "Rp. " . number_format($dashboard_data[0]->data,2,',','.'); ?></div>
                                  </div>
                                  <div class="col-auto">
                                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>


                  <!-- Earnings (Monthly) Card Example -->
                  <div class="col-xl-6 col-md-6 mb-4" onclick="window.open('<?php echo base_url('main/laporan_transaksi')?>')">
                      <div class="card border-left-warning shadow h-100 py-2">
                          <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                  <div class="col mr-2">
                                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Penjualan Bulan Ini (tanpa ongkir)</div>
                                      <div class="row no-gutters align-items-center">
                                          <div class="col-auto">
                                              <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo "Rp. " . number_format($dashboard_data[1]->data,2,',','.'); ?></div>
                                          </div>
                                          <div class="col">

                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-auto">
                                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

              </div>

              <?php } ?>

              <div class="row">

                  <!-- Earnings (Monthly) Card Example -->
                  <div class="col-xl-3 col-md-6 mb-4" onclick="window.open('<?php echo base_url('main/delivery_list?status=0')?>')">
                      <div class="card border-left-danger shadow h-100 py-2">
                          <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                  <div class="col mr-2">
                                      <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Belum Delivery</div>
                                      <div class="h6 mb-0 font-weight-bold text-gray-800"><?php echo $dashboard_data[2]->data ?></div>
                                  </div>
                                  <div class="col-auto">
                                      <i class="fas fa-motorcycle fa-2x text-gray-300"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Earnings (Monthly) Card Example -->
                  <div class="col-xl-3 col-md-6 mb-4" onclick="window.open('<?php echo base_url('main/pick_up_list?status=0')?>')">
                      <div class="card border-left-danger shadow h-100 py-2">
                          <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                  <div class="col mr-2">
                                      <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Belum Pick Up</div>
                                      <div class="h6 mb-0 font-weight-bold text-gray-800"><?php echo $dashboard_data[3]->data ?></div>
                                  </div>
                                  <div class="col-auto">
                                      <i class="fas fa-motorcycle fa-2x text-gray-300"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Earnings (Monthly) Card Example -->
                  <div class="col-xl-3 col-md-6 mb-4" onclick="window.open('<?php echo base_url('main/order_list?status=0')?>')">
                      <div class="card border-left-danger shadow h-100 py-2">
                          <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                  <div class="col mr-2">
                                      <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Pesanan Belum Bayar</div>
                                      <div class="row no-gutters align-items-center">
                                          <div class="col-auto">
                                              <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $dashboard_data[4]->data ?></div>
                                          </div>
                                          <div class="col">
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-auto">
                                      <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Earnings (Monthly) Card Example -->
                  <div class="col-xl-3 col-md-6 mb-4" onclick="window.open('<?php echo base_url('main/order_vendor_list?status=0')?>')">
                      <div class="card border-left-danger shadow h-100 py-2">
                          <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                  <div class="col mr-2">
                                      <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Vendor Belum Dibayar</div>
                                      <div class="row no-gutters align-items-center">
                                          <div class="col-auto">
                                              <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $dashboard_data[5]->data ?></div>
                                          </div>
                                          <div class="col">

                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-auto">
                                      <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

              </div>

              <?php if($this->session->userdata('is_admin') == "1") { ?>

              <div class="three">
                  <h6> Problem </h6>
                  <div class="green-line"></div>
                  <div class="card-body">
                      <div class="table-responsive">
                          <table class="table table-bordered" id="dataTableProblem" width="100%" cellspacing="0">
                              <thead>
                              <tr class="no-hover-style">
                                  <th style="width: 30%"> Kode </th>
                                  <th> Problem </th>
                              </tr>
                              </thead>
                              <tbody id="main-content">

                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>

              <?php }?>

              <div class="three">
                  <h6> Pendapatan Ongkir </h6>
                  <div class="green-line"></div>
                  <div class="card-body">
                      <div class="table-responsive">
                          <table class="table table-bordered" width="100%" cellspacing="0">
                              <thead>
                              <tr class="no-hover-style">
                                  <th> Staff </th>
                                  <th> Pendapatan Ongkir </th>
                              </tr>
                              </thead>
                              <tbody>
                              <?php
                                foreach($ongkir_data as $data){
                                    echo "<tr><td> $data->nama_staff </td>
                                            <td>Rp. " . number_format($data->ongkir_salary,2,',','.')."</td></tr>";
                                }

                              ?>

                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>



<style>
    .card:hover{
        cursor: pointer;
    }
</style>
<script>

    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    <?php if($this->session->userdata('is_admin') == "1") { ?>
        get_problem_solving('unsolved');
    <?php } ?>

    function get_problem_solving(status = "all"){

        $('#dataTableProblem').DataTable().destroy();
        $('#dataTableProblem').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            searching: false,
            bInfo: false,
            language: {
                search: ""
            },
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_problem_solving?status=' + status,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                // $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {
                    "data":{
                        "kode_problem_solving": "kode_problem_solving",
                        "solusi_problem_solving": "solusi_problem_solving"
                    },
                    mRender : function(data, type, full) {
                        if(data.solusi_problem_solving == ""){
                            html = '<div class="alert alert-danger alert-payment" role="alert">\n' +
                                '                            <strong>' + data.kode_problem_solving + '</strong>\n' +
                                '                        </div>';
                        } else {
                            html = data.kode_problem_solving;
                        }

                        return html;
                    }

                },
                {
                    "data": {
                        "topik_problem_solving":"topik_problem_solving",
                        "detail_problem_solving":"detail_problem_solving",
                        "timestamp_create":"timestamp_create",
                        "username_create":"username_create"
                    },
                    mRender : function(data, type, full) {
                        html = '<strong style="font-size: 12px">'+ data.topik_problem_solving +'</strong><br>' +
                            '   <span>'+ data.detail_problem_solving +'</span><br>' +
                            '   <i style="font-size: 9px;">'+ data.timestamp_create +' oleh '+ data.username_create +'</i>';

                        return html;
                    }
                }

            ],
            initComplete: function (settings, json) {

            }
        });
    }


</script>

