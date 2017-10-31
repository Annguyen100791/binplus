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
					
        <h3><?php echo $this->Html->image('icons/note.png', array('align' => 'left')) ?>&nbsp; Tìm kiếm </h3>
        
        <ul class="content-box-tabs">
            <li><a href="#congviec-duocgiao" class="default-tab" rel="congviec-duocgiao" id="congviec-duocgiao-tab"><?php echo $this->Html->image('icons/note.png', array('align' => 'left')) ?>&nbsp; Công việc được giao</a></li>
            <li><a href="#congviec-dagiao" rel="congviec-dagiao" id="congviec-dagiao-tab"><?php echo $this->Html->image('icons/note.png', array('align' => 'left')) ?>&nbsp; Công việc đã giao</a></li>
        </ul>
        <div class="clear"></div>
        
    </div> <!-- End .content-box-header -->
    
    <div class="content-box-content">
    	<div class="tab-content default-tab" id="congviec-duocgiao">
        	<form id="form-duocgiao-search">
        	<div align="right" style="padding-bottom:10px">
            	<table width="100%">
                	<tr style="height:37px">
                    	<td style="text-align:right; padding-right:10px" width="30%">Tên / nội dung công việc </td>
                        <td>
                        	<input type="text" class="text-input" name="data[keyword]" style="width:380px" />
                            &nbsp; <button type="submit" class="button" id="btn-duocgiao-search">Tìm kiếm</button>
                        </td>
                    </tr>
                    <tr style="height:37px">
                    	<td style="text-align:right;padding-right:10px">Từ ngày </td>
                        <td>
                        	<input type="text" class="text-input datepicker" style="width:100px" id="dg_ngay_batdau" name="ngay_batdau" />
                        	&nbsp;Đến ngày 
                            <input type="text" class="text-input datepicker" style="width:100px" id="dg_ngay_ketthuc" name="ngay_ketthuc" />
                            &nbsp;
                        	<select name="mucdo_hoanthanh">
                            	<option value=""> .. mức độ hoàn thành ..</option>
                                <option value="dahoanthanh">Đã hoàn thành</option>
                                <option value="chuahoanthanh">Chưa hoàn thành</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            </form>
            <div id="cvduocgiao-list-content" style="padding:5px 0px">
            	<img src="/img/circle_ball.gif" />
            </div>
        </div>
    	<div class="tab-content" id="congviec-dagiao">
        	<form id="form-dagiao-search">
        	<div align="right" style="padding-bottom:10px">
            	<table width="100%">
                	<tr style="height:37px">
                    	<td style="text-align:right;padding-right:10px" width="30%">Tên / nội dung công việc </td>
                        <td>
                        	<input type="text" class="text-input" name="keyword" style="width:380px" />
                            &nbsp; <button type="submit" class="button" id="btn-dagiao-search">Tìm kiếm</button>
                        </td>
                    </tr>
                    <tr style="height:37px">
                    	<td style="text-align:right;padding-right:10px" width="30%">Nhân viên được giao việc </td>
                        <td>
                        	 <?php
								echo $this->Form->input('nguoinhan_id', 
									array(
										  'div'		=>	false,
										  'class'	=>	'text-input',
										  'id'		=>	'nguoinhan_id',
										  'style'	=>	'min-width:390px; width:auto',
										  'options'	=>	$nv,
										  'empty'	=>	''
										  )
								);
							?>
                            
                        </td>
                    </tr>
                    <tr style="height:37px">
                    	<td style="text-align:right;padding-right:10px" width="30%">Loại công việc </td>
                        <td>
                        	 <?php
								echo $this->Form->input('loaicongviec_id', 
									array(
										  'div'		=>	false,
										  'class'	=>	'text-input',
										  'id'		=>	'loaicongviec_id',
										  'style'	=>	'min-width:390px; width:auto',
										  'options'	=>	$loai_congviec,
										  'empty'	=>	''
										  )
								);
							?>
                            
                        </td>
                    </tr>
                    <tr style="height:37px">
                    	<td style="text-align:right;padding-right:10px">Từ ngày </td>
                        <td>
                        	<input type="text" class="text-input datepicker" style="width:100px" id="dag_ngay_batdau" name="ngay_batdau" />
                        	&nbsp;Đến ngày 
                            <input type="text" class="text-input datepicker" style="width:100px" id="dag_ngay_ketthuc" name="ngay_ketthuc" />
                            &nbsp;
                        	<select name="mucdo_hoanthanh">
                            	<option value=""> .. mức độ hoàn thành ..</option>
                                <option value="dahoanthanh">Đã hoàn thành</option>
                                <option value="chuahoanthanh">Chưa hoàn thành</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            </form>
            <div id="cvdagiao-list-content" style="padding:5px 0px">
            	<img src="/img/circle_ball.gif" />
            </div>
    	</div>
	</div>

</div>
	
    
</div>

</div>

</div>
<style>
form label {
    display: none;
    font-weight: bold;
    padding: 0 0 10px;
} 
</style>