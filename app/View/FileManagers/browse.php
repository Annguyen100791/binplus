<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
	form{
		margin:1px;
	}
	.message{
		border: 1px solid #D96C00;
		background-color:#FFFFDF;
		padding:3px;
	}
	.alt-row{
		background-color:#F4F4F4
	}
</style>
<?php
	echo $this->Html->script(
		array(
			  'libs/jquery/jquery.min'
			  )
	);
?>
<script>
	function selectURL(url) { 
            if (url == '') return false; 

            var field = window.top.opener.browserField;
            field.val(url);
            window.top.close(); 
            window.top.opener.browserWin.focus(); 
        }
</script>
<div style="padding-bottom:5px">
<div style="background-color:#F4F4F4">
<?php
    echo $this->Form->create( 
        null, 
        array( 
            'type' => 'file', 
            'url' => array( 
                'action' => 'upload' 
            ) 
        ) 
    ); 
    echo $this->Form->label( 
        'FileManager.file', 
        'Chọn file upload : ' 
    ); 
    echo $this->Form->file('FileManager.file', array('div' => false, 'label' => false));     
	
	echo $this->Form->submit('Upload', array('div' => false, 'label' => false));
	echo $this->Form->hidden('FileManager.path', array('value' => $path));
    echo $this->Form->end(null); 
?>
</div>
<div style="background-color:#F4F4F4">
<?php	
	echo $this->Form->create( 
        null, 
        array( 
            'type' => 'file', 
            'url' => array( 
                'action' => 'create_dir' 
            )
        ) 
    ); 
    echo $this->Form->label( 
        'FileManager.dir_name', 
        'Tạo mới thư mục ' 
    ); 
    echo $this->Form->input('dir_name', array('div' => false, 'label' => false));     
	
	echo $this->Form->submit('Create', array('div' => false, 'label' => false));
	echo $this->Form->hidden('path', array('value' => $path));
    echo $this->Form->end(null); 
?> 
</div>
<div style="background-color: #CCC; border:1px solid #999; padding:3px">
<?php
	echo 'Đường dẫn hiện tại : ';
	echo DS;
	$breadcrumb = $this->FileManager->breadcrumb($path);
	
	foreach ($breadcrumb AS $pathname => $p) 
	{
		echo $this->FileManager->linkDirectory($pathname, $p);
		echo DS;
	}
?>
</div>
</div>
<?php echo $this->Session->flash(); ?>
<table width="100%" border="0" cellspacing="1" cellpadding="0" id="files-list">
<tr style="height:30px; background-color:#666; color:#FFF">
	<th></th>
    <th align="left">Name</th>
    <th>Modified Date</th>
    <th>Size</th>
    <th>Action</th>
</tr>
<?php
	foreach($files['0'] as $dir):
?>
	<tr class="table_row">
    	<td><img src="/img/icons/folder.png" /></td>
        <td><?php echo $this->Html->link($dir, '/file_managers/browse?path=' . $path . $dir . DS); ?></td>
        <td align="center"></td>
        <td></td>
        <td align="center">
        <?php
			echo $this->Html->link('Open', '/file_managers/browse?path=' . $path . $dir . DS);
			echo "&nbsp;";
			echo $this->Html->link('Delete', '/file_managers/delete_dir?path=' . $path . '&dir=' . $dir, array('title' => 'Click để xóa thư mục này.'), 'Bạn có muốn xóa thư mục này không ?');
		?>
        </td>
    </tr>
<?php	
	endforeach;
	foreach($files['1'] as $file):
	$item = new File(APP . WEBROOT_DIR . $root . DS . $path . $file); 
//	pr($item->info());
?>
	<tr>
    	<td><?php echo $this->Html->image('/img/icons/'. $this->FileManager->filename2icon($file)) ?></td>
        <td><?php echo $this->Html->link($item->name, '#', array('onClick' => 'selectURL("'. str_replace(DS, '/', $root . DS . $path . $item->name) . '");' 
)); ?></td>
        <td align="center"><?php echo date('d/m/Y H:i:s', $item->lastChange()) ?></td>
        <td align="right"><?php echo $this->Number->toReadableSize($item->size()); ?></td>
        <td align="center">
        <?php 
			echo $this->Html->link('Select', '#', array('onClick' => 'selectURL("'. str_replace(DS, '/', $root . DS . $path . $item->name) . '");'
, 'title' => 'Click để chọn file này')); 
			echo "&nbsp;";
			echo $this->Html->link('Delete', '/file_managers/delete_file?path=' . $path . '&file=' . $item->name, array('title' => 'Click để xóa file này'), 'Bạn có muốn xóa file này không ?');
		?>

        </td>
    </tr>
<?php	
	endforeach;
?>
</table>
<script>
	$(document).ready(function(){
		$('#files-list tr:odd').addClass("alt-row");
	});
</script>