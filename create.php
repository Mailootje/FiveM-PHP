<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$citizenid = $name = $money = "";
$citizenid_err = $name_err = $money_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate citizenid
    $input_citizenid = trim($_POST["citizenid"]);
    if(empty($input_citizenid)){
        $citizenid_err = "Please enter a citizenid.";
    } elseif(!filter_var($input_citizenid, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $citizenid_err = "Please enter a valid citizenid.";
    } else{
        $citizenid = $input_citizenid;
    }
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter an name.";     
    } else{
        $name = $input_name;
    }
    
    // Validate money
    $input_money = trim($_POST["money"]);
    if(empty($input_money)){
        $money_err = "Please enter the money amount.";     
    } elseif(!ctype_digit($input_money)){
        $money_err = "Please enter a positive integer value.";
    } else{
        $money = $input_money;
    }
    
    // Check input errors before inserting in database
    if(empty($citizenid_err) && empty($name_err) && empty($money_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO players (citizenid, name, money) VALUES (:citizenid, :name, :money)";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":citizenid", $param_citizenid);
            $stmt->bindParam(":name", $param_name);
            $stmt->bindParam(":money", $param_money);
            
            // Set parameters
            $param_citizenid = $citizenid;
            $param_name = $name;
            $param_money = $money;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>citizenid</label>
                            <input type="text" citizenid="citizenid" class="form-control <?php echo (!empty($citizenid_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $citizenid; ?>">
                            <span class="invalid-feedback"><?php echo $citizenid_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>name</label>
                            <textarea citizenid="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>"><?php echo $name; ?></textarea>
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>money</label>
                            <input type="text" citizenid="money" class="form-control <?php echo (!empty($money_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $money; ?>">
                            <span class="invalid-feedback"><?php echo $money_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>