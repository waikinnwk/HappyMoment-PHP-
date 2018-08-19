<table width="100%">
<tr><td>
	<img src="images/hm_pink03.png" alt="Happy Moment" height="120" width="120"/>
	</td>
	<td align="right" style="vertical-align:middle">
		<div class="fb-like" data-href="https://www.facebook.com/happymoment2013" data-width="100" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
	</td>
	</tr>
</table>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<br>
<div id="navmenubar">
<li><a href="index.php">主頁</a></li>
<li><a href="allClassList.php">課程報名</a></li>
<li><a href="application.php">課程時間表</a></li>
<li><a href="cake.php">課程種類</a></li>
<li><a href="howtoapply.php">報名流程</a></li>
<!--<li><a href="album.php">圖片集</a></li>-->
<li><a href="address.php">上課地址</a></li>
<li><a href="rule.php">課堂守則</a></li>
<!--<li><a href="privateclass.php">包場需知</a></li>-->
<li><a href="contactus.php">聯絡我們</a></li>
</div>
<br>
<?php
		require_once 'common/common.php';
		$msgSysValue = getSysValue('NoticeMsg');
		if($msgSysValue['status'] == 'A'){
			echo "<div class=\"notebox\" style=\"magin:20 20 20 0 \">";
			echo "<marquee width=100% scrollamount=10><font size=\"12\" face=\"chaoganghei\">".$msgSysValue['value']."</font></marquee>";
			echo "</div><br>";
		}
?>