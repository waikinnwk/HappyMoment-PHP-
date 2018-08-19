<?php require_once 'common/common.php'; ?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link rel="stylesheet" href="css/common.css" media="screen, projection" />
	<script type="text/javascript" src="js/common.js"></script>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/tabcontent.js"></script>
	<link href="css/tabcontent.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="images/hm_pink03.png"/>
	<title>Happy Moment</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
	<script src="galleria/galleria-1.3.5.min.js"></script>
	<script src="galleria/plugins/facebook/galleria.facebook.js"></script>
	<script src="galleria/themes/classic/galleria.classic.min.js"></script>
	<link rel="stylesheet" href="galleria/themes/classic/galleria.classic_cake_gp.css">	
	<script type="text/javascript">
	
	Galleria.loadTheme('galleria/themes/classic/galleria.classic.min.js');
	 
	function loadGalleria(id,albumId){
		Galleria.run('#'+id + '_galleria', {
		 facebook: 'album:'+albumId,
		 width: 500,
		 height: 300,
		 lightbox: true});	
	}
	 
	function changeAlbum(id,albumId,gpId,name){
		Galleria.run('#' + id + '_galleria', {
		 facebook: 'album:' + albumId,
		 width: 500,
		 height: 300,
		 wait: true,
		 lightbox: true});
		
		var btn = document.getElementById(id + 'Btn');
		btn.value = name + " - 立即報名";
		
		var btnD = document.getElementById(id + 'BtnD');
		btnD.value = name + " - 立即報名";
		
		document.getElementById(id + '_selectedGP').value = gpId;
	 }
	 
	 function openClassList(groupId){
		window.location.href = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/classList.php?groupId=";?>" + groupId;
	 }

	</script>
	<style type="text/css">
	<?php
	$tabList_css = getCakeClassTab();
	foreach ($tabList_css as $tab_css)
	{
		$groupList_css = getCakeClassGroup($tab_css["id"]);
		foreach ($groupList_css as $gp_css)
		{
			echo "#".$tab_css["id"]."_".$gp_css["id"]."_galleria{height:300px};\n";
			echo "#".$tab_css["id"]."_".$gp_css["id"]."_galleria-loader{height:300px};\n";
			echo ".".$tab_css["id"]."_".$gp_css["id"]."_galleria-stage {
						position: absolute;
						top: 10px;
						bottom: 60px;
						height: 300px;
						left: 10px;
						right: 10px;
						overflow:hidden;
					};\n";
		}								
	}
	?>
	</style>
</head>
<body>
<?php require_once 'cMenuBar.php'; ?>
<div class="notebox">
	
	<div style="width: 100%; margin: 0 auto; padding: 20px 0 20px;">
        <ul class="tabs" data-persist="true">
			<?php
				$tabList = getCakeClassTab();
				foreach ($tabList as $tab)
				{
			?>
					 <li><a href="#<?php echo $tab["id"]; ?>"><?php echo $tab["name"]; ?></a></li>
			<?php
				}
			?>
        </ul>
        <div class="tabcontents">
			<?php
				$tabList = getCakeClassTab();
				foreach ($tabList as $tab)
				{
			?>
					  <div id="<?php echo $tab["id"]; ?>">
					  <table width="100%">

							<?php
								$i = 0;
								$groupList = getCakeClassGroup($tab["id"]);
								foreach ($groupList as $gp)
								{
									if($gp['status'] != 'H') {
							?>
									<script type="text/javascript">
										loadGalleria('<?php echo $tab["id"]; ?>_<?php echo $gp['id'];?>','<?php echo $gp['albumId']; ?>');
									</script>

									<tr width="100%">
									<td align="center" width="50%">
										<div id="<?php echo $tab["id"]; ?>_<?php echo $gp['id'];?>_galleria"></div>
									</td>
									<td align="left" width="100%" style=" line-height: 34px;">
										<table>
											<tr>
												<td><br></td>
											</tr>
											<tr>
												<td><font size="7" face="chaoganghei"><?php echo $gp['name'];?></font>
													<br>
												</td>
											</tr>
											<tr>
												<td><font size="5" face="chaoganghei"><?php echo nl2br($gp['desc']);?></font></h2></td>
											</tr>
											<?php if ($gp['showPrice'] == 'A') {?>
											<tr>
												<td><font size="5"  face="chaoganghei">每位 $ <?php echo $gp['price'];?></font></td>
											</tr>	
											<?php } ?>
											<?php if ($gp['noc'] > 0) {?>
											<tr>
												<td>
													<div class="buttonsarea">
													<input style="font-family: chaoganghei;" id="<?php echo $tab["id"]; ?>_<?php echo $gp['id'];?>_Btn" type="button" value="立即報名!" onclick="openClassList('<?php echo $gp['id'];?>')"/>
													</div>
												</td>
											</tr>
											<?php } ?>
										</table>
									</td>
									</tr>


								

							<?php
									}
								}
							?>

					  </table>
					  </div>
			<?php
				}
			?>		
        </div>
    </div>
</div>
</body>