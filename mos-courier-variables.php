<?php
$page_list = array(
	'dashboard' => 'Dashboard', 
	'company-manage' => 'All Branch', 
	'company-edit' => 'Add Branch', 
	'transaction' => 'Transaction', 
	'expense-manage' => 'All Expense', 
	'expense-edit' => 'Add Expense', 
	'expense-category' => 'Add Category', 
	'expense-bulk' => 'Import Expense',
	'user-manage' => 'All User', 
	'user-edit' => 'Add User', 
	'user-bulk' => 'Import User', 
	'order-manage' => 'All Order', 
	'order-edit' => 'Add Order', 
	'order-bulk' => 'Import Order', 
	'check-in' => 'Check In', 
	'check-out' => 'Check Out', 
	'bill-pay' => 'Bill Pay', 
	'daily-cash' => 'Daily Cash', 
	'report' => 'Report',
	'edit-profile' => 'Profile',
	'settings' => 'Settings',
	'settings-area' => 'Area Setup',
);	
$page_slug = (isset($_GET['page'])) ? $_GET['page'] : 'dashboard';
$order_status_arr = array(
	// 'pending' => 'pending',
	// 'inhouse' => 'Received by branch',
	// 'ready' => 'Chalan Printed',
	// 'way' => 'way',
	// 'delivered' => 'delivered',
	// 'cencle' => 'Cencled the order',

	'pending' => 'pending',
	'received' => 'received',
	// 'Received by branch' => 'Received by branch',
	// 'Chalan Printed' => 'Chalan Printed',
	'hold' => 'hold',
	'way' => 'way',
	'delivered' => 'delivered',
	'pdelivered' => 'pdelivered',
	'returned' => 'returned',
);
$payment_status_arr = array(
	'unpaid' => 'unpaid',
	// 'Paid by receiver' => 'Paid by receiver',
	// 'Pertially Paid by receiver' => 'Pertially Paid by receiver',
	// 'Pertially paid to Marchent' => 'Pertially paid to Marchent',
	'paid' => 'paid',
);
$religion_arr = array(
    'Islam' => 'Islam',
    'Hindu' => 'Hindu',
    'Chiristan' => 'Chiristan',
    'Boudhho' => 'Boudhho',
    'Others' => 'Others',
);
$gender_arr = array(
	'Male' => 'Male',
	'Female' => 'Female',
	'Others' => 'Others',
);
$user_role_arr['employee'] = array(
	'Operator' => 'Operator',
	'Delivery Man' => 'Delivery Man',
	'Driver' => 'Driver',
	'Loader' => 'Loader',
	'Manager' => 'Manager',
	'Owner' => 'Owner',
	'Other' => 'Other',
);
$user_role_arr['marchent'] = array(
    'Regular' => 'Regular',
    'Corporate' => 'Corporate',
);
$user_activation = array(
	'Active' => 'Active',
	'Deactive' => 'Deactive'
);
$payment_methods = array(
	'Bank' => 'Bank',
	'BKash' => 'BKash',
	'Cash' => 'Cash',
);




