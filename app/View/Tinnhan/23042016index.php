<?php
	echo $this->Html->script(
		array(
			  'libs/jquery/jquery.hashchange'
			  )
	);
?>
<style>
.users{
	width:250px
}
</style>
<div style="padding:20px">

<div class="content-box" id="tinnhan-box"><!-- Start Content Box -->
    
    <div class="content-box-header">
					
        <h3><img src="/img/icons/email.png" align="left" />&nbsp; Quản lý tin nhắn</h3>
        
        <ul class="content-box-tabs">
            <li><a href="#message-unread" class="default-tab" rel="message-unread" id="message-unread-tab" title="Danh sách tin nhắn chưa đọc"><img src="/img/icons/email.png" align="left" />&nbsp;Chưa đọc</a></li>
            <li><a href="#message-read" rel="message-read" id="message-read-tab" title="Danh sách tin nhắn đã đọc"><img src="/img/icons/email_open.png" align="left" />&nbsp;Đã đọc</a></li>
            <li><a href="#message-sent" rel="message-sent" id="message-sent-tab" title="Danh sách tin nhắn đã gửi"><img src="/img/icons/email_go.png" align="left" />&nbsp;Đã gửi</a></li>
            <li><a href="#message-all" rel="message-all" id="message-all-tab" title="Danh sách tất cả tin nhắn"><img src="/img/icons/email.png" align="left" />&nbsp;Tất cả</a></li>
        </ul>
        
        <div class="clear"></div>
        
    </div> <!-- End .content-box-header -->
    
    <div class="content-box-content">
    	<div class="tab-content default-tab" id="message-unread">
        	<div align="right" style="padding-bottom:10px">
                <form id="form-unread-search">
                    Từ khóa : 
                    <?php
                        echo $this->Form->input('Tinnhan.keyword', array(
                                                                      'label'	=>	false,
                                                                      'div'		=>	false,
                                                                      'class'	=>	'text-input',
                                                                      'title'	=>	'Tìm kiếm tin nhắn đã đọc theo <b>tiêu đề</b> hoặc <b>nội dung</b>'
                                                                      ));
                    ?>
                    Ngày gửi
                    <?php
                        echo $this->Form->input('Tinnhan.tu_ngay', array(
                                                                      'label'	=>	false,
                                                                      'div'		=>	false,
                                                                      'class'	=>	'text-input tu_ngay',
                                                                      'style'	=>	'width:80px',
                                                                      'title'	=>	'Tìm kiếm tin nhắn đã đọc theo khoảng thời gian gửi'
                                                                      ));
                    ?>
                    đến ngày 
                    <?php
                        echo $this->Form->input('Tinnhan.den_ngay', array(
                                                                      'label'	=>	false,
                                                                      'div'		=>	false,
                                                                      'class'	=>	'text-input den_ngay',
                                                                      'style'	=>	'width:80px',
                                                                      'title'	=>	'Tìm kiếm tin nhắn đã đọc theo khoảng thời gian gửi'
                                                                      ));
                       echo '&nbsp;';
                       echo $this->Form->input('Tinnhan.nguoigui_id', array(
                                                                      'label'	=>	false,
                                                                      'div'		=>	false,
                                                                      'class'	=>	'text-input users',
                                                                      'options'	=>	$nv,
                                                                      'empty'	=>	'-- tìm theo người gửi --'
                                                                      ));
                    ?>
                    <button class="button" type="submit">Tìm kiếm</button>
                </form>
             </div>
             <div class="content-box-content" id="unread-list-content">
                <img src="/img/circle_ball.gif" />
            </div>
            <div  style="padding:10px">
                <a class="button" href="/tinnhan/compose" id="btn-unread-add" title="Click để soạn thảo tin nhắn">Soạn thảo</a>
                <a class="button" href="/tinnhan/unread_delete"  data-mode="ajax" data-action="delete-all" data-msg="Vui lòng chọn trong danh sách" data-confirm="Bạn có muốn xóa các Tin nhắn đã chọn hay không ?" data-target="unread-list-content" title="Click để xóa các Tin nhắn đã chọn trong danh sách">Xóa</a>
                <a class="button" href="/tinnhan/unread" title="Click để tải lại danh sách tin nhắn" data-mode="ajax" data-action="update" data-target="unread-list-content">Reload</a>
                <a href="/tinnhan/mark_read" class='button' data-mode="ajax" data-action="delete-all"  data-msg="Vui lòng chọn trong danh sách" data-confirm="Bạn có muốn đánh dấu tin nhắn đã chọn hay không ?" data-target="unread-list-content">Đánh dấu tin nhắn đã đọc</a>
            </div>
        </div>
    	<div class="tab-content" id="message-read">
        	<div align="right" style="padding-bottom:10px">
            <form id="form-read-search">
                Từ khóa : 
                <?php
                    echo $this->Form->input('Tinnhan.keyword', array(
                                                                  'label'	=>	false,
                                                                  'div'		=>	false,
                                                                  'class'	=>	'text-input',
                                                                  'title'	=>	'Tìm kiếm tin nhắn đã đọc theo <b>tiêu đề</b> hoặc <b>nội dung</b>'
                                                                  ));
                ?>
                Ngày gửi
                <?php
                    echo $this->Form->input('Tinnhan.tu_ngay', array(
                                                                  'label'	=>	false,
                                                                  'div'		=>	false,
                                                                  'id'		=>	'read_tu_ngay',
                                                                  'class'	=>	'text-input tu_ngay',
                                                                  'style'	=>	'width:80px',
                                                                  'title'	=>	'Tìm kiếm tin nhắn đã đọc theo khoảng thời gian gửi'
                                                                  ));
                ?>
                đến ngày 
                <?php
                    echo $this->Form->input('Tinnhan.den_ngay', array(
                                                                  'label'	=>	false,
                                                                  'div'		=>	false,
                                                                  'id'		=>	'read_den_ngay',
                                                                  'class'	=>	'text-input den_ngay',
                                                                  'style'	=>	'width:80px',
                                                                  'title'	=>	'Tìm kiếm tin nhắn đã đọc theo khoảng thời gian gửi'
                                                                  ));
                   echo '&nbsp;';
                   echo $this->Form->input('Tinnhan.nguoigui_id', array(
                                                                  'label'	=>	false,
                                                                  'div'		=>	false,
                                                                  'class'	=>	'text-input users',
                                                                  'options'	=>	$nv,
                                                                  'empty'	=>	'-- tìm theo người gửi --'
                                                                  ));
                ?>
                <button class="button" type="submit">Tìm kiếm</button>
            </form>
            </div>
            <div class="content-box-content" id="read-list-content">
                <img src="/img/circle_ball.gif" />
            </div>
            <div  style="padding:10px">
                <a class="button" href="/tinnhan/compose" title="Click để soạn thảo tin nhắn">Soạn thảo</a>
                <a class="button" href="/tinnhan/read_delete"  data-mode="ajax" data-action="delete-all" data-msg="Vui lòng chọn trong danh sách" data-confirm="Bạn có muốn xóa các Tin nhắn đã chọn hay không ?" data-target="read-list-content" title="Click để xóa các Tin nhắn đã chọn trong danh sách">Xóa</a>
                <a class="button" href="/tinnhan/read" title="Click để tải lại danh sách tin nhắn" data-mode="ajax" data-action="update" data-target="read-list-content">Reload</a>
            </div>
    	</div>
    	<div class="tab-content" id="message-sent">
        	<div align="right" style="padding-bottom:10px">
            <form id="form-sent-search">
                Từ khóa : 
                <?php
                    echo $this->Form->input('Tinnhan.keyword', array(
                                                                  'label'	=>	false,
                                                                  'div'		=>	false,
                                                                  'class'	=>	'text-input'
                                                                  ));
                ?>
                &nbsp;
                Ngày gửi&nbsp;
                <?php
                    echo $this->Form->input('Tinnhan.tu_ngay', array(
                                                                  'label'	=>	false,
                                                                  'div'		=>	false,
                                                                  'id'		=>	'sent_tu_ngay',
                                                                  'class'	=>	'text-input tu_ngay',
                                                                  'style'	=>	'width:70px'
                                                                  ));
                ?>
                đến ngày&nbsp;
                <?php
                    echo $this->Form->input('Tinnhan.den_ngay', array(
                                                                  'label'	=>	false,
                                                                  'div'		=>	false,
                                                                  'id'		=>	'sent_den_ngay',
                                                                  'class'	=>	'text-input den_ngay',
                                                                  'style'	=>	'width:70px'
                                                                  ));
                   echo '&nbsp;';
                   echo $this->Form->input('Tinnhan.nguoinhan_id', array(
                                                                  'label'	=>	false,
                                                                  'div'		=>	false,
                                                                  'class'	=>	'text-input users',
                                                                  'options'	=>	$nv,
                                                                  'empty'	=>	'-- tìm theo người nhận --'
                                                                  ));
                ?>
                <button class="button" id="btn-unread-search">Tìm kiếm</button>
            </form>
            </div>
            <div class="content-box-content" id="sent-list-content">
                <img src="/img/circle_ball.gif" />
            </div>
            <div  style="padding:10px">
                <a class="button" href="/tinnhan/compose" title="Click để soạn thảo tin nhắn">Soạn thảo</a>
                <a class="button" href="/tinnhan/sent_delete" data-mode="ajax" data-action="delete-all" data-msg="Vui lòng chọn trong danh sách" data-confirm="Bạn có muốn xóa các Tin nhắn đã chọn hay không ?" data-target="sent-list-content" title="Click để xóa các Tin nhắn đã chọn trong danh sách">Xóa</a>
                <a class="button" href="/tinnhan/sent" title="Click để tải lại danh sách tin nhắn" data-mode="ajax" data-action="update" data-target="sent-list-content">Reload</a>
            </div>
    	</div>
        <!-- Tất cả tin nhắn -->
        <div class="tab-content" id="message-all">
        	<div align="right" style="padding-bottom:10px">
            <form id="form-all-search">
                Từ khóa : 
                <?php
                    echo $this->Form->input('Tinnhan.keyword', array(
                                                                  'label'	=>	false,
                                                                  'div'		=>	false,
                                                                  'class'	=>	'text-input'
                                                                  ));
                ?>
                &nbsp;
                Ngày gửi&nbsp;
                <?php
                    echo $this->Form->input('Tinnhan.tu_ngay', array(
                                                                  'label'	=>	false,
                                                                  'div'		=>	false,
                                                                  'id'		=>	'all_tu_ngay',
                                                                  'class'	=>	'text-input tu_ngay',
                                                                  'style'	=>	'width:70px'
                                                                  ));
                ?>
                đến ngày&nbsp;
                <?php
                    echo $this->Form->input('Tinnhan.den_ngay', array(
                                                                  'label'	=>	false,
                                                                  'div'		=>	false,
                                                                  'id'		=>	'all_den_ngay',
                                                                  'class'	=>	'text-input den_ngay',
                                                                  'style'	=>	'width:70px'
                                                                  ));
                   echo '&nbsp;';
                   
                ?>
                <button class="button" id="btn-all-search">Tìm kiếm</button>
            </form>
            </div>
            <div class="content-box-content" id="all-list-content">
                <img src="/img/circle_ball.gif" />
            </div>
            <div  style="padding:10px">
                <a class="button" href="/tinnhan/compose" title="Click để soạn thảo tin nhắn">Soạn thảo</a>
                <a class="button" href="/tinnhan/sent_delete" data-mode="ajax" data-action="delete-all" data-msg="Vui lòng chọn trong danh sách" data-confirm="Bạn có muốn xóa các Tin nhắn đã chọn hay không ?" data-target="sent-list-content" title="Click để xóa các Tin nhắn đã chọn trong danh sách">Xóa</a>
                <a class="button" href="/tinnhan/sent" title="Click để tải lại danh sách tin nhắn" data-mode="ajax" data-action="update" data-target="sent-list-content">Reload</a>
            </div>
    	</div>
	</div>

</div>

</div>