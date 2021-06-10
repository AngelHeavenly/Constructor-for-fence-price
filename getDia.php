<?php
require_once ("dbConnect.php");
$item = filter_input(INPUT_GET, 'item', FILTER_SANITIZE_STRING);
$size = filter_input(INPUT_GET, 'size', FILTER_SANITIZE_STRING);
$query =   "
    SELECT items.id, name, diameters.diameters_value
    FROM items
    INNER JOIN items_values ON items_values.item_id = items.id
    INNER JOIN dimension_values ON dimension_values.id = items_values.dimension_value_id
    INNER JOIN sizes ON dimension_values.size_id = sizes.id 
    INNER JOIN diameters ON dimension_values.diameter_id = diameters.id
    WHERE items.name = '".$item."' AND sizes.sizes_value = '".$size."'
    GROUP BY diameters.id";

if ($res = mysqli_query($mysqli, $query))
{
    while ($array = mysqli_fetch_assoc($res))
    {

        $dia = $array['diameters_value'];
        $sendJSON[] = array('diameters_value' => $dia );
    }
}
else
{
    echo "Get error!  (". mysqli_errno(). ")" .mysqli_error();
}
mysqli_close($mysqli);
header('Content-Type: application/json');
$json = json_encode($sendJSON, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
echo $json;
?>
