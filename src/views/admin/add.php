<?php
foreach($fields as $field){
    echo "<label for='$field->name'>$field->name</label>";
    $field->render();
    echo "<br>";
}