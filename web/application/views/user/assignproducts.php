<?php  echo form_open(site_url() . '/user/doAssignProducts/');?>
<section id="main" class="column">
    <article class="module width_full">
        <header>
            <h3>Assign Product to Users</h3>
        </header>
        <div class="module_content">
            <input type="hidden" id="userid" name="userid" value="<?php echo $userid;?>">
            <table class="tablesorter" cellspacing="0">
                <tbody>
                    <?php if ($products):
                    ?>
                    <?php if($products&&count($products)>0) {
                    for($i=0;$i<count($products);$i++) {
                    $row =  $products[$i];
                    ?>
                    <tr>
                    <td>
                    <input type="checkbox" name="product[]" value="<?php echo $row['id'];?>"
                    <?php echo $row["permission"] ? "checked" : "";?> ><?php echo $row['name'];?></input>
                    </td>
                    </tr>
                    <?php }}
                    ?>
                    <?php endif;
                    ?>
                    <tr>
                        <td><?php echo form_submit('user/doAssignProducts', "Assign Products");
                        ?></td>
                    </tr>
                </tbody>
            </table>
            <br/>
        </div>
    </article>
</section>
<?php echo form_close();
?>