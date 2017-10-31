<div data-role="page" id="tinnhan" data-title="BIN+ - Tin nhắn">
	<div data-role="header" data-ajax="false">
		<h1>Tin nhắn</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" class="ui-btn-active" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
	<div data-role="content">	
		<ul data-role="listview">
        	<li>
            	<a href="#tinnhan-chuadoc" style="padding-left:80px">
                    <img src="/img/mobile/email_closed.png" style="padding:10px" />
                    <h3>Chưa đọc</h3>
                    <?php if($chuadoc > 0): ?>
                    <span class="ui-li-count" style="color:red; font-size:12pt"><?php echo $this->Number->format($chuadoc); ?></span>
                    <?php else: ?>
                    <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($chuadoc); ?></span>
                    <?php endif;?>
                </a>
            </li>
            <li>
            	<a href="#tinnhan-tatca" data-direction="reverse" style="padding-left:80px">
                    <img src="/img/mobile/email_open.png" style="padding:10px" />
                    <h3>Đã nhận</h3>
                    <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($tatca); ?></span>
                </a>
            </li>
            <li>
            	<a href="#tinnhan-dagui" style="padding-left:80px">
                	<img src="/img/mobile/email_write.png" style="padding:10px" />
                    <h3>Đã gửi</h3>
                    <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($dagui); ?></span>
                </a>
            </li>

        </ul>
	</div>
	<div data-role="footer" class="ui-bar">
    	<a href="/mobile/tinnhan/compose" data-ajax="false" id="tn-compose" data-role="button" data-icon="edit">Soạn thảo tin nhắn</a>
	</div>
</div>



<!-- Tin nhắn tất cả PAGE -->

<div data-role="page" id="tinnhan-tatca" data-title="Tin nhắn đã nhận">
	<div data-role="header" data-ajax="false">
		<h1>Tin nhắn đã nhận</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" class="ui-btn-active" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
    
    <div data-role="content">
    	<div class="content-primary">
    		<ul data-role="listview" id="tatca-list" data-inset="true" data-split-icon="question"></ul>
        </div>
    </div>
    
    <div data-role="footer" class="ui-bar">
		<div data-role="controlgroup" data-type="horizontal" id="pagination">
        	<button type="button" id="tatca-prev" disabled="disabled" data-icon="arrow-l">Trang trước</button>
        	<button type="button" id="tatca-next" disabled="disabled" data-icon="arrow-r">Trang sau</button>
            <a href="/mobile/tinnhan/compose" data-ajax="false" data-role="button" data-theme="b" data-icon="edit">Soạn thảo</a>

        </div>
        
	</div>
</div>


<!-- Tin nhắn chưa đọc PAGE -->

<div data-role="page" id="tinnhan-chuadoc" data-title="Tin nhắn chưa đọc">
	<div data-role="header" data-ajax="false">
		<h1>Tin nhắn chưa đọc</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" class="ui-btn-active" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
    
    <div data-role="content">
    	<ul data-role="listview" data-inset="true" id="chuadoc-list">
        </ul>
    </div>
    
    <div data-role="footer" class="ui-bar">
        <div data-role="controlgroup" data-type="horizontal">
        	<button type="button" id="chuadoc-prev" disabled="disabled" data-icon="arrow-l">Trang trước</button>
        	<button type="button" id="chuadoc-next" disabled="disabled" data-icon="arrow-r">Trang sau</button>
        </div>
	</div>
</div>

<!-- Xem tin nhắn -->

<div data-role="page" id="tinnhan-detail" data-title="Xem tin nhắn" data-id="">
	<div data-role="header" data-ajax="false">
		<h1>Xem tin nhắn</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" class="ui-btn-active" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
    
    <div data-role="content">
    	<div data-role="collapsible-set" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d">
            <div data-role="collapsible" data-collapsed="false" data-content-theme="c">
            	<h3>Nội dung</h3>
                <div class="content-primary">
                <h4 id="tinnhan-title"></h4>
                <p>bởi <em id="tinnhan-nguoigui"></em> ngày <em id="tinnhan-ngaygui"></em>
                	<BR />
                	<blockquote id="tinnhan-noidung"></blockquote>
                </p>
                </div>
            </div>
            
            <div data-role="collapsible" data-content-theme="c" id="attach-container">
            <h3>File đính kèm</h3>
            <ol id="tinnhan-attach" data-role="listview"></ol>
            </div>
            
        </div>
    </div>
    
    <div data-role="footer" class="ui-bar">
    	<div data-role="controlgroup" data-type="horizontal">
        	<a href="/mobile/tinnhan/compose" data-ajax="false" id="tinnhan-detail-compose" data-role="button" data-icon="edit">Soạn thảo</a>
            <a href="#" data-ajax="false" id="tinnhan-detail-reply" data-role="button" data-icon="chat">Trả lời</a>
            <a href="#" data-ajax="false" id="tinnhan-detail-forward" data-role="button" data-icon="direction">Chuyển tiếp</a>
            <a href="#" data-ajax="false" id="tinnhan-detail-delete" data-rel="back" data-role="button" data-icon="trash">Xóa</a>
        </div>
	</div>
</div>

<!-- Xem tin nhắn đã gửi -->

<div data-role="page" id="tinnhan-sent" data-title="Xem tin nhắn" data-id="">
	<div data-role="header" data-ajax="false">
		<h1>Xem tin nhắn đã gửi</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" class="ui-btn-active" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
    
    <div data-role="content">
    	<div data-role="collapsible-set" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d">
            <div data-role="collapsible" data-collapsed="false" data-content-theme="c">
            	<h3>Nội dung</h3>
                <div class="content-primary">
                <h4 id="tinnhan-sent-title"></h4>
                <p>ngày gửi <em id="tinnhan-sent-ngaygui"></em>
                	<BR />
                	<blockquote id="tinnhan-sent-noidung"></blockquote>
                </p>
                </div>
            </div>
            <div data-role="collapsible" data-content-theme="c">
                <h3>Người nhận</h3>
                <ol id="sent-tinnhan-nguoinhan" data-role="listview"></ol>
            </div>
            <div data-role="collapsible" data-content-theme="c" id="sent-attach-container">
                <h3>File đính kèm</h3>
                <ol id="sent-tinnhan-attach" data-role="listview"></ol>
            </div>
            
        </div>
    </div>
    
    <div data-role="footer" class="ui-bar">
    	<div data-role="controlgroup" data-type="horizontal">
        	<a href="/mobile/tinnhan/compose" data-ajax="false" id="tinnhan-sent-compose" data-role="button" data-icon="edit">Soạn thảo</a>
            <a href="#" data-ajax="false" id="tinnhan-sent-delete" data-rel="back" data-role="button" data-icon="trash">Xóa</a>
        </div>
	</div>
</div>

<!-- Tin nhắn đã gửi PAGE -->

<div data-role="page" id="tinnhan-dagui" data-title="Tin nhắn đã gửi">
	<div data-role="header" data-ajax="false">
		<h1>Tin nhắn đã gửi</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" class="ui-btn-active" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
    
    <div data-role="content">
    	<ul data-role="listview" data-inset="true" id="dagui-list">
        </ul>
    </div>
    
    <div data-role="footer" class="ui-bar">
        <div data-role="controlgroup" data-type="horizontal">
        	<button type="button" id="dagui-prev" disabled="disabled" data-icon="arrow-l">Trang trước</button>
        	<button type="button" id="dagui-next" disabled="disabled" data-icon="arrow-r">Trang sau</button>
        </div>
	</div>
</div>

<div data-role="dialog" id="confirm" data-title="Xác nhận thông tin">
	<div data-role="header" data-theme="d">
        <h1 class="confirm-title">Dialog</h1>
    </div>
  <div data-role="content">
    <p class="confirm-desc">???</p>
    <a href="#" class="confirm-do" data-role="button" data-theme="b" data-rel="back">Có</a>
    <a href="#" data-role="button" data-theme="c" data-rel="back">Không</a>
  </div>
</div>

<div data-role="dialog" id="tinnhan-options" data-title="Thao tác với tin nhắn">
	<div data-role="header" data-theme="d">
    	<h1>Lựa chọn thao tác</h1>
    </div>
    <div data-role="content" data-ajax="false">
    	<p>Vui lòng chọn thao tác với tin nhắn đã chọn</p>
        <a href="#" data-role="button" id="tinnhan-options-reply" data-theme="b" data-rel="back" data-icon="chat">Trả lời</a>
        <a href="#" data-role="button" id="tinnhan-options-forward" data-theme="b" data-rel="back" data-icon="direction">Chuyển tiếp</a>
        <button type="button" id="tinnhan-options-delete" data-rel="back" data-theme="b" data-icon="trash">Xóa</button>
    </div>
</div>