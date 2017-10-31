<?php
	$url = Configure::read('Site.website') . '/users/resetpass/' . $info['User']['username'] . '/' . md5($info['User']['username'] . '|' . $info['User']['email']);
?>
Chào <?php echo $info['User']['full_name'] ?> !
<p>
Bạn đã quên mật khẩu truy cập. Chúng tôi muốn bạn xác nhận lại lần nữa bằng cách click vào link sau<BR />
<a href="<?php echo $url ?>"><?php echo $url ?></a>