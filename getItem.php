<?php
require_once ("dbConnect.php");

$query =   "
    SELECT name, items.id,
	GROUP_CONCAT(DISTINCT dimensions.name_size
    ORDER BY dimensions.name_size
    DESC SEPARATOR ', ') as name_size,
	GROUP_CONCAT(DISTINCT dimensions.name_diameter
    ORDER BY dimensions.name_diameter
    DESC SEPARATOR ', ') as name_diameter,
	GROUP_CONCAT(DISTINCT dimensions.name_cell
    ORDER BY dimensions.name_cell
    DESC SEPARATOR ', ') as name_cell
    FROM items
	INNER JOIN items_values ON items_values.item_id = items.id
	INNER JOIN dimension_values ON items_values.dimension_value_id = dimension_values.id
	INNER JOIN dimensions ON dimension_values.dimension_id = dimensions.id
    GROUP BY items.id";
if ($res = mysqli_query($mysqli, $query))
{
    while ($array = mysqli_fetch_assoc($res))
    {
        $size = $array['name_size'];
        $cell = $array['name_cell'];
        $diameter = $array['name_diameter'];
        $name = $array['name'];
        $key = $array['name'];
        $sendJSON[] = array('key'=> $key, 'val' => $name,  'diameter'=> $diameter, 'cell' => $cell, 'size' => $size );
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



