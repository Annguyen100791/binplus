<?php

	echo $this->Html->script(

		array(

			  'libs/jquery/jquery.hashchange'

			  )

	);

?>

<div style="padding:20px">

<div class="content-box" id="congviec-box"><!-- Start Content Box -->

    <div class="content-box-header">

        <h3><?php echo $this->Html->image('icons/note.png', array('align' => 'left')) ?>&nbsp; Công việc đã giao</h3>

        <ul class="content-box-tabs">

            <li><a href="#congviec-all" rel="congviec-all" class="default-tab" id="congviec-all-tab"><?php echo $this->Html->image('icons/note.png', array('align' => 'left')) ?>&nbsp; Tất cả</a></li>

            <li><a href="#congviec-instant" rel="congviec-instant" id="congviec-instant-tab"><?php echo $this->Html->image('icons/note_error.png', array('align' => 'left')) ?>&nbsp; Khẩn</a></li>

            <li><a href="#congviec-baocao" rel="congviec-baocao" id="congviec-baocao-tab"><?php echo $this->Html->image('icons/note_error.png', array('align' => 'left')) ?>&nbsp; Theo dõi, xem báo cáo</a></li>

            <li><a href="#congviec-progressing" rel="congviec-progressing" id="congviec-progressing-tab"><?php echo $this->Html->image('icons/note_error.png', array('align' => 'left')) ?>&nbsp; Đang thực hiện</a></li>

            <li><a href="#congviec-unfinished" rel="congviec-unfinished" id="congviec-unfinished-tab"><?php echo $this->Html->image('icons/note_error.png', array('align' => 'left')) ?>&nbsp; Trễ tiến độ</a></li>

            <li><a href="#congviec-finished" rel="congviec-finished" id="congviec-finished-tab"><?php echo $this->Html->image('icons/note_go.png', array('align' => 'left')) ?>&nbsp; Đã hoàn thành</a></li>
			<li><a href="#congviec-commentmoi" rel="congviec-commentmoi" id="congviec-commentmoi-tab"><?php echo $this->Html->image('icons/note_go.png', array('align' => 'left')) ?>&nbsp; Có thảo luận mới</a></li>

        </ul>

        <div class="clear"></div>

    </div> <!-- End .content-box-header -->

    <div class="content-box-content">

    	<!-- Tất cả -->

    	<div class="tab-content default-tab" id="congviec-all">

        	<img src="/img/circle_ball.gif" />

        </div>

    	<div class="tab-content" id="congviec-instant">

        	<img src="/img/circle_ball.gif" />

    	</div>

        <div class="tab-content" id="congviec-baocao">

        	<img src="/img/circle_ball.gif" />

        </div>

        <div class="tab-content" id="congviec-progressing">

        	<img src="/img/circle_ball.gif" />

    	</div>

        <div class="tab-content" id="congviec-unfinished">

        	<img src="/img/circle_ball.gif" />

    	</div>

    	<div class="tab-content" id="congviec-finished">

        	<img src="/img/circle_ball.gif" />

    	</div>
		<div class="tab-content" id="congviec-commentmoi">

        	<img src="/img/circle_ball.gif" />

    	</div>

	</div>

</div>

    <div style=" padding:10px 0"><span style="font-weight:bold; text-decoration:underline;">Chú thích</span></div>

    <div>

        <div style="width:30px; height:15px; background-color:#fffbcc; border:1px solid #999; float:left; margin-right:5px"></div>

        <div style="float:left; margin-right:5px">Trễ tiến độ</div>

        <div style="width:30px; height:15px; background-color:#d5ffce; border:1px solid #999; float:left; margin-right:5px"></div>

        <div style="float:left; margin-right:5px">Đã hoàn thành</div>

        <div style="width:30px; height:15px; background-color:#eaf5ff; border:1px solid #999; float:left; margin-right:5px"></div>

        <div style="float:left; margin-right:5px">Đang thực hiện</div>

    </div>

</div>