<?php
require_once ( 'QR_BarCode.php' );
//include "QR_BarCode.php"; 

// QR_BarCode object 
$qr = new QR_BarCode(); 

// create text QR code 
$qr->text('PB-81451570037975'); 

// create url QR code 
// $qr->url('URL');

// create text QR code 
// $qr->text('textContent');

// create email QR code 
// $qr->email('emailAddress', 'subject', 'message');

// create phone QR code 
// $qr->phone('phoneNumber');

// create sms QR code 
// $qr->sms('phoneNumber', 'message');

// create contact QR code 
// $qr->contact('name', 'address', 'phone', 'email');

// create content QR code 
// $qr->content('type', 'size', 'content');

// display QR code image
$qr->qrCode();

// $qr->qrCode(350,'images/cw-qr.png');