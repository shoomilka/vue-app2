<?php

require_once('connect.php');

$code = '';
$errors = [];
if(!empty($_POST)){
    // put all rules to $rules array, $minimum and $maximum variables
    include('rules.php');
    
    $code = strval($_POST['code']);
    if((strlen($code) < $minimum) && ($minimum > 0)){
        array_push($errors, "Minimum amount of characters is " . $minimum);
    }
    if((strlen($code) > $maximum) && ($maximum > 0)){
        array_push($errors, "Maximum amount of characters is " . $maximum);
    }
    foreach($rules as $rule){
        if($rule['type'] == 'number'){
            if(!preg_match('/\d/', substr($code, $rule['position']-1, 1))) {
                array_push($errors, "Character on " . $rule['position'] . "th position must be a number");
            }
        } elseif($rule['type'] == 'letter'){
            if(!preg_match('/[a-zA-Z]/', substr($code, $rule['position']-1, 1))) {
                array_push($errors, "Character on " . $rule['position'] . "th position must be a letter");
            }
        } elseif($rule['type'] == 'symbol'){
            if($rule['symbol'] != substr($code, $rule['position']-1, 1)) {
                array_push($errors, "Character on " . $rule['position'] . "th position must be a letter " . $rule['symbol']);
            }
        }
    }
}

$conn->close();
?>

</head>
<body>
    <div class="container">
        <div vlass="row">
            <div class="col-md-8">
                <h1 class="mt-4">Validation Page</h1>

        <form method="post">
            <div class="form-row mt-4">
                <div class="col">
                    <input type="text" class="form-control" name="code" value="<?php echo $code ?>">
                </div>
                <div class="col">
                    <input type="submit" class="btn btn-light" value="Check it">
                </div>
            </div>
        </form>

        <ul class="list-group mt-4">
            <?php foreach($errors as $error) { ?>
                <li class="list-group-item list-group-item-danger"><?php echo $error; ?></li>
            <?php } if(empty($errors)) { ?>
                <li class="list-group-item list-group-item-success">Code is valid</li>
            <?php } ?>
        </ul>

            </div>
        </div>
    </div>
</body>