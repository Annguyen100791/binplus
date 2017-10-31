<!--  start page-heading -->
<div id="page-heading">
	<div style="float:left"><h1>Thiết đặt cấu hình <?php echo $prefixes[$prefix] ?></h1></div>
    <div style="clear:both"></div>
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
    <!--  start content-table-inner ...................................................................... START -->
    <div id="content-table-inner">
		<div id="tinhchatvanban-list-content">
        	<?php
		 	echo $this->Session->flash();
			echo $this->Form->create('Setting', array(
				'url' => array(
					'controller' => 'settings',
					'action' => 'prefix',
					$prefix,
				),
				'id'	=>	'form-setting-add'
			));
		?>
        <fieldset>
		<?php
            $i = 0;
            foreach ($settings AS $setting) {
                $key = $setting['Setting']['key'];
                $keyE = explode('.', $key);
                $keyTitle = Inflector::humanize($keyE['1']);
    
                $label = $keyTitle;
                if ($setting['Setting']['title'] != null) {
                    $label = $setting['Setting']['title'];
                }
    
                $inputType = 'text';
                if ($setting['Setting']['input_type'] != null) {
                    $inputType = $setting['Setting']['input_type'];
                }
    
                echo '<p>';
                    echo $this->Form->input("Setting.$i.id", array('value' => $setting['Setting']['id']));
                    echo $this->Form->input("Setting.$i.key", array('type' => 'hidden', 'value' => $key));
                    if ($setting['Setting']['input_type'] == 'checkbox') 
					{
                        if ($setting['Setting']['value'] == 1) {
                            echo $this->Form->input("Setting.$i.value", array(
                                'label' => $label,
                                'type' => $setting['Setting']['input_type'],
                                'checked' => 'checked',
                                'rel' => $setting['Setting']['description'],
                            ));
                        } else {
                            echo $this->Form->input("Setting.$i.value", array(
                                'label' => $label,
                                'type' => $setting['Setting']['input_type'],
                                'rel' => $setting['Setting']['description'],
                            ));
                        }
                    } else 
					{
                        echo $this->Form->input("Setting.$i.value", array(
                            'label' => $label,
                            'type' 	=> $inputType,
                            'value' => $setting['Setting']['value'],
                            'rel' 	=> $setting['Setting']['description'],
							'class' => 'text-input large-input'
                        ));
                    }
                echo "</p>";
                $i++;
            }
        ?>
        </fieldset>
    	<div style="padding:10px">
            <input class="button" type="submit" value="Lưu dữ liệu" id="btn-setting-save" />
        </div>
        <?php
            echo $this->Form->end();
        ?>
        </div>
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