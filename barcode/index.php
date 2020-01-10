<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>BarCode</title>
</head>
<body>
<?php
require_once('BarcodeGeneratorPNG.php');
$generator = new BarcodeGeneratorPNG();
echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode('081231723897', $generator::TYPE_CODE_128)) . '">';
$img = "data:image/png;base64," . base64_encode($generator->getBarcode('081231723897', $generator::TYPE_CODE_128));
copy($img,"images/081231723897.png");
?>
</body>
</html>