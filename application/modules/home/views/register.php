<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <link rel="icon" href="Favicon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <title>Register User</title>
</head>
<body>



<main class="login-form">
    <div class="cotainer">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Register User</div>
                    <div class="card-body">
                        <div class="alert alert-danger" role="alert" style="display: none">
                            Silahkan isi form dengan benar!
                        </div>
                        <form id="add-user-form">
                            <div class="form-group row" >
                                <label for="staff" class="col-md-4 col-form-label text-md-right">Staff</label>
                                <div class="col-md-6">
                                    <select id="id_staff" name="id_staff" class="form-control form-active-control selectpicker" data-live-search="true">
                                        <option value="none"> -- Pilih Staff -- </option>
                                        <?php foreach ($staffs as $staff) { ?>
                                            <option value="<?php echo $staff->id_staff; ?>">
                                                <?php echo $staff->nama_staff; ?>
                                            </option>


                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="username" class="col-md-4 col-form-label text-md-right">Username</label>
                                <div class="col-md-6">
                                    <input type="text" id="username" class="form-control" name="username" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input type="password" id="password" class="form-control" name="password" required>
                                </div>
                            </div>

                        </form>

                    <div class="col-md-6 offset-md-4">
                        <button class="btn btn-primary" id="login-btn">
                            Register
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</main>

</body>
</html>

<style>

    .my-form .row
    {
        margin-left: 0;
        margin-right: 0;
    }

    .login-form
    {
        font-family: Raleway, sans-serif;
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }

    .login-form .row
    {
        margin-left: 0;
        margin-right: 0;
    }
</style>
<script>

    $('#login-btn').click(function(e){
        $('#login-btn').attr("disabled", true);
        e.preventDefault();
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : '<?php echo base_url('home/')?>' + 'add_user', // the url where we want to POST
            data        : $('#add-user-form').serialize(), // our data object
            dataType    : 'json',
            success     : function(response){
                if(response.Status == 'OK'){
                    html_success = '<div class="alert alert-success" role="alert" style="display: block">\n' +
                        '                            Berhasil! Data sudah ditambahkan.\n' +
                        '                        </div>';
                    $('.card-body').html(html_success);
                    $('.alert-danger').css('display', 'none');
                } else if(response.Status == 'ERROR'){
                    $('.alert-danger').css('display', 'block');
                    $('#login-btn').attr("disabled", false);

                }
            }
        })
    })

</script>
