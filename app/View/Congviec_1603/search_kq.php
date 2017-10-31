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
					
        <h3><?php echo $this->Html->image('icons/note.png', array('align' => 'left')) ?>&nbsp; Tra cứu kết quả xử lý công việc </h3>
        
        <ul class="content-box-tabs">
            <li><a href="#congviec-vtdn" class="default-tab" rel="congviec-vtdn" id="congviec-vtdn-tab"><?php echo $this->Html->image('icons/note.png', array('align' => 'left')) ?>&nbsp; Công việc VTĐN giao</a></li>
            <li><a href="#congviec-donvi" rel="congviec-donvi" id="congviec-donvi-tab"><?php echo $this->Html->image('icons/note.png', array('align' => 'left')) ?>&nbsp; Công việc Phòng ban/Đơn vị giao</a></li>
        </ul>
        <div class="clear"></div>
        
    </div> <!-- End .content-box-header -->
    
    <div class="content-box-content">
    	<div class="tab-content default-tab" id="congviec-vtdn">
        	<form id="form-vtdn-search">
        	<div align="right" style="padding-bottom:10px">
            	<table width="100%">
                	<tr style="height:37px">
                    	<td style="text-align:right; padding-right:10px" width="30%">Kết quả xử lý </td>
                        <td>
                        	<select name="mucdo_hoanthanh">
                            	<option value=""> .. mức độ hoàn thành ..</option>
                                <option value="dahoanthanh">Đã hoàn thành</option>
                                <option value="chuahoanthanh">Chưa hoàn thành</option>
                            </select>
                            &nbsp; <button type="submit" class="button" id="btn-vtdn-search">Tìm kiếm</button>
                        </td>
                    </tr>
                    <tr style="height:37px">
                    	<td style="text-align:right;padding-right:10px">Từ ngày </td>
                        <td>
                        	<input type="text" class="text-input datepicker" style="width:100px" id="dg_ngay_batdau" name="ngay_batdau" />
                        	&nbsp;Đến ngày 
                            <input type="text" class="text-input datepicker" style="width:100px" id="dg_ngay_ketthuc" name="ngay_ketthuc" />
                            &nbsp;
                        	
                        </td>
                    </tr>
                </table>
            </div>
            </form>
            <div id="cvvtdngiao-list-content" style="padding:5px 0px">
            	<img src="/img/circle_ball.gif" />
            </div>
        </div>
    	<div class="tab-content" id="congviec-donvi">
        	<form id="form-donvi-search">
        	<div align="right" style="padding-bottom:10px">
            	<table width="100%">
                	<tr style="height:37px">
                    	<td style="text-align:right; padding-right:10px" width="30%">Kết quả xử lý </td>
                        <td>
                        	<select name="mucdo_hoanthanh">
                            	<option value=""> .. mức độ hoàn thành ..</option>
                                <option value="dahoanthanh">Đã hoàn thành</option>
                                <option value="chuahoanthanh">Chưa hoàn thành</option>
                            </select>
                            &nbsp; <button type="submit" class="button" id="btn-donvi-search">Tìm kiếm</button>
                        </td>
                    </tr>
                    <tr style="height:37px">
                    	<td style="text-align:right;padding-right:10px">Từ ngày </td>
                        <td>
                        	<input type="text" class="text-input datepicker" style="width:100px" id="dg_ngay_batdau" name="ngay_batdau" />
                        	&nbsp;Đến ngày 
                            <input type="text" class="text-input datepicker" style="width:100px" id="dg_ngay_ketthuc" name="ngay_ketthuc" />
                            &nbsp;
                        	
                        </td>
                    </tr>
                </table>
            </div>
            </form>
            <div id="cvdonvigiao-list-content" style="padding:5px 0px">
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