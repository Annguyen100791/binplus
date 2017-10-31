<!-- CONGVIEC PAGE -->
<div data-role="page" id="congviec">
	<div data-role="header" data-ajax="false">
		<h1>Công việc</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" class="ui-btn-active" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
	<div data-role="content">	
    
    	<?php if($dagiao): ?>
		<div data-role="collapsible" data-ajax="false" data-theme="b" data-content-theme="d" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" data-inset="true" data-collapsed="false">
        	<h2>Công việc đã giao</h2>
            <ul data-role="listview">
                <li>
                    <a href="/mobile/congviec/dagiao/dangthuchien" style="padding-left:80px">
                        <img src="/img/mobile/task.png" style="padding:10px" />
                        <h3>Đang thực hiện</h3>
                        <?php if($dagiao_dangthuchien > 0): ?>
                        <span class="ui-li-count" style="color:red; font-size:12pt"><?php echo $this->Number->format($dagiao_dangthuchien); ?></span>
                        <?php else: ?>
                        <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($dagiao_dangthuchien); ?></span>
                        <?php endif;?>
                    </a>
                </li>
                <li>
                    <a href="/mobile/congviec/dagiao/tretiendo" style="padding-left:80px">
                        <img src="/img/mobile/task.png" style="padding:10px" />
                        <h3>Trễ tiến độ</h3>
                        <?php if($dagiao_chuahoanthanh > 0): ?>
                        <span class="ui-li-count" style="color:red; font-size:12pt"><?php echo $this->Number->format($dagiao_chuahoanthanh); ?></span>
                        <?php else: ?>
                        <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($dagiao_chuahoanthanh); ?></span>
                        <?php endif;?>
                    </a>
                </li>
                <li>
                	<a href="/mobile/congviec/dagiao/dathuchien" style="padding-left:80px">
                        <img src="/img/mobile/task.png" style="padding:10px" />
                        <h3>Đã thực hiện</h3>
                        <?php if($dagiao_hoanthanh > 0): ?>
                        <span class="ui-li-count" style="color:red; font-size:12pt"><?php echo $this->Number->format($dagiao_hoanthanh); ?></span>
                        <?php else: ?>
                        <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($dagiao_hoanthanh); ?></span>
                        <?php endif;?>
                    </a>
                </li>
                
            </ul>
        </div>
        <?php endif;?>
        
        <?php if($duocgiao):?>
        
        <div data-role="collapsible" data-theme="b" data-content-theme="d" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" data-inset="true" data-collapsed="false">
        	<h2>Công việc được giao</h2>
            <ul data-role="listview">
            	<li>
                    <a href="/mobile/congviec/duocgiao/dangthuchien" style="padding-left:80px">
                        <img src="/img/mobile/task.png" style="padding:10px" />
                        <h3>Đang thực hiện</h3>
                        <?php if($duocgiao_dangthuchien > 0): ?>
                        <span class="ui-li-count" style="color:red; font-size:12pt"><?php echo $this->Number->format($duocgiao_dangthuchien); ?></span>
                        <?php else: ?>
                        <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($duocgiao_dangthuchien); ?></span>
                        <?php endif;?>
                    </a>
                </li>
                <li>
                    <a href="/mobile/congviec/duocgiao/tretiendo" style="padding-left:80px">
                        <img src="/img/mobile/task.png" style="padding:10px" />
                        <h3>Trễ tiến độ</h3>
                        <?php if($duocgiao_chuahoanthanh > 0): ?>
                        <span class="ui-li-count" style="color:red; font-size:12pt"><?php echo $this->Number->format($duocgiao_chuahoanthanh); ?></span>
                        <?php else: ?>
                        <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($duocgiao_chuahoanthanh); ?></span>
                        <?php endif;?>
                    </a>
                </li>
                <li>
                    <a href="/mobile/congviec/duocgiao/dathuchien" style="padding-left:80px">
                        <img src="/img/mobile/task.png" style="padding:10px" />
                        <h3>Đã thực hiện</h3>
                        <?php if($duocgiao_hoanthanh > 0): ?>
                        <span class="ui-li-count" style="color:red; font-size:12pt"><?php echo $this->Number->format($duocgiao_hoanthanh); ?></span>
                        <?php else: ?>
                        <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($duocgiao_hoanthanh); ?></span>
                        <?php endif;?>
                    </a>
                </li>
                
            </ul>
        </div>
        <?php endif;?>
	</div>
	<div data-role="footer" class="ui-bar">
    	<h4><?php echo Configure::read('Mobile.footer') ?></h4>
	</div>
</div>


