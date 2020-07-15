<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" type="text/css">
    <title>ProbaFeladat2</title>
</head>
<body>

<?php 
$amountError = $expirationError = $creditCardError = "";
$amount = $year = $month = $creditCard = "";
$currentYear = date('Y');
$currentMonth = abs(date('m'));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $valid = true;

  if(empty($_POST["amount"])) {
    $amountError = "Exchange amount is required.";
    $valid = false;
  } else {
    $amount = testInputData($_POST["amount"]);
    if($_POST["amount"] > 1000000) {
      $amountError = "You have exceeded the maximum amount (1000000). Please try again.";
      $valid = false;
    }
  }

  if(empty($_POST["year"]) or empty($_POST["month"])) {
    $expirationError = "Expiration date is required.";
    $valid = false;
  } else { 
    $year = testInputData($_POST["year"]);
    $month = testInputData($_POST["month"]);
     if (($_POST["year"] - $currentYear < 0) or (($_POST["year"] - $currentYear == 0) and ($_POST["month"] - $currentMonth < 0))) {
      $expirationError = "It seems that your credit card has expired. Please try again.";
      $valid = false;
     }
  }

  if(empty($_POST["creditCard"])) {
    $creditCardError = "Credit card number is required";
    $valid = false;
  } else {
    $creditCard = testInputData($_POST["creditCard"]);
    if(luhnCheckForCreditCard($_POST["creditCard"]) == FALSE) {
      $creditCardError = "You have entered an invalid credit card number. Please try again.";
      $valid = false;
    }
  }
}

if($valid){
  header('Location: http://localhost/ProbaFeladat2/exchange.php?amount=' . $_POST["amount"]);
  exit();
 }

function testInputData($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function luhnCheckForCreditCard($number) {
    $number_length=strlen($number);
    $parity=$number_length % 2;
    $total=0;
    for ($i=0; $i<$number_length; $i++) {
      $digit=$number[$i];
      if ($i % 2 == $parity) {
        $digit*=2;
        if ($digit > 9) {
          $digit-=9;
        }
      }
      $total+=$digit;
    }
    return ($total % 10 == 0) ? TRUE : FALSE;
    }
?>

<h2>Payment form</h2>
<p><span class="error">* Required field</span></p>

<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Please enter your credit card number:
    <input type="number"
     name="creditCard" value="<?php echo $creditCard;?>" 
     min="1"
     placeholder="Credit card number"
     onKeyPress="if(this.value.length == 16) return false;" 
     oninput="this.value = Math.abs(this.value)" >
     <span class="error">*</span>
     <br>
     <span class="error"><?php echo $creditCardError;?></span>

     <br>
    Please enter the expiration date. Month:
    <input class="date" type="number"
     name="month"
     placeholder="mm"
     value="<?php echo $month;?>"
     onKeyPress="if(this.value.length == 2) return false;"
     min="1" max="12"
     size="2"
     oninput="this.value = Math.abs(this.value)"> Year:

    <input class="date" type="number"
     name="year"
     value="<?php echo $year;?>"
     placeholder="yyyy"
     onKeyPress="if(this.value.length == 4) return false;"
     min="1"
     size="4"
     oninput="this.value = Math.abs(this.value)">
     <span class="error">*</span>
     <br>
     <span class="error"><?php echo $expirationError;?></span>
     <br>

    Please enter the amount to be exchanged:
    <input type="number"
     value="<?php echo $amount;?>"
     name="amount"
     placeholder="Amount to be exchanged"
     min="1"
     oninput="this.value = Math.abs(this.value)">
     <span class="error">*</span>
     <br>
     <span class="error"><?php echo $amountError;?></span>
     <br>
    <button class="button" type="submit">Submit</button>
    </form>
  </body>
</html>