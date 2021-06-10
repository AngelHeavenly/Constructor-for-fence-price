<?php
require_once ("dbConnect.php");
$get = file_get_contents('php://input');
$json = json_decode($get, true);
foreach ($json as $new) {
$query =   "SELECT items.id, name, items_values.price
    FROM items
    INNER JOIN items_values ON items_values.item_id = items.id
    INNER JOIN dimension_values ON dimension_values.id = items_values.dimension_value_id
    INNER JOIN sizes ON dimension_values.size_id = sizes.id 
    INNER JOIN diameters ON dimension_values.diameter_id = diameters.id
    INNER JOIN cells ON dimension_values.cell_id = cells.id
    WHERE items.name = '".$new[item]."' AND sizes.sizes_value = '".$new[size]."' AND diameters.diameters_value = '".$new[dia]."' AND cells.cell_value = '".$new[cell]."'
    GROUP BY items.id";
if ($res = mysqli_query($mysqli, $query))
{
    while ($array = mysqli_fetch_assoc($res))
    {
        $price = $array['price'];
    }
}
else
{
    echo "Get error!  (". mysqli_errno(). ")" .mysqli_error();
}
    $sum += $price * $new[amount];
    $sendJSON = array('sum' =>'Итого: ' .$sum.' ₽');
}
mysqli_close($mysqli);
$json = json_encode($sendJSON, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
echo $json;
?>
