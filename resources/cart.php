<?php require_once("config.php "); ?>


<?php

    if(isset($_GET['add']))
    {
        // echo "I am working";
        // $_SESSION['product_'. $_GET['add']] += 1;

        // redirect("index.php");

        $query = query("SELECT * from products where product_id=" . escape_string($_GET['add']). "");
        confirm($query);

        while($row = fetch_array($query))
        {
            if($row['product_quantity'] != $_SESSION['product_' . $_GET['add']])
            {
                $_SESSION['product_' . $_GET['add']] += 1;
                redirect("../public/checkout.php");
                // changed and moved to resources because web uses this and we want to keep it secure
            }
            else{
                set_message("We only have " . $row['product_quantity'] ." ". $row['product_title'] . " available");
                redirect("../public/checkout.php");
            }
        }

    }

    if(isset($_GET['remove']))
    {
        $_SESSION['product_' . $_GET['remove']]--;

        if($_SESSION['product_' . $_GET['remove']] < 1)
        {
            unset($_SESSION['item_total']);
            unset($_SESSION['item_quantity']);
            redirect("../public/checkout.php");
        }
        else{
            redirect("../public/checkout.php");
        }
    }


    if(isset($_GET['delete']))
    {
        $_SESSION['product_' . $_GET['delete']] = '0';
        unset($_SESSION['item_total']);
        unset($_SESSION['item_quantity']);
        redirect("../public/checkout.php");

    }



    function cart(){
        $item_quantity  = 0;
        $total = 0;
        $item_name = 1;
        $item_number = 1;
        $amount = 1;
        $quantity = 1;
        foreach($_SESSION as $name => $value)
        {
            if($value > 0){
                if(substr($name, 0, 8)== "product_")
                {
                    $length = strlen((int)$name - 8); // non numeric value warning

                    $id = substr($name, 8, $length); // this will give us the product_number

                    $query = query("SELECT * from products WHERE product_id = " . escape_string($id) . " ");
                    confirm($query);
                    //$value = $value - 3;
                    echo $value;
    
                    while($row = fetch_array($query))
                    {
                        $sub = $row['product_price']* $value; // calculating sub total
                        $item_quantity += $value;
                        $product_image = display_image($row['product_image']);
                        $product = <<<DELIMETER
                        <tr>
                        <td>{$row['product_title']}<br>
                        <img width='100' src='../resources/{$product_image}'>
                        </td>
                        <td>Rs.{$row['product_price']}</td>
                        <td>{$value}</td>
                        <td>{$sub}Rs</td>
                        <td><a class='btn btn-warning' href="../resources/cart.php?remove={$row['product_id']}"> <span class='glyphicon glyphicon-minus'></span> </a>  <a class='btn btn-success' href="../resources/cart.php?add={$row['product_id']}"> <span class='glyphicon glyphicon-plus'></span> </a>
                        
                        <a class='btn btn-danger' href="../resources/cart.php?delete={$row['product_id']}"> <span class='glyphicon glyphicon-remove'></span> </a></td>
                        </tr>


<input type="hidden" name="item_name_{$item_name}" value="{$row['product_title']}">
<input type="hidden" name="item_number_{$item_number}" value="{$row['product_id']}">
<input type="hidden" name="amount_{$amount}" value="{$row['product_price']}">
<input type="hidden" name="amount_{$quantity}" value="{$row['product_quantity']}">


DELIMETER;
  $item_name++;
  $item_number++;
  $amount++;
  $quantity++;
    
                echo $product;

                
                    }//while end
                    $_SESSION['item_total'] = $total += $sub;
                    $_SESSION['item_quantity'] = $item_quantity;
                }//if end
            }
           
        }//for each end
        
    } // function end


    function reports(){
        $item_quantity  = 0;
        $total = 0;
        $item_name = 1;
        $item_number = 1;
        $amount = 1;
        $quantity = 1;
        $product_title = "";
      //  $product;
        $count = 0;
        foreach($_SESSION as $name => $value)
        {
            if($value > 0){
                if(substr($name, 0, 8)== "product_")
                {
                    $length = strlen((int)$name - 8); // non numeric value warning

                    $id = substr($name, 8, $length); // this will give us the product_number

                    $query = query("SELECT * from products WHERE product_id = " . escape_string($id) . " ");
                    confirm($query);
                    //$value = $value - 3;
                    echo $value;
                    
                    while($row = fetch_array($query))
                    {
                        $count++;
                        $sub = $row['product_price']* $value; // calculating sub total
                        $item_quantity += $value;
                        $product_price = $row['product_price'];
                        $product_quantity = $row['product_quantity'];
                        $product_title = $row['product_title'];
                        $insert_report = query("INSERT INTO reports (product_id,order_id,product_title, product_price, product_quantity) VALUES('{$id}','{NULL}','{$product_title}','{$product_price}','{$product_quantity}')");

                        confirm($insert_report);
    
               // echo $product;

                
                    }//while end
                     $total += $sub;
                     $item_quantity;
                }//if end
            }
           //session_destroy();
        }//for each end
       // session_destroy();
        
    } // function end

?>