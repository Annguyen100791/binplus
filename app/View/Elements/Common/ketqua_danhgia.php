<?php
    echo $this->Session->flash();
	if(!empty($ds)):
    
    $this->Paginator->options( 
    array(//'update'	=>	'#listing-container',  
          'url'		=> 	$this->passedArgs
        )); 
?>
<div class="data">
    <table>
        <thead>
        <tr>
            <th width="1px"><input class="check-all" type="checkbox" /></th>
            <th width="1px">STT</th>
            <th width="60%"><?php echo $this->Paginator->sort('dondiduocddanhgia_id', 'Đơn vị được đánh giá', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'danhgia-list'));?></th>
            <th width="100px"><?php echo $this->Paginator->sort('ket_qua', 'Kết quả', array('data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'danhgia-list'));?></th>
            <th width="120px"><?php echo $this->Paginator->sort('ngay_danhgia', 'Ngày đánh giá', array('data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'danhgia-list'));?></th>
        </tr>
        </thead>
    <tbody>
<?php
		$stt = $this->Paginator->params['paging']['Obdanhgia']['limit']*($this->Paginator->params['paging']['Obdanhgia']['page']-1) + 1;
		foreach($ds as $item):
	?>
    	<tr>
            <td align="center"><input type="checkbox" value="<?php echo $item['Obdanhgia']['id'] ?>" /></td>
    		<td align="center"><?php echo $stt++ ?></td>
            <td>
            <?php
				echo "Trung tâm Điều hành Thông tin";
			?>
            </td>
            <td align="center">
            <a href="#" class="tooltip">
			<?php if($item['Obdanhgia']['ket_qua'] == 1){
					
					echo "Hài lòng";
			      }
				  else
				  {
				  	//echo "Không hài lòng";
					//foreach($item['Obchitiet'] as $i):
						echo "Không hài lòng";
						//echo($i['Oblydo']['ten_lydo']); 
						//pr($item['Obchitiet']);die();
						?>
                        <span>
							<?php
                            foreach($item['Obchitiet'] as $lydo) {
                                echo '<p>'.$lydo['Oblydo']['ten_lydo'].'</p>';
                            }
                            ?>
                        </span>
                        <?php
						//echo $this->Html->link('Không hài lòng', '/dg360/view_reasons/', array('tip-position' => 'bottom left', 'title' => '<b>Lý do không hài lòng:</b>' . ($i['Oblydo']['ten_lydo']). '</p><i>(Click để xem chi tiết )</i>'));
					//endforeach;
					
				  }
					
			?></a></td>
            <td align="center"><?php echo $this->Time->format("d-m-Y", $item['Obdanhgia']['ngay_danhgia']) ?></td>

        </tr>
    <?php
		endforeach;
	?>
    </tbody>
    </table>
    <?php if($this->Paginator->params['paging']['Obdanhgia']['pageCount'] > 1):?>
    <div class="pagination">
        [Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
            <?php echo $this->Paginator->first('|<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'danhgia-list'))?>
            <?php echo $this->Paginator->prev('<', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'danhgia-list'))?>
            <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'danhgia-list')); ?>
            <?php echo $this->Paginator->next('>', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'danhgia-list'))?>
            <?php echo $this->Paginator->last('>|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'danhgia-list'))?>
    </div>
    <?php endif;?>
</div>    
<?php
    endif;
?>
</div>
<script>
	$(document).ready(function(){
		BIN.doListing();
	});
	$(function() {
    if ($.browser.msie && $.browser.version.substr(0,1)<7)
    {            
      $('.tooltip').mouseover(function(){              
            $(this).children('span').show();                
          }).mouseout(function(){
            $(this).children('span').hide();
          })
    }
  });  
</script>