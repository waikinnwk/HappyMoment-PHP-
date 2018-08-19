<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<?php require_once 'commonHeader.php'; ?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
	<script src="galleria/galleria-1.2.9.min.js"></script>
	<script src="galleria/plugins/facebook/galleria.facebook.js"></script>
	<script src="galleria/themes/classic/galleria.classic.min.js"></script>
	<link rel="stylesheet" href="galleria/themes/classic/galleria.classic.css">
	<script>
		Galleria.loadTheme('galleria/themes/classic/galleria.classic.min.js');
		Galleria.run('#galleria', {
		 facebook: 'album:624296454271610',
		 width: 800,
		 height: 460,
		 lightbox: true});
	</script>

</head>
<body>
<?php require_once 'cMenuBar.php'; ?>
<div class="notebox">
<table>
<tr>
<td>
	<img src="images/catsociety.jpg" alt="Love Cat•Love Cake"  height="720" width="508"/>
</td>
<td>
	<table>
	<tr>
		<td><font size="5" style="line-height:180%">
			Love Cat•Love Cake 
			<br>
			Happy Moment x 香港群貓會，為流浪貓貓出一分力。
			<br>
			由2013年8月18日至10月18日期間，
			<br>
			凡於Happy Moment參與 Happy Cat系列彩繪卷蛋製作班, 
			<br>
			學費扣成本後將全數撥捐香港群貓會作幫助流浪貓之用。
			</font>
		</td>
		</tr>
		<tr>
		<td>
		<font size="5" style="line-height:180%">
		條款及細則:
		<br>
		1）所有同學上課時必需出示入數紙
		<br>
		2）如欲2人1CAKE(兩人製作一個蛋榚)另+$50 (請報名時於Remark註明)
		<br>
		3）請於Remark註明欲製作之貓貓捲蛋 (四選一），如無註明則隨機指派
		<br>
		4）一旦報名，則代表同意並遵守課堂守則
		</font>
		<br>
		</td>
	</tr>
	</table>
</td>
</tr>
<tr>
	<td colspan="2">
		<br>
		<h2>課堂花絮</h2>
		<br>
		<div id="galleria"></div>
	</td>
</tr>
</table>
</div>


</body>
</html>