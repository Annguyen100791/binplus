<?php

	echo $this->Html->css(array(

				'/js/libs/dynatree/skin/ui.dynatree',

		));

	echo $this->Html->script(array(

		'libs/dynatree/jquery.dynatree'

		));

?>

<style type="text/css">



span.dynatree-active a {

    background-color:#3399ff !important;

    color: white !important;

}

	.dynatree-title div.td {

		float:left;

	   	display: inline;

	   	overflow: hidden;

		padding: 3px;

	}

ul.dynatree-container	{

	padding:0!important;

}

	</style>

<!--  start page-heading -->

<div id="page-heading">

    <h1>Danh sách Nhân sự</h1>

</div>

<!-- end page-heading -->



<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">

<tr>

    <th rowspan="3" class="sized"><img src="/img/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>

    <th class="topleft"></th>

    <td id="tbl-border-top">&nbsp;</td>

    <th class="topright"></th>

    <th rowspan="3" class="sized"><img src="/img/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>

</tr>

<tr>

    <td id="tbl-border-left"></td>

    <td>

    <!--  start content-table-inner ........................................................ START -->

    <div id="content-table-inner">

        <!--  start table-content  -->

            <!--  start data-table ................................................................. -->

            <div  class="content-box">

                <div class="content-box-header"> <!-- Add the class "closed" to the Content box header to have it closed by default -->

                    <div style="float:left">

                        <h3><?php echo $this->Html->image('icons/user.png', array('align' => 'left')) ?>&nbsp; Danh sách nhân sự</h3>

                    </div>

                    <div style="float:right;">

                        <ul class="shortcut-buttons-set" style="padding:6px 0px!important">

                            <li><a class="shortcut-button" href="javascript:void(0)" title="Tìm kiếm nhanh trong danh sách" id="nhanvien-search"><img src="/img/icons/zoom.png"/></a></li>

                            <li><a class="shortcut-button" href="javascript:void(0)" title="CLick để thu hẹp danh sách" id="nhanvien-un-select"><img src="/img/icons/arrow_in.png"/></a></li>

                            

                        </ul>

                        <div style="clear:both"></div>

                    </div>

                    <div style="clear:both"></div>

                </div>

                

                <div class="content-box-content">

                    

                      <div class="tab-content default-tab" style="overflow:auto" id="tinnhan_rightcontent">

                            <div id="search-form" style="padding-bottom:10px">

                            <form>

                                <input type="text" class="text-input" id="search-keyword" style="width:98.5%" />

                            </form>

                            </div>
                             <table width="100%" border="0">
                              <tr>
                                <th style="width:260px; font-weight:bold" align="center">&nbsp;</th>
                                <th style="width:180px; font-weight:bold" align="center">Chức danh</th>
                                <th style="width:150px; font-weight:bold" align="center">Email</th>
                                <th style="width:80px; font-weight:bold" align="center">Đt di động</th>
                                <th style="width:80px; font-weight:bold" align="center">Đt nội bộ</th>
                                <th style="width:50px; font-weight:bold" align="center">Số MEG</th>
                              </tr>
                            </table>
							<!--<ul class="dynatree-container dynatree-no-connector">
                            <li>
                           <span class="dynatree-node dynatree-exp-c dynatree-ico-c">
                           <div>
                            <div class="td" style="width:260px">Đơn vị</div>
                            <div class="td" style="width:180px">Chức danh</div>
                            <div class="td" style="width:150px">Email</div>
                            <div class="td" style="width:80px">Đt Di động</div>
                            <div class="td" style="width:80px">Đt nội bộ</div>
                            <div class="td" style="width:50px">Số MEG</div>		
                            </div>
                            </span>							
                            </li>
                            </ul>-->	
                            <div id="nhanvien-ds">

                            </div>	

                

                      </div>

                </div>  

             </div>

            <!--  end data-table ..................................................................................... -->

        <div class="clear"></div>

     

    </div>

    <!--  end content-table-inner ............................................END  -->

    </td>

    <td id="tbl-border-right"></td>

</tr>

<tr>

    <th class="sized bottomleft"></th>

    <td id="tbl-border-bottom">&nbsp;</td>

    <th class="sized bottomright"></th>

</tr>

</table>