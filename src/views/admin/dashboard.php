<?php
echo Navbar::get_admin();
?>
<?php foreach($tables as $table) { ?>
<div>
    <h1 class="text-xl"><?php echo $table->name ?></h1>
    <div class="p-2 bg-sky-500 rounded w-fit">
        <a  href="<?php echo "/admin/add/" . $table->name ?>">Add record to <?php echo $table->name ?></a>
    </div>
</div>
<table class="table-auto">
    <?php foreach($table->getFieldNames() as $field) { ?>
    <th>
        <?php echo $field ?>
    </th>
    <?php } ?>
    <?php foreach($table->fetchFields() as $row) {  ?>
        
    <tr class="<?php echo $row['is_deleted'] ? '!bg-red-500' : '' ?> even:bg-green-500 bg-green-400">
        <?php foreach($table->getFieldNames() as $field) { ?>
        <td class="text-center">
            <!-- name -->
            <?php if($field == 'password'){ echo "supersecret";continue; } ?>
            <?php echo $row[$field] ?? 'NULL' ?>
        </td>
        <?php } ?>
        <!-- delete -->
        <td>
            <a href="<?php echo "/admin/delete/" . $table->name . "/" . $row['id'] ?>">X</a>
        </td>
    </tr>
    <?php } ?>
</table>
<?php } ?>