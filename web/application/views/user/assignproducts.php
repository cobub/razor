<?php  echo form_open(site_url() . '/user/doAssignProducts/');?>
<section id="main" class="column" style='height:1500px;'>
    <article class="module width_full">
        <header>
            <h3><?php echo lang('v_title_assign_products');?></h3>
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
                     <?php if($products&&count($products)>0) {?>
                    <tr>
                        <td>
                        <?php if(isset($guest_roleid) && $guest_roleid==2) 
                        { echo form_submit('user/doAssignProducts', lang('v_assign_products'),'disabled');}
                        else {
                              echo form_submit('user/doAssignProducts', lang('v_assign_products'));
                        }
                        ?>
                        </td>
                    </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
            <br/>
        </div>
    </article>
</section>
<?php echo form_close();
?>