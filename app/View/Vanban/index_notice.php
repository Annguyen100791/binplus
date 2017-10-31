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
            
            <ul class="content-box-tabs">
                <li><a href="#notice-gap" class="default-tab" rel="notice-gap" id="notice-gap-tab" title="Danh sách các văn bản có ngày hoàn thành bị trễ tiến độ"><?php echo $this->Html->image('icons/page_white_star.png', array('align' => 'left')) ?>&nbsp; Gấp, trễ tiến độ</a></li>
                <li><a href="#notice-khac" class="default-tab" rel="notice-khac" id="notice-khac-tab" title="Danh sách các văn bản khác bị trễ tiến độ"><?php echo $this->Html->image('icons/page_white_star.png', array('align' => 'left')) ?>&nbsp; Khác, trễ tiến độ</a></li>
            </ul>
            
            <div class="clear"></div>
            
        </div> <!-- End .content-box-header -->
        
        <div class="content-box-content">
        	<!-- Văn bản đến có ngày hoàn thành bị trễ tiến độ -->
            <div class="tab-content default-tab" id="notice-gap" style="display:none">
            	<div align="right" style="padding-bottom:10px">
                <form id="form-notice-gap-search">
                    <?php
                        echo '<b>Từ khóa</b> : ';
                        echo $this->Form->input('Vanban.keyword', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input',
                                                        'title'	=>	'Tìm kiếm văn bản gấp trễ tiến độ theo từ khóa',
                                                        'id'	=>	'notice-gap-keyword'));
                        echo '&nbsp;&nbsp;<b>Văn bản phát hành</b> :';
                        echo '&nbsp; từ ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_batdau', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input tu_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'notice-gap-ngay-batdau'));
                        echo '&nbsp; đến ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_ketthuc', 
                                                array(
                                                        'label' => false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input den_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'notice-gap-ngay-ketthuc'));
                                                        
                    ?>
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-notice-gap-search" title="Tìm kiếm nhanh văn bản gấp, trễ tiến độ " />
                </form>
                </div>
                <div class="content-box-content" id="notice-gap-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Văn bản đến khác bị trễ tiến độ -->
            <div class="tab-content default-tab" id="notice-khac" style="display:none">
            	<div align="right" style="padding-bottom:10px">
                <form id="form-notice-khac-search">
                    <?php
                        echo '<b>Từ khóa</b> : ';
                        echo $this->Form->input('Vanban.keyword', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input',
                                                        'title'	=>	'Tìm kiếm văn bản gấp trễ tiến độ theo từ khóa',
                                                        'id'	=>	'notice-khac-keyword'));
                        echo '&nbsp;&nbsp;<b>Văn bản phát hành</b> :';
                        echo '&nbsp; từ ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_batdau', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input tu_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'notice-khac-ngay-batdau'));
                        echo '&nbsp; đến ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_ketthuc', 
                                                array(
                                                        'label' => false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input den_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'notice-khac-ngay-ketthuc'));
                                                        
                    ?>
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-notice-khac-search" title="Tìm kiếm nhanh văn bản khác, trễ tiến độ " />
                </form>
                </div>
                <div class="content-box-content" id="notice-khac-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
        </div>
    </div><!-- End Content Box -->
</div>    