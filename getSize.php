<?php
require_once ("dbConnect.php");
$item = filter_input(INPUT_GET, 'item', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $query =   "
    SELECT items.id, name, sizes.sizes_value, dimension_values.special_value
    FROM items 
    INNER JOIN items_values ON items_values.item_id = items.id
    INNER JOIN dimension_values ON dimension_values.id = items_values.dimension_value_id
    LEFT JOIN sizes ON dimension_values.size_id = sizes.id
	WHERE items.name = '".$item."'
	GROUP BY sizes.id";
	if ($res = mysqli_query($mysqli, $query))
    {
        while ($array = mysqli_fetch_assoc($res))
        {
            
            $size = $array['sizes_value'];
            $special = $array['special_value'];
            $sendJSON[] = array_filter(array('sizes' => $size, 'special_value' => $special ));
            /*серьёзно, и с этим возникли проблемы? Достаточно всего-лишь присоединить слева таблицу
             * и всё заработает? Хорошо хоть догадался почистить JSON на наличие NULL строк.
             */
        }
    }
    else
    {
        echo "Get error!  (". mysqli_errno(). ")" .mysqli_error();
    }
mysqli_close($mysqli);
header('Content-Type: application/json');
$json = json_encode($sendJSON, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
echo $json;
?>

