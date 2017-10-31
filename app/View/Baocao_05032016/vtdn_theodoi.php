 <?php
	echo $this->Html->script(
		array(
			  'libs/jquery/jquery.hashchange'
			  )
	);
?>
<div style="padding:20px">
    <div class="content-box" id="vanban-box"><!-- Start Content Box -->
        <div class="content-box-header">
            <h3><?php echo $this->Html->image('icons/page_white_stack.png', array('align' => 'left')) ?>&nbsp; Văn bản</h3>
            
            
            <div class="clear"></div>
            
        </div> <!-- End .content-box-header -->
        
        <div class="content-box-content">
        
           <!--  start table-content  -->
            <!--  start message -->
            <?php
                echo $this->Session->flash();
            ?>
            <!--  end message -->

			<?php
                if(!empty($ds)):
                
                $this->Paginator->options( 
                        array('url'		=> 	$this->passedArgs,  
                            )); 
            ?>
            <div style="padding-top:20px" class="data table-content">
                <table>
                    <thead>
                    <tr>
                        <th width="1px"><input class="check-all" type="checkbox" title="Click để chọn/ bỏ chọn tất cả văn bản" /></th>
                        <th width="80px"><?php echo $this->Paginator->sort('Vanban.so_hieu', 'Văn bản số', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'theodoi-list-content'));?></th>
                        <th width="90px"><?php echo $this->Paginator->sort('Vanban.ngay_phathanh', 'Ngày phát hành', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'theodoi-list-content'));?></th>
                        <th width="30%"><?php echo $this->Paginator->sort('Vanban.trich_yeu', 'Trích yếu', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'theodoi-list-content'));?></th>
                        <th width="90px"><?php echo $this->Paginator->sort('Theodoivanban.ngay_theodoi', 'Ngày theo dõi', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'theodoi-list-content'));?></th>
                        <th width="30%"><?php echo $this->Paginator->sort('Vanban.loaivanban_id', 'Loại văn bản', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'theodoi-list-content'));?></th>
                        <th width="30%"><?php echo $this->Paginator->sort('Theodoivanban.ghi_chu', 'Ghi chú', array('data-mode' => 'ajax', 'data-action' => 'paging', 'data-target' => 'theodoi-list-content'));?></th>
                    </tr>
                    </thead>
                <tbody>
                <?php
                    $stt = $this->Paginator->params['paging']['Theodoivanban']['limit']*($this->Paginator->params['paging']['Theodoivanban']['page']-1) + 1;
                    foreach($ds as $item):
					//pr($item);die();
                ?>
                    <tr>
                        <td align="center"><input type="checkbox" value="<?php echo $item['Theodoivanban']['id'] ?>" title="Click để chọn/ bỏ chọn văn bản này" /></td>
                        <td>
                        <?php
                            echo $this->Html->link($item['Vanban']['so_hieu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Xem chi tiết văn bản'));
                        ?>
                        </td>
                        <td align="center">
                        <?php
                            echo $this->Time->format("d-m-Y", $item['Vanban']['ngay_phathanh'])
                        ?>
                        </td>
                        
                        <td>
                        <?php
                            echo $this->Html->link($item['Vanban']['trich_yeu'], '/vanban/view/' . $item['Vanban']['id'] . '#xem_chitiet', array('title' => 'Xem chi tiết văn bản'));
                        ?>
                        </td>
                        <td align="center">
                        <?php
                            echo $this->Time->format("d-m-Y H:i:s", $item['Theodoivanban']['ngay_theodoi'])
                        ?>
                        </td>
                        <td>
                        <?php
                            echo $item['Vanban']['Loaivanban']['ten_loaivanban'];
                        ?>
                        </td>
                        <td><?php echo $item['Theodoivanban']['ghi_chu'] ?></td>
                    </tr>
                <?php
                    endforeach;
                ?>
                </tbody>
                </table>
                <!--<div style="float:left; padding: 20px 0">
                    <a href="/baocao/untrack/type:unread" class='button' data-mode="ajax" data-action="delete-all"  data-msg="Vui lòng chọn trong danh sách" data-confirm="Bạn có muốn hủy theo dõi văn bản đã chọn hay không ?" data-target="theodoi-list-content">Hủy bỏ theo dõi văn bản</a>
                </div> -->
                <?php if($this->Paginator->params['paging']['Theodoivanban']['pageCount'] > 1):?>
                <div class="pagination">
                    [Trang <?php echo $this->Paginator->counter(); ?> ] &nbsp;
                    <?php echo $this->Paginator->first('|< Đầu', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'theodoi-list-content'))?>
                    <?php echo $this->Paginator->prev('< Trước', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'theodoi-list-content'))?>
                    <?php echo $this->Paginator->numbers(array('separator'=>'&nbsp;', 'class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'theodoi-list-content')); ?>
                    <?php echo $this->Paginator->next('Sau >', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'theodoi-list-content'))?>
                    <?php echo $this->Paginator->last('Cuối >|', array('class' => 'number', 'data-mode' => 'ajax','data-action' => 'paging', 'data-target' => 'theodoi-list-content'))?>
                </div>
                <?php endif;?>
                <div style="clear:both"></div>
            </div>    
			<script>
                $(document).ready(function(){
                    BIN.doListing();
                });
            </script>
			<?php
                endif;
            ?>
			<!--  end content-table  -->
        </div>
    </div><!-- End Content Box -->
</div>    