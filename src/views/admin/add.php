<?php
echo "<form method='post'>";
foreach($fields as $field){
    echo "<label for='$field->name'>$field->name</label>";
    $field->render();
    echo "<br>";
}
echo "<button type='submit'>Add new record</button>";
echo "</form>";