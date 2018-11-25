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
                                <a class="btn btn-primary" target="_blank" href="cart.php?add={$row['product_id']}">Add to Cart</a>
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


?>