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
</head>
<body>
<?php require_once 'cMenuBar.php'; ?>
<div class="notebox">
<script>
	Galleria.loadTheme('galleria/themes/classic/galleria.classic.min.js');
	Galleria.run('#galleria', {
	 facebook: 'album:598880116813244',
	 width: 745,
	 height: 550,
	 lightbox: true});
	 
	function changeAlbum(albumId){
	Galleria.run('#galleria', {
	 facebook: 'album:' + albumId,
	 width: 745,
	 height: 550,
	 lightbox: true});	 
	 }
</script>
<table>
<tr>
	<td>
		<div class="orange" onClick="changeAlbum('598880116813244');"> 最新情報 </div>
		<br>
		<div class="orange" onClick="changeAlbum('624296454271610');"> Love Cat Love Cake </div>
		<br>		
		<div class="orange" onClick="changeAlbum('606886686012587');"> Happy Cat 系列 </div>
		<br>	
		<div class="orange" onClick="changeAlbum('599304856770770');"> Line 彩繪蛋卷系列－熊大 Brown </div>
		<br>
		<div class="orange" onClick="changeAlbum('602794959755093');"> Line 彩繪蛋卷系列－兔兔 Cony </div>
		<br>
		<div class="orange" onClick="changeAlbum('606344102733512');"> Toy Story 系列 - 三眼仔 woo~ </div>
		<br>
		<div class="orange" onClick="changeAlbum('600895496611706');"> 怪獸大學系列－大眼仔與毛毛 </div>
		<br>		 
		<div class="orange" onClick="changeAlbum('605722322795690');"> 鬆弛熊系列－鬆弛熊與豬鼻雞 </div>
		<br>
		<div class="orange" onClick="changeAlbum('599305613437361');"> Kumamon 九州熊 </div>		
	</td>
	<td><div id="galleria"></div></td>
</tr>
</table>

</div>
</html>
	
	
	