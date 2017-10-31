    <!--  start related-act-top -->
    <div id="related-act-top">
    	<a href="javascript:void(0)"  title="Click để cập nhật thông tin" id="btn-refresh-notice"><img src="/img/forms/header_related_act.png" width="271" height="43" alt="" /></a>
    </div>
    <!-- end related-act-top -->
    
    <!--  start related-act-bottom -->
    <div id="related-act-bottom">
    
        <!--  start related-act-inner -->
        <div id="related-act-inner">
        
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
                </ul>
                <p style="text-decoration:underline">
                    Công việc đã giao.
                </p>
                <ul class="greyarrow">
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
            <div class="lines-dotted-short"></div>
            
            <div class="left" style="margin-top:2px"><img src="/img/icons/calendar_view_day.png"/></div>
            <div class="right">
                <h5>Công việc phải làm</h5>
                <ul class="greyarrow">
                    <?php 
                        if(!empty($lichcanhan)):
                            foreach($lichcanhan as $item):
                                echo '<li>';
                                echo $this->Html->link($this->Time->format('H:i', $item['Lichlamviec']['ngay_ghinho']) . ' - ' .$item['Lichlamviec']['tieu_de'], '/calendars');
                                echo '</li>';
                            endforeach;
                        else:
                            echo '<li><a href="/calendars" style="color:#0968B3">Hôm nay không có lịch làm việc.</a></li>';
                        endif;
                    ?>
                </ul>
            </div>
            
            <div class="clear"></div>
            
        </div>
        <!-- end related-act-inner -->
        <div class="clear"></div>
    
    </div>
    <!-- end related-act-bottom -->
