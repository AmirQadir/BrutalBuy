
            <div class="container-fluid">

<div class="row">

<h1 class="page-header">
All Products

</h1>

<h3 class="bg-success"> 
<?php display_message(); ?>
</h3>
<table class="table table-hover">


<thead>

<tr>
<th>Id</th>
<th>ProductID</th>
<th>OrderID</th>
<th>Price</th>
<th>Product title</th>
<th>Product Quantity</th>
</tr>
</thead>
<tbody>

<?php get_reports(); ?>


</tbody>
</table>


