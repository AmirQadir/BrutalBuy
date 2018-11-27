<?php require_once("../resources/config.php "); ?>


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
                redirect("checkout.php");
            }
            else{
                set_message("We only have " . $row['product_quantity'] ." ". $row['product_title'] . " available");
                redirect("checkout.php");
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
            redirect("checkout.php");
        }
        else{
            redirect("checkout.php");
        }
    }


    if(isset($_GET['delete']))
    {
        $_SESSION['product_' . $_GET['delete']] = '0';
        unset($_SESSION['item_total']);
        unset($_SESSION['item_quantity']);
        redirect("checkout.php");

    }



    function cart(){
        $item_quantity  = 0;
        $total = 0;
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

                        $product = <<<DELIMETER
                        <tr>
                        <td>{$row['product_title']}</td>
                        <td>Rs.{$row['product_price']}</td>
                        <td>{$value}</td>
                        <td>{$sub}Rs</td>
                        <td><a class='btn btn-warning' href="cart.php?remove={$row['product_id']}"> <span class='glyphicon glyphicon-minus'></span> </a>  <a class='btn btn-success' href="cart.php?add={$row['product_id']}"> <span class='glyphicon glyphicon-plus'></span> </a>
                        
                        <a class='btn btn-danger' href="cart.php?delete={$row['product_id']}"> <span class='glyphicon glyphicon-remove'></span> </a></td>
                        </tr>
DELIMETER;
    
    
                echo $product;

                
                    }//while end
                    $_SESSION['item_total'] = $total += $sub;
                    $_SESSION['item_quantity'] = $item_quantity;
                }//if end
            }
           
        }//for each end
        
    } // function end

?>