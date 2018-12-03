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
	$product_image = display_image($row['product_image']);
	echo $row['product_price'];

	// using herodec which will allow big strings here of text

	$product =  <<<DELIMETER
	<div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="thumbnail">
                           <a href="item.php?id={$row['product_id']}"> <img src="../resources/{$product_image}" alt=""> </a>
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
	$product_image = display_image($row['product_image']);


	//echo $row['product_price'];

	// using herodec which will allow big strings here of text

	$product =  <<<DELIMETER
	<div class="row text-center">

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="../resources/{$product_image}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <p>
                            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
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
	$product_image = display_image($row['product_image']);
	//echo $row['product_price'];

	// using herodec which will allow big strings here of text

	$product =  <<<DELIMETER
	<div class="row text-center">

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="../resources/{$product_image}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <p>
                            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
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
$uploads = "uploads";
function display_image($picture)
{
	global $uploads;

	return $uploads . DS . $picture;

}

function get_products_in_admin()
{
	
	$query = query("SELECT * FROM products");

confirm($query);

while($row = fetch_array($query))
{
	$category = show_product_category_title($row['product_category_id']);
	//echo $row['product_price']; // to be removed

	// using herodec which will allow big strings here of text
	$product_image = display_image($row['product_image']);
	$product =  <<<DELIMETER
	<tr>
		<td>{$row['product_id']}</td>
		<td>{$row['product_title']}<br>
		<a href="index.php?edit_product&id={$row['product_id']}"><img width = '200' src="../../resources/{$product_image}" alt=""></a>
		</td>
		<td>{$category}</td>
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

function show_product_category_title($product_category_id)
{
$category_query = query("SELECT * FROM Categories WHERE cat_id = '{$product_category_id}'");
confirm($category_query);

while($category_row = fetch_array($category_query))
{
	return $category_row['cat_title'];
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
		if(empty($product_title) || $product_title==" ")
		{
			echo "This cannot be empty";
		}
		else{
		$query = query("INSERT INTO PRODUCTS (product_title, product_category_id, product_price, product_description, short_desc, product_quantity, product_image) VALUES ('{$product_title}', '{$product_category_id}', '{$product_price}', '{$product_description}', '{$short_desc}', '{$product_quantity}', '{$product_image}')");
		confirm($query);
		set_message("New Product Just Added");

		redirect("index.php?products");
		}//else
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


/**** EDIT PRODUCT FUNCTION */


function update_product()
{
	if(isset($_POST['update']))
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

		if(empty($product_image))
		{
			$get_pic = query("SELECT product_image FROM products WHERE product_id= " .escape_string($_GET['id']) ." ");
			confirm($get_pic);

			while($pic = fetch_array($get_pic))
			{
				$product_image = $pic['product_image'];

			}
		}

		move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);

		$query = "UPDATE products SET ";
		$query .= "product_title       = '{$product_title}'       , ";
		$query .= "product_category_id = '{$product_category_id}' , ";
		$query .= "product_price       = '{$product_price }'      , ";
		$query .= "product_description = '{$product_description }', ";
		$query .= "short_desc          = '{$short_desc }'         , ";
		$query .= "product_quantity    = '{$product_quantity }'   , ";
		$query .= "product_image       = '{$product_image }'      ";
		$query .= "WHERE product_id=" .escape_string($_GET['id']);
		
		$send_update_query = query($query);
		confirm($send_update_query);
		set_message("Product has been updated");

		redirect("index.php?products");
	}
}


/*** Categories in admin */

function show_categories_in_admin()
{
	
	$category_query = query("SELECT * from categories");

	confirm($category_query);

	while($row = fetch_array($category_query))
	{
		$cat_id = $row['cat_id'];
		$cat_title = $row['cat_title'];

		$category =  <<<DELIMETER
		<tr>
			<td>{$cat_id}</td>
			<td>{$cat_title}</td>
			<td><a class="btn btn-danger" href="../../resources/templates/back/delete_category.php?id={$row['cat_id']}"> <span class="glyphicon glyphicon-remove"> </span></a></td>
			</tr>
DELIMETER;

echo $category;

	}

}


function add_category()
{
	if(isset($_POST['add_category']))
	{
		$cat_title = escape_string($_POST['cat_title']);

		if(empty($cat_title) || $cat_title==" ")
		{
			echo "This cannot be empty";
		}
		else{
		$insert_catquery = query("INSERT INTO categories (cat_title) VALUES ('{$cat_title}')");

		confirm($insert_catquery);
		redirect("index.php?categories");

		}
	}
}



function display_users()
{
	
	$category_query = query("SELECT * from users");

	confirm($category_query);

	while($row = fetch_array($category_query))
	{
		$user_id = $row['user_id'];
		$username = $row['username'];
		$email = $row['email'];
		$password = $row['password'];

		$user =  <<<DELIMETER
		<tr>
			<td>{$user_id}</td>
			<td>{$username}</td>
			<td>{$email}</td>
			<td><a class="btn btn-danger" href="../../resources/templates/back/delete_user.php?id={$row['user_id']}"> <span class="glyphicon glyphicon-remove"> </span></a></td>
			</tr>
DELIMETER;

echo $user;

	}

}


function add_user()
{
	if(isset($_POST['add_user']))
	{
		$username = escape_string($_POST['username']);
		$email = escape_string($_POST['email']);
		$password = escape_string($_POST['password']);
		//$user_photo = escape_string($_FILES['file']['name']);
		//$photo_temp = escape_string($_FILES['file']['tmp_name']);

		//move_uploaded_file($photo_temp, UPLOAD_DIRECTORY . DS . $user_photo);

		$query = query("INSERT INTO USERS (username,email,password) VALUES ('{$username}','{$email}','{$password}') ");
		confirm($query);

		set_message("USER CREATED");

		redirect("index.php?users");

	}
}



function get_reports()
{
	
	$query = query("SELECT * FROM reports");

confirm($query);

while($row = fetch_array($query))
{
	//$category = show_product_category_title($row['product_category_id']);
	//echo $row['product_price']; // to be removed

	// using herodec which will allow big strings here of text
	//$product_image = display_image($row['product_image']);
	$report =  <<<DELIMETER
	<tr>
		<td>{$row['report_id']}</td>
		<td>{$row['product_id']}</td>
		<td>{$row['order_id']}</td>
		<td>{$row['product_price']}</td>
		<td>{$row['product_quantity']}</td>
		<td><a class="btn btn-danger" href="../../resources/templates/back/delete_report.php?id={$row['report_id']}"> <span class="glyphicon glyphicon-remove"> </span></a></td>
	</tr>

DELIMETER;
// no space at start or end of delimeter
// ? after url means to send a parameter

echo $report;
}

}

function admin_content()
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


