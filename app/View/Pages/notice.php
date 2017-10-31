<div class="left" style="margin-top:2px"><img src="/img/icons/page.png"/></div>

<div class="right">

    <h5>Công văn - Văn bản</h5>

    <ul class="greyarrow">

        <li>

        <?php 

			if(empty($vanban))

                echo $this->Html->link('0 văn bản mới.', '/vanban', array('style' => 'color:#0968B3'));

            else

                echo $this->Html->link($vanban . ' văn bản mới chưa đọc.', '/vanban');

		?>

		</li>

		<li>

		<?php

        	if($this->Layout->check_permission('VanBan.trinh') && ($vanbanden_trinh <> 0))

                echo $this->Html->link($vanbanden_trinh . ' văn bản đến chờ trình.', '/vanban/index_vanbanden#vanban-chotrinh');

		?>

        </li>

        <li>

		<?php

			if($this->Layout->check_permission('VanBan.duyet') && ($vanbanden_duyet <> 0))

                echo $this->Html->link($vanbanden_duyet . ' văn bản đến chờ duyệt.', '/vanban/index_vanbanden#vanban-choduyet');

			

        ?>

        </li>

        <li>

		<?php

			if(!empty($vb_traodoi))

                echo $this->Html->link($vb_traodoi . ' văn bản đến có trao đổi.', '/vanban/index#vanban-traodoi');

			

        ?>

        </li>

        <li>

		<?php

			if(!empty($vanban_thaydoi))

                echo $this->Html->link($vanban_thaydoi . ' văn bản thêm chỉ đạo của GĐ VTĐN.', '/vanban/index#vanban-giamdocchidao');

			

        ?>

        </li> 

        <li>
		<?php
			if(!empty($vbgap_notice) && ($this->Session->read('Auth.User.chucdanh_id') == 16 || $this->Session->read('Auth.User.chucdanh_id') == 30 || $this->Session->read('Auth.User.nhanvien_id') == 804 )) // Trưởng PBCN/GĐ đơn vị/Lê Hoàng(ngoại lệ vì tách Tổ này ra khỏi QLM)
                echo $this->Html->link($vbgap_notice . ' văn bản đến cần xử lý gấp trễ tiến độ.', '/vanban/index_notice#notice-gap');
			
        ?>
        </li>
       <li>
		<?php
			if(!empty($vbkhac_notice) && ($this->Session->read('Auth.User.chucdanh_id') == 16 || $this->Session->read('Auth.User.chucdanh_id') == 30 || $this->Session->read('Auth.User.nhanvien_id') == 804)) // Trưởng PBCN/GĐ đơn vị/Lê Hoàng(ngoại lệ vì tách Tổ này ra khỏi QLM)
                echo $this->Html->link($vbkhac_notice . ' văn bản đến khác trễ tiến độ.', '/vanban/index_notice#notice-khac');
			
        ?>
        </li>

    </ul>

</div>



<div class="clear"></div>

<div class="lines-dotted-short"></div>



<div class="left" style="margin-top:2px"><img src="/img/icons/congviec.png"/></div>

<div class="right">

    <h5>Công việc</h5>

    <p style="text-decoration:underline">

    Công việc được giao.

    </p>

    <ul class="greyarrow">

        <li>

        <?php 

            if(empty($duocgiao_baocao))

                echo $this->Html->link('0 công việc báo cáo trễ tiến độ', '/congviec/duocgiao#congviec-unfinished', array('style' => 'color:#0968B3'));

            else

                echo $this->Html->link($duocgiao_baocao . ' công việc báo cáo trễ tiến độ.', '/congviec/duocgiao#congviec-unfinished');

			/*if(!empty($duocgiao_baocao))

                echo $this->Html->link($duocgiao_baocao . ' công việc báo cáo trễ tiến độ.', '/congviec/duocgiao#congviec-unfinished');*/

        ?>

        </li>

        <li>

        <?php 

            if(empty($duocgiao_chuahoanthanh))

                echo $this->Html->link('0 công việc trễ tiến độ', '/congviec/duocgiao#congviec-unfinished', array('style' => 'color:#0968B3'));

            else

                echo $this->Html->link($duocgiao_chuahoanthanh . ' công việc trễ tiến độ.', '/congviec/duocgiao#congviec-unfinished');

        ?>

        </li> 

        <li>

        <?php 

            if(empty($duocgiao_dangthuchien))

                echo $this->Html->link('0 công việc đang thực hiện', '/congviec/duocgiao#congviec-progressing', array('style' => 'color:#0968B3'));

            else

                echo $this->Html->link($duocgiao_dangthuchien . ' công việc đang thực hiện.', '/congviec/duocgiao#congviec-progressing');

        ?>

        </li>

        <li>

        <?php 

            if(empty($duocgiao_comment_moi))

                echo '<span style="color:#0968B3; font-weight:bold">0 công việc được giao có thảo luận mới</span>';

            else

                echo $this->Html->link($duocgiao_comment_moi . ' công việc được giao có thảo luận mới.', '/congviec/duocgiao#congviec-commentmoi');

        ?>

        </li>

    </ul>

    <p style="text-decoration:underline">

        Công việc đã giao.

    </p>

    <ul class="greyarrow">

        <li>

        <?php 

            if(empty($dagiao_baocao))

                echo $this->Html->link('0 công việc báo cáo trễ tiến độ', '/congviec/dagiao#congviec-unfinished', array('style' => 'color:#0968B3'));

            else

                echo $this->Html->link($dagiao_baocao . ' công việc báo cáo trễ tiến độ.', '/congviec/dagiao#congviec-unfinished');

			/*if(!empty($dagiao_baocao))

                echo $this->Html->link($dagiao_baocao . ' công việc báo cáo trễ tiến độ.', '/congviec/dagiao#congviec-unfinished');*/

        ?>

        </li>

        <li>

        <?php 

            if(empty($dagiao_chuahoanthanh))

                echo $this->Html->link('0 công việc trễ tiến độ', '/congviec/dagiao#congviec-unfinished', array('style' => 'color:#0968B3'));

            else

                echo $this->Html->link($dagiao_chuahoanthanh . ' công việc trễ tiến độ.', '/congviec/dagiao#congviec-unfinished');

        ?>

        </li> 

        <li>

        <?php 

            if(empty($dagiao_dangthuchien))

                echo $this->Html->link('0 công việc đang thực hiện', '/congviec/dagiao#congviec-progressing', array('style' => 'color:#0968B3'));

            else

                echo $this->Html->link($dagiao_dangthuchien . ' công việc đang thực hiện.', '/congviec/dagiao#congviec-progressing');

        ?>

        </li>

        <li>

        <?php 

            if(empty($dagiao_comment_moi))

                echo '<span style="color:#0968B3; font-weight:bold">0 công việc đã giao có thảo luận mới</span>';

            else

                echo $this->Html->link($dagiao_comment_moi . ' công việc đã giao có thảo luận mới.', '/congviec/dagiao#congviec-commentmoi');

        ?>

        </li>

    </ul>

    <p style="text-decoration:underline">

        Công việc khẩn.

    </p>

    <ul class="greyarrow">

        <li>

        <?php 

            if(empty($dagiao_khan))

                echo $this->Html->link('0 công việc khẩn đã giao', '/congviec/dagiao#congviec-instant', array('style' => 'color:#0968B3'));

            else

                echo $this->Html->link($dagiao_khan . ' công việc khẩn đã giao.', '/congviec/dagiao#congviec-instant');

        ?>

        </li> 

        <li>

        <?php 

            if(empty($duocgiao_khan))

                echo $this->Html->link('0 công việc khẩn được giao', '/congviec/duocgiao#congviec-instant', array('style' => 'color:#0968B3'));

            else

                echo $this->Html->link($duocgiao_khan . ' công việc khẩn được giao.', '/congviec/duocgiao#congviec-instant');

        //pr($duocgiao_khan);die();

		?>

        

        </li>

    </ul>

</div>



<div class="clear"></div>

<div class="lines-dotted-short"></div>



<div class="left" style="margin-top:2px"><img src="/img/icons/email.png"/></div>

<div class="right">

    <h5>Tin nhắn</h5>

    <ul class="greyarrow">

        <li>

        <?php 

            if(empty($tinnhan))

                echo $this->Html->link('0 tin nhắn mới.', '/tinnhan', array('style' => 'color:#0968B3'));

            else

                echo $this->Html->link($tinnhan . ' tin nhắn chưa đọc.', '/tinnhan');

        ?>

        </li> 

    </ul>

</div>



<div class="clear"></div>



<?php if(!empty($lichcanhan)): ?>

<div class="lines-dotted-short"></div>

<div class="left" style="margin-top:2px"><img src="/img/icons/calendar_view_day.png"/></div>

<div class="right">

    <h5>Công việc phải làm</h5>

    <ul class="greyarrow">

        <?php 

            

                foreach($lichcanhan as $item):

                    echo '<li>';

                    echo $this->Html->link($this->Time->format('H:i', $item['Lichlamviec']['ngay_ghinho']) . ' - ' .$item['Lichlamviec']['tieu_de'], '/calendars/lamviec/2', array('title' => 'Xem toàn bộ lịch làm việc cá nhân'));

                    echo '</li>';

                endforeach;

        ?>

    </ul>

</div>



<div class="clear"></div>

<?php endif; ?>



<div class="clear"></div>

<?php //echo $this->element('sql_dump');?>