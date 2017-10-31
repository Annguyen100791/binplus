<div data-role="page" id="vanban">
	<div data-role="header" data-ajax="false">
		<h1>Văn bản</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" class="ui-btn-active" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
	<div data-role="content">	
		<ul data-role="listview">
        	<li>
            	<a href="#vanban-chuadoc">Chưa đọc</a>
                <?php if($chuadoc > 0): ?>
                <span class="ui-li-count" style="color:red; font-size:12pt"><?php echo $this->Number->format($chuadoc); ?></span>
                <?php else: ?>
                <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($chuadoc); ?></span>
                <?php endif;?>
            </li>
            <li>
            	<a href="#vanban-tatca" data-direction="reverse">Tất cả</a>
                <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($tatca); ?></span>
            </li>
            <li>
            	<a href="#vanban-di">Đi</a>
                <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($di); ?></span>
            </li>
            <li>
            	<a href="#vanban-den">Đến</a>
                <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($den); ?></span>
            </li>
            <li>
            	<a href="#vanban-theodoi">Được theo dõi</a>
                <span class="ui-li-count" style="font-size:12pt"><?php echo $this->Number->format($theodoi); ?></span>
            </li>
        </ul>
	</div>
	<div data-role="footer">
		<h4><?php echo Configure::read('Mobile.footer') ?></h4>
        
	</div>
</div>



<!-- Văn bản tất cả PAGE -->

<div data-role="page" id="vanban-tatca">
	<div data-role="header" data-ajax="false">
		<h1>Tất cả văn bản</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" class="ui-btn-active" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
    
    <div data-role="content">
    	<div class="content-primary">
    	<ul data-role="listview" id="tatca-list" data-inset="true">
        	
        </ul>
        </div>
    </div>
    
    <div data-role="footer">
		<div data-role="controlgroup" data-type="horizontal" id="pagination">
        	<button type="button" id="prev" disabled="disabled" data-icon="arrow-l">Trang trước</button>
        	<button type="button" id="next" disabled="disabled" data-icon="arrow-r">Trang sau</button>
        </div>
	</div>
</div>


<!-- Văn bản chưa đọc PAGE -->

<div data-role="page" id="vanban-chuadoc">
	<div data-role="header">
		<h1>Văn bản chưa đọc</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" class="ui-btn-active" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
    
    <div data-role="content">
    	<ul data-role="listview" data-inset="true" id="chuadoc-list">
        </ul>
    </div>
    
    <div data-role="footer">
        <div data-role="controlgroup" data-type="horizontal">
        	<button type="button" id="chuadoc-prev" disabled="disabled" data-icon="arrow-l">Trang trước</button>
        	<button type="button" id="chuadoc-next" disabled="disabled" data-icon="arrow-r">Trang sau</button>
        </div>
	</div>
</div>

<!-- Xem văn bản -->

<div data-role="page" id="vanban-detail" data-add-back-btn="true">
	<div data-role="header">
		<h1>Xem văn bản</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" class="ui-btn-active" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
    
    <div data-role="content">
    	<div data-role="collapsible" data-theme="c" data-content-theme="d" data-collapsed="true">
        	<h3>Thông tin văn bản</h3>
            <div id="vanban-info"></div>
        </div>
        
    	<div data-role="collapsible-set" data-theme="c" data-content-theme="d" id="vanban-files">
        	
        </div>
    </div>
    
    <div data-role="footer">
    	<h4><?php echo Configure::read('Mobile.footer') ?></h4>
	</div>
</div>

<!-- Văn bản đi PAGE -->

<div data-role="page" id="vanban-di">
	<div data-role="header">
		<h1>Văn bản đi</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" class="ui-btn-active" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
    
    <div data-role="content">
    	<ul data-role="listview" data-inset="true" id="di-list">
        </ul>
    </div>
    
    <div data-role="footer">
        <div data-role="controlgroup" data-type="horizontal">
        	<button type="button" id="di-prev" disabled="disabled" data-icon="arrow-l">Trang trước</button>
        	<button type="button" id="di-next" disabled="disabled" data-icon="arrow-r">Trang sau</button>
        </div>
	</div>
</div>

<!-- Văn bản đến PAGE -->

<div data-role="page" id="vanban-den">
	<div data-role="header">
		<h1>Văn bản đến</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" class="ui-btn-active" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
    
    <div data-role="content">
    	<ul data-role="listview" data-inset="true" id="den-list">
        </ul>
    </div>
    
    <div data-role="footer">
        <div data-role="controlgroup" data-type="horizontal">
        	<button type="button" id="den-prev" disabled="disabled" data-icon="arrow-l">Trang trước</button>
        	<button type="button" id="den-next" disabled="disabled" data-icon="arrow-r">Trang sau</button>
        </div>
	</div>
</div>


<!-- Văn bản theo dõi PAGE -->

<div data-role="page" id="vanban-theodoi">
	<div data-role="header">
		<h1>Văn bản đang được theo dõi</h1>
        <a href="/mobile/users/logout" data-icon="lock" class="ui-btn-right">Logout</a>
        <div data-role="navbar">
        	<ul>
            	<li><a href="/mobile" data-icon="home">Trang tin</a></li>
                <li><a href="/mobile/vanban" class="ui-btn-active" data-icon="page">Văn bản</a></li>
                <li><a href="/mobile/congviec" data-icon="grid">Công việc</a></li>
                <li><a href="/mobile/tinnhan" data-icon="email">Tin nhắn</a></li>
            </ul>
        </div>
	</div>
    
    <div data-role="content">
    	<ul data-role="listview" data-inset="true" id="theodoi-list">
        </ul>
    </div>
    
    <div data-role="footer">
        <div data-role="controlgroup" data-type="horizontal">
        	<button type="button" id="theodoi-prev" disabled="disabled" data-icon="arrow-l">Trang trước</button>
        	<button type="button" id="theodoi-next" disabled="disabled" data-icon="arrow-r">Trang sau</button>
        </div>
	</div>
</div>