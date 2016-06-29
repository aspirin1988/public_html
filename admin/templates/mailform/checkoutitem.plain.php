Order Form
<?php echo $mail_body ?>

*************************

Order information
Date : <?php echo date('Y/m/d H:i:s', time()) ."\r\n"?>
Currency : <?php echo $order_currencyCode . "\r\n"?>
*************************
<?php
$templateItem = '    ID : {%id%}
    Name : {%name%}
    Unit Price : {%unitPrice%}
    Quantity : {%qty%}
    Subtotal : {%subTotal%}
---------------
';
$total = array(
	'qty' => 0,
	'amount' => 0,
);
$icount = count($order_productsList);
for($i = 0; $i < $icount; $i++)
{
	$item = $order_productsList[$i];
	$total['amount'] += $item['subTotal'] = $item['qty'] * $item['unitPrice'];
	$total['qty'] += $item['qty'];
	$row = str_replace(explode(',', '{%' . implode("%},{%", array_keys($item)) . '%}'), array_values($item), $templateItem);
	echo $row;
}
?>
*************************
Total
    Quantity : <?php echo $total['qty'] . "\r\n"?>
    Amount : <?php echo $total['amount'] . "\r\n"?>
