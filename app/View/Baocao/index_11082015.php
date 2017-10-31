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
                <li><a href="#vanban-unread" class="default-tab" rel="vanban-unread" id="vanban-unread-tab" title="Danh sách các văn bản chưa xem"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>&nbsp; Chưa xem</a></li>
                <li><a href="#vanban-all" rel="vanban-all" id="vanban-all-tab" title="Tất cả văn bản"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>&nbsp; Tất cả</a></li>
                <li><a href="#vanban-den" rel="vanban-den" id="vanban-den-tab" title="Danh sách các văn bản đến"><?php echo $this->Html->image('icons/page_white_put.png', array('align' => 'left')) ?>&nbsp; Đến</a></li>
                <li><a href="#vanban-di" rel="vanban-di" id="vanban-di-tab" title="Danh sách các văn bản đi"><?php echo $this->Html->image('icons/page_white_get.png', array('align' => 'left')) ?>&nbsp;Đi</a></li>
                <li><a href="#vanban-noibo" rel="vanban-noibo" id="vanban-noibo-tab" title="Văn bản nội bộ"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>&nbsp; Văn bản nội bộ</a></li>
                <li><a href="#vanban-theodoi" rel="vanban-theodoi" id="vanban-theodoi-tab" title="Danh sách các văn bản đang được theo dõi"><?php echo $this->Html->image('icons/page_white_star.png', array('align' => 'left')) ?>&nbsp; Đang theo dõi</a></li>
            </ul>
            
            <div class="clear"></div>
            
        </div> <!-- End .content-box-header -->
        
        <div class="content-box-content">
        	<!-- Văn bản chưa đọc -->
            <div class="tab-content default-tab" id="vanban-unread" style="display:none">
            	<div align="right" style="padding-bottom:10px">
                <form id="form-unread-search">
                    <?php
                        echo '<b>Từ khóa</b> : ';
                        echo $this->Form->input('Vanban.keyword', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input',
                                                        'title'	=>	'Tìm kiếm văn bản chưa xem theo từ khóa',
                                                        'id'	=>	'unread-keyword'));
                        echo '&nbsp;&nbsp;<b>Văn bản phát hành</b> :';
                        echo '&nbsp; từ ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_batdau', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input tu_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'unread-ngay-batdau'));
                        echo '&nbsp; đến ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_ketthuc', 
                                                array(
                                                        'label' => false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input den_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'unread-ngay-ketthuc'));
                                                        
                    ?>
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-unread-search" title="Tìm kiếm nhanh văn bản chưa xem " />
                </form>
                </div>
                <div class="content-box-content" id="unread-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Tất cả văn bản -->
            <div class="tab-content" id="vanban-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-all-search">
                    <?php
                        echo '<b>Từ khóa</b> : ';
                        echo $this->Form->input('Vanban.keyword', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input',
                                                        'title'	=>	'Tìm kiếm theo từ khóa',
                                                        'id'	=>	'all-keyword'));
                        echo '&nbsp;&nbsp;<b>Văn bản phát hành</b> :';
                        echo '&nbsp; từ ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_batdau', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input tu_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'all-ngay-batdau'));
                        echo '&nbsp; đến ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_ketthuc', 
                                                array(
                                                        'label' => false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input den_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'all-ngay-ketthuc'));
                                                        
                    ?>
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Văn bản đến -->
            <div class="tab-content" id="vanban-den" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-den-search" method="post">
                    <?php
                        echo '<b>Từ khóa</b> : ';
                        echo $this->Form->input('Vanban.keyword', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input',
                                                        'title'	=>	'Tìm kiếm theo từ khóa',
                                                        'id'	=>	'den-keyword'));
                        echo '&nbsp;&nbsp;<b>Văn bản phát hành</b> :';
                        echo '&nbsp; từ ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_batdau', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input tu_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'den-ngay-batdau'));
                        echo '&nbsp; đến ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_ketthuc', 
                                                array(
                                                        'label' => false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input den_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'den-ngay-ketthuc'));
                                                        
                    ?>
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-den-search" title="Tìm kiếm nhanh văn bản đến" />
                </form>
                </div>
                <div class="content-box-content" id="den-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Văn bản đi -->
            <div class="tab-content" id="vanban-di" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-di-search" method="post">
                    <?php
                        echo '<b>Từ khóa</b> : ';
                        echo $this->Form->input('Vanban.keyword', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input',
                                                        'title'	=>	'Tìm kiếm theo từ khóa',
                                                        'id'	=>	'di-keyword'));
                        echo '&nbsp;&nbsp;<b>Văn bản phát hành</b> :';
                        echo '&nbsp; từ ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_batdau', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input tu_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'di-ngay-batdau'));
                        echo '&nbsp; đến ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_ketthuc', 
                                                array(
                                                        'label' => false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input den_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'di-ngay-ketthuc'));
                                                        
                    ?>
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-di-search" title="Tìm kiếm nhanh văn bản đi" />
                </form>
                </div>
                <div id="di-list-content" style="padding:10px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Văn bản nội bộ -->
            <div class="tab-content" id="vanban-noibo" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-noibo-search" method="post">
                    <?php
                        echo '<b>Từ khóa</b> : ';
                        echo $this->Form->input('Vanban.keyword', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input',
                                                        'title'	=>	'Tìm kiếm theo từ khóa',
                                                        'id'	=>	'den-keyword'));
                        echo '&nbsp;&nbsp;<b>Văn bản phát hành</b> :';
                        echo '&nbsp; từ ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_batdau', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input tu_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'den-ngay-batdau'));
                        echo '&nbsp; đến ngày &nbsp;';
                        echo $this->Form->input('Vanban.ngay_ketthuc', 
                                                array(
                                                        'label' => false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input den_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn ngày',
                                                        'id'	=>	'den-ngay-ketthuc'));
                                                        
                    ?>
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-noibo-search" title="Tìm kiếm nhanh văn bản nội bộ" />
                </form>
                </div>
                <div class="content-box-content" id="noibo-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Văn bản được theo dõi -->
            <div class="tab-content" id="vanban-theodoi" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-theodoi-search">
                    <?php
                        echo '<b>Từ khóa</b> : ';
                        echo $this->Form->input('Vanban.keyword', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input',
                                                        'title'	=>	'Tìm kiếm văn bản được theo dõi theo :<BR>- <b>số hiệu văn bản</b><BR>- <b>nơi phát hành</b> <BR>- <b>trích yếu</b>',
                                                        'id'	=>	'theodoi-keyword'));
						echo '&nbsp;&nbsp;<b>Ghi chú</b> : ';
                        echo $this->Form->input('Vanban.ghi_chu', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
                                                        'class'	=>	'text-input',
                                                        'title'	=>	'Tìm kiếm văn bản theo ghi chú',
                                                        'id'	=>	'theodoi-ghichu'));
                        echo '&nbsp;&nbsp;<b>phát hành</b>&nbsp;';
                        echo $this->Form->input('Vanban.ngay_batdau', 
                                                array(
                                                        'label' => 	false,
                                                        'div'	=>	false,
														'placeholder'	=>	'Từ ngày',
                                                        'class'	=>	'text-input tu_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn tra cứu từ ngày',
                                                        'id'	=>	'theodoi-ngay-batdau'));
                        echo '&nbsp;';
                        echo $this->Form->input('Vanban.ngay_ketthuc', 
                                                array(
                                                        'label' => false,
                                                        'div'	=>	false,
														'placeholder'	=>	'đến ngày',
                                                        'class'	=>	'text-input den_ngay',
                                                        'style'	=>	'width:80px',
                                                        'title'	=>	'Click để chọn tra cứu đến ngày',
                                                        'id'	=>	'theodoi-ngay-ketthuc'));
                                                        
                    ?>
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-theodoi-search" title="Tìm kiếm nhanh văn bản được theo dõi" />
                </form>
                </div>
                <div class="content-box-content" id="theodoi-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
        </div>
    </div><!-- End Content Box -->
</div>    