<?php



// if($connection)
// {
// 	echo "connected established";
// }
// helper functions

function set_message($msg)
{
	if(!empty($msg))
	{
		$_SESSION['message'] = $msg; //assigned to message

	}
	else{
		$msg = "";
	}

}

function display_message()
{
	if(isset($_SESSION['message'])){
		echo $_SESSION['message'];
		unset($_SESSION['message']);
	} // check if session is available
}

function redirect($location)
{
	header("Location: $location ");
}
//<!-- whenevr we need it will be redirected (we used variable name) -->

function query($sql)
{
	global $connection;

	return mysqli_query($connection,$sql);
}

function confirm($result)
{
	global $connection;

	if(!$result)
	{
		die("QUERY FAILED " . mysqli_error($connection));
	}

}

function escape_string($string)
{
	global $connection;

	return mysqli_real_escape_string($connection,$string);
}

function fetch_array($result)
{
	return mysqli_fetch_array($result);
}

// get products  (so we can add values and thumbnail)

/******************** FRONT END FUNCTIONS */




function get_products()
{
$query = query("SELECT * FROM products");

confirm($query);

while($row = fetch_array($query))
{
	echo $row['product_price'];

	// using herodec which will allow big strings here of text

	$product =  <<<DELIMETER
	<div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="thumbnail">
                           <a href="item.php?id={$row['product_id']}"> <img src="{$row['product_image']}" alt=""> </a>
                            <div class="caption">
                                <h4 class="pull-right">{$row['product_price']}RS</h4>
                                <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                                </h4>
                                <p>wait for more <a target="_blank" href="http://www.google.com">DB project xD</a>.</p>
                                <a class="btn btn-primary" target="_blank" href="../resources/cart.php?add={$row['product_id']}">Add to Cart</a>
                          </div>
                        </div>
                    </div>

DELIMETER;
// no space at start or end of delimeter
// ? after url means to send a parameter

echo $product;
}
}



function get_products_in_cat_page()
{
$query = query("SELECT * FROM products where product_category_id= " .escape_string($_GET['id']) . "");

confirm($query);

while($row = fetch_array($query))
{
	//echo $row['product_price'];

	// using herodec which will allow big strings here of text

	$product =  <<<DELIMETER
	<div class="row text-center">

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="{$row['product_image']}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <p>
                            <a href="#" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>

DELIMETER;
// no space at start or end of delimeter
// ? after url means to send a parameter

echo $product;
}
}


function get_products_in_shop_page()
{
$query = query("SELECT * FROM products");

confirm($query);

while($row = fetch_array($query))
{
	//echo $row['product_price'];

	// using herodec which will allow big strings here of text

	$product =  <<<DELIMETER
	<div class="row text-center">

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="{$row['product_image']}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <p>
                            <a href="#" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>

DELIMETER;
// no space at start or end of delimeter
// ? after url means to send a parameter

echo $product;
}
}


function get_categories()
{
	/*$query = "SELECT * FROM categories";
        $send_query = mysqli_query($connection,$query);

        if(!$send_query)
        {
            die("QUERY FAILED" . mysqli_error($connection));
        }

        while($row = mysqli_fetch_array($send_query))
        {
            echo "<a href='' class='list-group-item'>{$row['cat_title']}</a>";
            // cat title is attribute name
		}
	*/
	$query = query("SELECT * FROM categories");
	confirm($query);
	while($row = fetch_array($query))
	{
		//echo "<a href='' class='list-group-item'>{$row['cat_title']}</a>";
		// cat title is attribute name
		// he used category_links as variable in place of $product 
		$cat_links =  <<<DELIMETER
		<a href='category.php?id={$row['cat_id']}'class='list-group-item'>{$row['cat_title']}</a>
DELIMETER;
		echo $cat_links;
	}
	
}


function login_user(){
	if(isset($_POST['submit']))
	{	
		// sql injection saving by using escape_string
		$username  = escape_string($_POST['username']);
		$password = escape_string($_POST['password']);

	$query = query("SELECT * FROM users where username = '{$username}' AND password = '{$password}' ");
	// learning alot more :p because I've made a lot of mistakes xD thanks FAST xD
	confirm($query);

	// anything found?
	
	if(mysqli_num_rows($query) == 0) 
	{
		set_message("Maaf Kijiye! apka password or username is not durast xD");	
		redirect("login.php"); // it will redirect to the page since that username or password isn't in database
	}
	else{
		$_SESSION['username'] = $username;
		//set_message("Welcome to Admin {$username}");
		redirect("admin");
	}


	}
}



function send_message()
{
	if(isset($_POST['submit']))
	{
		$to = "amirqadir@live.com";
		$from_name    = $_POST['name'];
		$subject = $_POST['subject'];
		$email   = $_POST['email'];
		$message = $_POST['message'];

		$headers = "From: {$from_name} {$email}";

		$result = mail($to, $subject, $message, $headers); // not reliable

		if(!$result)
		{
			set_message("Sorry! we could not send your message");
			redirect("contact.php");
		}
		else
		{
			set_message("Your message has been sent");
			redirect("contact.php");
		}



	}
}

/******************** BACK END FUNCTIONS */

function display_orders()
{
	$query = query("SELECT * FROM ORDERS");
	confirm($query);

	while($row = fetch_array($query))
	{
		$orders = <<<DELIMETER
		<tr>
		<td>{$row['order_id']}</td>
		<td>{$row['order_amount']}</td>
		<td>{$row['order_transaction']}</td>
		<td>{$row['order_currency']}</td>
		<td>{$row['order_status']}</td>
		<td><a class="btn btn-danger" href="../../resources/templates/back/delete_order.php?id={$row['order_id']}"> <span class="glyphicon glyphicon-remove"> </span></a></td>
   </tr>

DELIMETER;
		echo $orders;
	}
}
/*********** ADMIN PRODUCTS */
function get_products_in_admin()
{
	
	$query = query("SELECT * FROM products");

confirm($query);

while($row = fetch_array($query))
{
	echo $row['product_price'];

	// using herodec which will allow big strings here of text

	$product =  <<<DELIMETER
	<tr>
		<td>{$row['product_id']}</td>
		<td>{$row['product_title']}<br>
		<a href="index.php?edit_product&id={$row['product_id']}"><img src="{$row['product_image']}" alt=""></a>
		</td>
		<td>Category</td>
		<td>{$row['product_price']}</td>
		<td>{$row['product_quantity']}</td>
		<td><a class="btn btn-danger" href="../../resources/templates/back/delete_product.php?id={$row['product_id']}"> <span class="glyphicon glyphicon-remove"> </span></a></td>
	</tr>

DELIMETER;
// no space at start or end of delimeter
// ? after url means to send a parameter

echo $product;
}

}

/** add products in admin */

function add_product()
{
	if(isset($_POST['publish']))
	{
		//echo "IT Works";
		$product_title =  escape_string($_POST['product_title']);
		$product_category_id =  escape_string($_POST['product_category_id']);
		$product_price =  escape_string($_POST['product_price']);
		$product_description =  escape_string($_POST['product_description']);
		$short_desc =  escape_string($_POST['short_desc']);
		$product_quantity =  escape_string($_POST['product_quantity']);
		$product_image = escape_string($_FILES['file']['name']);
		$image_temp_location = escape_string($_FILES['file']['tmp_name']);

		move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);

		$query = query("INSERT INTO PRODUCTS (product_title, product_category_id, product_price, product_description, short_desc, product_quantity, product_image) VALUES ('{$product_title}', '{$product_category_id}', '{$product_price}', '{$product_description}', '{$short_desc}', '{$product_quantity}', '{$product_image}')");
		confirm($query);
		set_message("New Product Just Added");

		redirect("index.php?products");
	}
}

function show_categories_add_product_page()
{
	/*$query = "SELECT * FROM categories";
        $send_query = mysqli_query($connection,$query);

        if(!$send_query)
        {
            die("QUERY FAILED" . mysqli_error($connection));
        }

        while($row = mysqli_fetch_array($send_query))
        {
            echo "<a href='' class='list-group-item'>{$row['cat_title']}</a>";
            // cat title is attribute name
		}
	*/
	$query = query("SELECT * FROM categories");
	confirm($query);
	while($row = fetch_array($query))
	{
		//echo "<a href='' class='list-group-item'>{$row['cat_title']}</a>";
		// cat title is attribute name
		// he used category_links as variable in place of $product 
		$categories_options =  <<<DELIMETER
		<option value="{$row['cat_id']}">{$row['cat_title']}</option>

DELIMETER;
		echo $categories_options;
	}
	
}


?>


