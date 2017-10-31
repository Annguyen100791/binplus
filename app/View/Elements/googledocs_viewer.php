<style>
#file-list .number.current {
    background: url("/img/shared/bg-button-green.gif") repeat-x scroll left top #469400 !important;
    border-color: #459300 !important;
    color: #FFFFFF !important;
}
#file-list .number {
    border: 1px solid #DDDDDD;
    margin: 0 5px 0 0;
    padding: 5px 6px;
}
#file-list .number{
    border-radius: 4px 4px 4px 4px;
}
</style>
<?php
	//pr($this->data['Filevanban']);die();
	$files = count($this->data['Filevanban']);
	if( $files > 0):
?>
	<div id="file-list">
	<div class="content-box-header" style="padding-left:5px">
					
        <ul class="content-box-tabs" style="float:left!important;">
        <?php
			for($i = 0; $i < $files; $i++)
				printf('<li><a href="#file-vanban-%s" rel="#file-vanban-%s" class="number">File %s</a></li>', $i, $i, $i+1);
		?>
        </ul>
        
        <div class="clear"></div>
        
    </div> <!-- End .content-box-header -->
    <div class="content-box-content">
    	<?php
			$nv = $this->Session->read('Auth.User.nhanvien_id');
			$i = 0;
			$id_vanban = $this->data['Vanban']['id_vanban'];
			//pr($this->data); die();
			foreach($this->data['Filevanban'] as $item):
			$id = $item['id'];
			$s = Configure::read('Security.salt');
			$token = sha1($id.$nv.$s);
			$file = 'http://' . env("HTTP_HOST") . "/vanban/getfile_ggd/".$id."/".$nv."/".$token."/".$id_vanban ;
		?>
    	<div class="tab-content" id="file-vanban-<?php echo $i ?>">

            <div align="right" style="padding:5px">
                <a target="_blank" href="/vanban/getfile_ggd/<?php echo $id."/".$nv."/".$token."/".$id_vanban?>" class="button"><?php echo $this->Html->image('icons/disk.png', array('align' => 'left')) ?>&nbsp; Download file</a>&nbsp;
				<?php //if($this->Layout->check_permission('VanBan.theodoi') && empty($this->data['Theodoivanban']['id']) &&($this->data['Vanban']['tinhtrang_duyet'] == 1) ): ?>
                <?php if($this->Layout->check_permission('VanBan.theodoi') && ($this->data['Theodoivanban']['nguoi_theodoi_id'] !=$this->Session->read('Auth.User.nhanvien_id')) &&($this->data['Vanban']['tinhtrang_duyet'] == 1) ): ?>
                <a href="/vanban/danhdau_theodoi/id:<?php echo $this->data['Vanban']['id'] ?>/target:/type:" class="button btn-theodoi"><?php echo $this->Html->image('icons/page_white_star.png', array('align' => 'left')) ?>&nbsp; Theo dõi văn bản này</a>
                <?php endif;?>
                
                <?php if($this->Layout->check_permission('CongViec.khoitao')&& empty($check) && ($this->data['Vanban']['tinhtrang_duyet'] == 1)): ?>
                <a href="/congviec/add/<?php echo $this->data['Vanban']['id'] ?>" class="button" ><?php echo $this->Html->image('icons/congviec_add.png', array('align' => 'left')) ?>&nbsp; Giao việc theo văn bản</a>
                <?php 
				elseif(!empty($check)):
				?>
                <a href="/congviec/view/<?php echo $check['Congviec']['id'] ?>" class="button" ><?php echo $this->Html->image('icons/congviec_add.png', array('align' => 'left')) ?>&nbsp; Hiển thị công việc đã giao</a>	
                <?php
				endif;?>
                <?php if($this->Layout->check_permission('VanBan.trinh') && $this->data['Vanban']['tinhtrang_duyet'] == 0): ?>
					<a href="/vanban/trinh_vanban/<?php echo $this->data['Vanban']['id'] ?>" class="button"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>&nbsp; Trình văn bản đến</a>							
				<?php endif;?>
                <?php if($this->Layout->check_permission('VanBan.duyet') && $this->data['Vanban']['tinhtrang_duyet'] == 2 && $this->data['Vanban']['nguoi_duyet_id'] == $this->Session->read('Auth.User.nhanvien_id')) : 
                ?>
                    <a href="/vanban/duyet_vanban/<?php echo $this->data['Vanban']['id'] ?>" class="button"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>&nbsp; Duyệt văn bản đến</a>
				<?php endif;?>
                <?php  
				//if(($this->data['Vanban']['chieu_di'] == 1) && ($this->data['Vanban']['tinhtrang_duyet'] == 1) && ($this->Session->read('Auth.User.id') != 681) && $this->data['Vanban']['vb_gap'] == 1 ) : 
				if(($this->data['Vanban']['chieu_di'] == 1) && ($this->data['Vanban']['tinhtrang_duyet'] == 1) && ($this->Session->read('Auth.User.id') != 681)  ) : 
                ?>
                    <!--<a href="/vanban/up_result/<?php echo $this->data['Vanban']['id'] ?>/<?php echo $this->data['Vanban']['id'] ?>" class="button"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>&nbsp; Cập nhật kết quả</a> -->
                    <a href="/vanban/up_result/id:<?php echo $this->data['Vanban']['id']."/". urlencode("type:index#vanban-all") ?>" class="button"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>&nbsp; Cập nhật kết quả</a>  
				<?php endif;?> 
            </div>
            <div style="clear:both"></div>
            <div style="width:100%; min-width:600px">
                <iframe style="border-style: none;" id="viewer_container-<?php echo $i ?>"></iframe>
            </div>
            <script>
			$(document).ready(function(){
				var file = '<?php  echo $file ; ?>';
				file = 'https://docs.google.com/viewer?url=' + file + '&embedded=true';
				var w = $(window).width() - 60;
				var h = Math.floor(w*11/8);
				$('#viewer_container-<?php echo $i ?>').attr('width', w);
				$('#viewer_container-<?php echo $i ?>').attr('height', h);
				$('#viewer_container-<?php echo $i ?>').attr('src', file);
			});
		</script>
            
        </div>
        <?php
			$i++;
			endforeach;
		?>
    </div>
    
    </div>
<?php

	endif;
?>
<!--<script>
	$(document).ready(function(){
		var w = $(window).width() - 60;
		var h = Math.floor(w*11/8);
		//$('.viewer_container').attr('width', w);
		//$('.viewer_container').attr('height', h);

		$('.viewer_container').height(h);
		$('.viewer_container').width(w);
		
		
		$('a.embed').gdocsViewer({width: w, height: h});
	});
</script> -->