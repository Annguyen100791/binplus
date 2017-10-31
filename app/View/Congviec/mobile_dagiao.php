<!-- Công việc đã giao PAGE -->

<div data-role="page" id="congviec-dagiao" data-loai="<?php echo $loai ?>">
	<div data-role="header" data-ajax="false">
		<h1><?php echo $page_title ?></h1>
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
    	<div class="content-primary">
    	<ul data-role="listview" id="congviec-dagiao-list" data-inset="true">
        	
        </ul>
        </div>
    </div>
    
    <div data-role="footer">
		<div data-role="controlgroup" data-type="horizontal" id="pagination">
        	<button type="button" id="congviec-dagiao-prev" disabled="disabled" data-icon="arrow-l">Trang trước</button>
        	<button type="button" id="congviec-dagiao-next" disabled="disabled" data-icon="arrow-r">Trang sau</button>
        </div>
	</div>
</div>