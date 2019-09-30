<?php
	//print_r($_POST);
	
	$form_mode = form_param('mode');
	
	// add single item to cart
	if($form_mode == "add"){
		if(!cart_item_exists(form_param('product_id'))){
			// add to cart;
			$cart_record = array();
			$cart_record['product_id'] = form_param('product_id');
			//$cart_record['product_name'] = form_param('product_name');
			$cart_record['user_id'] = (int)$_SESSION['user_id'];
			$cart_record['*order_date'] = 'NOW()';
			$cart_record['order_source'] = 'product_page';
			$cart_record['status'] = "1";
			$cart_result = db_insert('cart', $cart_record, $cartId);
		}
	}
	
	// add multiple items to cart
	if($form_mode == "list"){
		foreach ($_POST['products'] as $product_id){
			if(!cart_item_exists($product_id)){
				// add to cart;
				$cart_record = array();
				$cart_record['product_id'] = $product_id;
				//$cart_record['product_name'] = $product_name;
				$cart_record['user_id'] = (int)$_SESSION['user_id'];
				$cart_record['*order_date'] = 'NOW()';
				$cart_record['order_source'] = 'quick_list';
				$cart_record['status'] = "1";
				$cart_result = db_insert('cart', $cart_record, $cartId);
			}
		}
		// output
	}
	
	// delete single item from cart
	if($form_mode == "remove"){
		// delete from cart
		$sql = "DELETE FROM  cart 
					WHERE cart_id = ".form_param('cart_id')."
					AND user_id = ".(int)$_SESSION['user_id'];
		//echo  $sql;
		db_query($sql);
	}

	// delete all items from cart
	if($form_mode == "drop"){
		// delete all from cart
		$sql = "DELETE FROM  cart WHERE user_id = ".(int)$_SESSION['user_id'];
		//echo  $sql;
		db_query($sql);
	}
		
	header("Location: ".BASE_WEB.form_param('source'));
	
?>