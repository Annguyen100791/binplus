<div data-role="page" id="home">
	<div data-role="header" data-ajax="false">
    	<h1>Trang tin</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" class="ui-btn-active" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
	<div data-role="content">	
    	<div class="content-primary">
		<ul data-role="listview" data-ajax="false">
			<li>
            	<a href="/mobile/vanban#vanban-chuadoc" style="padding-left:80px">
                	<img src="/img/mobile/note.png" style="padding:10px" />
                	<h3>Văn bản chưa đọc</h3>
                    <?php if($vanban > 0): ?>
                	<span class="ui-li-count" style="color:red; font-size:12pt"><?php echo $this->Number->format($vanban); ?></span>
                    <?php else: ?>
                    <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($vanban); ?></span>
                    <?php endif;?>
                </a>
            </li>
            <li>
            	<a href="/mobile/congviec" style="padding-left:80px">
                    <img src="/img/mobile/task.png" style="padding:10px" />
                    <h3>Công việc chưa hoàn thành</h3>
                    <?php if($duocgiao_chuahoanthanh > 0): ?>
                    <span class="ui-li-count" style="color:red; font-size:12pt"><?php echo $this->Number->format($duocgiao_chuahoanthanh); ?></span>
                    <?php else: ?>
                    <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($duocgiao_chuahoanthanh); ?></span>
                    <?php endif;?>
                </a>
            </li>
			<li>
            	<a href="/mobile/tinnhan#tinnhan-chuadoc" style="padding-left:80px">
                    <img src="/img/mobile/email_closed.png" style="padding:10px" />
                    <h3>Tin nhắn chưa đọc</h3>
                    <?php if($tinnhan > 0): ?>
                    <span class="ui-li-count" style="color:red; font-size:12pt"><?php echo $this->Number->format($tinnhan); ?></span>
                    <?php else: ?>
                    <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($tinnhan); ?></span>
                    <?php endif;?>
                </a>
            </li>
		</ul>		
        </div>
	</div>
	<div data-role="footer">
		<h4><?php echo Configure::read('Mobile.footer') ?></h4>
	</div>
</div>