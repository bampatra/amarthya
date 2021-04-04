<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <input type="file" id="excel">
    <button id="upload"> Upload </button>
</head>
<body>

</body>
</html>

<script>
    $('#upload').click(function(){
        var formData =new FormData();
        formData.append('excel', $('#excel')[0].files[0]); //use get('files')[0]

        $.ajax({
            url : '<?php echo base_url('main/process_excel')?>',
            dataType : 'json',
            cache : false,
            contentType : false,
            processData : false,
            data : formData, //formdata will contain all the other details with a name given to parameters
            type : 'post',
            success : function(response) {
                console.log(response)
            }
        });

    })

</script>