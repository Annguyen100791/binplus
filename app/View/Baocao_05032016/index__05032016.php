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
                <li><a href="#vtdn-all" rel="vtdn-all" id="vtdn-all-tab" title="Tất cả văn bản VTĐN gửi VNPT"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>VTĐN</a></li>
                <li><a href="#vpth-all" rel="vpth-all" id="vpth-all-tab" title="Tất cả văn bản VPTH gửi VTĐN"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>VPTH</a></li>
                <li><a href="#tcld-all" rel="tcld-all" id="tcld-all-tab" title="Tất cả văn bản TC-LĐ gửi VTĐN"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>TC-LĐ</a></li>
                <li><a href="#khkd-all" rel="khkd-all" id="khkd-all-tab" title="Tất cả văn bản KH-KD gửi VTĐN"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>KH-KD</a></li>
                <li><a href="#dt-all" rel="dt-all" id="dt-all-tab" title="Tất cả văn bản ĐT-XDCB gửi VNPT"><?php echo $this->Html->image('icons/page_white.png', array('align'  => 'left')) ?>ĐT-XDCB</a></li>
                <li><a href="#mdv-all" rel="mdv-all" id="mdv-all-tab" title="Tất cả văn bản MDV gửi VTĐN"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>MDV</a></li>
                <li><a href="#tckt-all" rel="tckt-all" id="tckt-all-tab" title="Tất cả văn bản TC-KT gửi VTĐN"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>TC-KT</a></li>
                <li><a href="#ttcntt-all" rel="ttcntt-all" id="ttcntt-all-tab" title="Tất cả văn bản TTCNTT gửi VTĐN"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>TTCNTT</a></li>
                <li><a href="#ttkd-all" rel="ttkd-all" id="ttkd-all-tab" title="Tất cả văn bản TTKD gửi VTĐN"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>TTKD</a></li>
                <li><a href="#ttdh-all" rel="ttdh-all" id="ttdh-all-tab" title="Tất cả văn bản TTĐH gửi VTĐN"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>TTĐH</a></li>
                <li><a href="#ttvt1-all" rel="ttvt1-all" id="ttvt1-all-tab" title="Tất cả văn bản TTVT1 gửi VNPT"><?php echo $this->Html->image('icons/page_white.png', array('align'  => 'left')) ?>TTVT1</a></li>
                <li><a href="#ttvt2-all" rel="ttvt2-all" id="ttvt2-all-tab" title="Tất cả văn bản TTVT2 gửi VTĐN"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>TTVT2</a></li>
                <li><a href="#ttvt3-all" rel="ttvt3-all" id="ttvt3-all-tab" title="Tất cả văn bản TTVT3 gửi VTĐN"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>TTVT3</a></li>
                <li><a href="#ttvt4-all" rel="ttvt4-all" id="ttvt4-all-tab" title="Tất cả văn bản TTVT4 gửi VTĐN"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>TTVT4</a></li>
                <li><a href="#ttvt5-all" rel="ttvt5-all" id="ttvt5-all-tab" title="Tất cả văn bản TTVT5 gửi VTĐN"><?php echo $this->Html->image('icons/page_white.png', array('align' => 'left')) ?>TTVT5</a></li>
                
            </ul>
            
            <div class="clear"></div>
            
        </div> <!-- End .content-box-header -->
        
        <div class="content-box-content">
        
            <!-- Tất cả văn bản VTĐN gửi VNPT-->
            <div class="tab-content" id="vtdn-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-vtdn-all-search">
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
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-vtdn-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-vtdn-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Tất cả văn bản VPTH gửi VTĐN-->
            <div class="tab-content" id="vpth-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-vpth-all-search">
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
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-vpth-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-vpth-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Tất cả văn bản TC-LĐ gửi VTĐN-->
            <div class="tab-content" id="tcld-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-tcld-all-search">
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
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-tcld-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-tcld-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Tất cả văn bản KH-KD gửi VTĐN-->
            <div class="tab-content" id="khkd-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-khkd-all-search">
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
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-khkd-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-khkd-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Tất cả văn bản ĐT-XDCB gửi VTĐN-->
            <div class="tab-content" id="dt-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-dt-all-search">
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
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-dt-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-dt-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Tất cả văn bản MDV gửi VTĐN-->
            <div class="tab-content" id="mdv-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-mdv-all-search">
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
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-mdv-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-mdv-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Tất cả văn bản TC-KT gửi VTĐN-->
            <div class="tab-content" id="tckt-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-tckt-all-search">
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
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-tckt-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-tckt-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Tất cả văn bản TTCNTT gửi VTĐN-->
            <div class="tab-content" id="ttcntt-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-ttcntt-all-search">
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
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-ttcntt-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-ttcntt-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Tất cả văn bản TTKD gửi VTĐN-->
            <div class="tab-content" id="ttkd-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-ttkd-all-search">
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
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-ttkd-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-ttkd-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Tất cả văn bản TTĐH gửi VTĐN-->
            <div class="tab-content" id="ttdh-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-ttdh-all-search">
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
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-ttdh-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-ttdh-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Tất cả văn bản TTVT1 gửi VTĐN-->
            <div class="tab-content" id="ttvt1-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-ttvt1-all-search">
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
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-ttvt1-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-ttvt1-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Tất cả văn bản TTVT2 gửi VTĐN-->
            <div class="tab-content" id="ttvt2-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-ttvt2-all-search">
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
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-ttvt2-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-ttvt2-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Tất cả văn bản TTVT3 gửi VTĐN-->
            <div class="tab-content" id="ttvt3-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-ttvt3-all-search">
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
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-ttvt3-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-ttvt3-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Tất cả văn bản TTVT4 gửi VTĐN-->
            <div class="tab-content" id="ttvt4-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-ttvt4-all-search">
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
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-ttvt4-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-ttvt4-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
            <!-- Tất cả văn bản TTVT5 gửi VTĐN-->
            <div class="tab-content" id="ttvt5-all" style="display:none">
                <div align="right" style="padding-bottom:10px">
                <form id="form-ttvt5-all-search">
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
                    <input class="button" type="submit" value="Tìm kiếm nhanh" id="btn-ttvt5-all-search" title="Tìm kiếm nhanh tất cả văn bản" />
                </form>
                </div>
                <div class="content-box-content" id="all-ttvt5-list-content" style="padding:5px 0px">
                    <img src="/img/circle_ball.gif" />
                </div>
            </div>
       
        </div>
    </div><!-- End Content Box -->
</div>    