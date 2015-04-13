<?php echo $header; ?>
<div class="container">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
    <?php  echo $content_top; /*这里是中间的橱窗和格子内容*/ ?>
    <?php echo $content_bottom; 
            if(is_null($content_bottom) )
        {
            echo '-11111111111111------------$content_bottom is null--------' ;
        }/* 访问首页，经过测试不为空啊*/  ?>
    </div>
    <?php echo $column_right; ?>
  </div>
</div>
<?php echo $footer;  ?>


<?php 
/*
按以下顺序输出 
$content_top， 
$content_bottom; ，
$column_right;   

$footer;  
*/
?>