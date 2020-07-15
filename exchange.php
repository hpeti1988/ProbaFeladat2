<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css" type="text/css">
  <title>ProbaFeladat2</title>
</head>
<body>
<h2>Exchange value</h2>
  <?php

    $amount = $_GET["amount"];

  function convertCurrency($amount, $fromCurrency, $toCurrency){
    $apikey = '5d2cf53d2ed461542f0b';
    $fromCurrency = urlencode($fromCurrency);
    $toCurrency = urlencode($toCurrency);
    $query =  "{$fromCurrency}_{$toCurrency}";
    $json = file_get_contents("https://free.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");
    $obj = json_decode($json, true);
    $val = floatval($obj["$query"]);
    $total = $val * $amount;
    return number_format($total, 2, '.', '');
  }
  
  ?>
<div class="center">
<?php echo "The amount of " . $amount . " " . " HUF is equal to " . convertCurrency($amount, 'HUF', 'EUR') . " " . "EUR"; ?>
<br>
<button class="button">
    <a href="<?php echo $_SERVER['HTTP_REFERER'] ?>">New Exchange</a>
  </button>
</div>
</body>
</html>

