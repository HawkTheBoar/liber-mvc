<?php

echo Navbar::get_admin();
?>
<h1 class="text-2xl">Add record to <?php echo $table_name ?></h1>
<?php
echo "<form method='post'>";
foreach($fields as $field){
    echo "<label for='$field->name'>$field->name</label>";
    $field->render();
    echo "<br>";
}
echo "<button class='bg-sky-500 p-4 rounded ml-10' type='submit'>Add new record</button>";
echo "</form>";