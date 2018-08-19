<?php 
require_once 'common/common.php';
if (isset($_GET['eventId']) || isset($_POST['eventId'])) {
	if (isset($_GET['eventId']) )
		$eventId = $_GET['eventId'];
	else 
		$eventId = $_POST['eventId'];
	$classDetail = getClassDetail($eventId);
	$summayPage = false;
	if(count($classDetail) > 0 ){
		$title = $classDetail['title'];
		$quota = $classDetail['quota'];
		$quotaleft = $classDetail['quotaLeft'];
		$date = $classDetail['date'];
		$time = $classDetail['time'];
		$location = $classDetail['location'];
		$price = $classDetail['price'];

		$errors = array('name' => '','tel'=> '','email' => '','captcha' => '');
		$summayPage = false;
		

		
		if(isset($_POST['action']) && $_POST['action'] == 'applyClass' ){
		
			$name = $_POST['name'];
			$tel =  $_POST['tel'];
			if(isset($_POST['whatsapp']))
				$whatsapp = $_POST['whatsapp'];
			else
				$whatsapp = "N";
			$email = $_POST['email'];
			$nop = $_POST['nop'];
			$remarks = $_POST['remarks'];
			
			$captcha = $_POST['captcha'];
			$isErrors = false;
			
			if (strlen($name) == 0) {
				$errors['name'] = '姓名是必需的';
				$isErrors = true;
			}			
			

			if (strlen($tel) == 0) {
				$errors['tel'] = '電話號碼是必需的';
				$isErrors = true;
			} else if (!preg_match("/^[0-9]{8}$/", $tel)) {
				$errors['tel'] = '電話號碼錯誤';
				$isErrors = true;
			}

			if (strlen($email) == 0) {
				$errors['email'] = '電郵是必需的';
				$isErrors = true;
			} else if ( !preg_match('/^(?:[\w\d]+\.?)+@(?:(?:[\w\d]\-?)+\.)+\w{2,4}$/i', $email)) {
				$errors['email'] = '電郵地址錯誤';
				$isErrors = true;
			}
			
			

			require_once 'captcha/securimage.php';
			$securimage = new Securimage();

			if ($securimage->check($captcha) == false) {
				$errors['captcha'] = '驗證碼錯誤';
				$isErrors = true;
			}
			
			if(!$isErrors){
				$summayPage = true;
				$totalPrice = $nop * $price;
			}
		}
		else if(isset($_POST['action']) && $_POST['action'] == 'back' ){
			$name = $_POST['name'];
			$tel =  $_POST['tel'];
			if(isset($_POST['whatsapp']))
				$whatsapp = $_POST['whatsapp'];
			else
				$whatsapp = "N";
			$email = $_POST['email'];
			$nop = $_POST['nop'];
			$remarks = $_POST['remarks'];
		}
		else{			
			$name = "";
			$tel =  "";
			$whatsapp = "";
			$email = "";
			$nop = "";
			$remarks = "";
		
		}		
		
	}
	else{
		redirectMainPage();
	}

	

}
else{
	redirectMainPage();
}
?>
<html>
<head>
	<link rel="stylesheet" href="css/addForm.css" media="screen, projection" />
	<link rel="stylesheet" href="css/common.css" media="screen, projection" />
	<script type="text/javascript" src="js/common.js"></script>
	<link rel="shortcut icon" href="images/hm_pink03.png"/>
	<title>Happy Moment</title>
	<script type="text/javascript">
		function back(){
			document.getElementById("applyClass").action = "applyClass.php";
			document.getElementById("action").value = "back";
			formSubmit('applyClass');
		}
	</script>
</head>
<body>
<div class="formarea">
	
	<h2><?php echo $title ?></h2>
	<h2>課堂時間 : <?php echo $date." ".$time ?></h2>
	<h2>費用(每人) : $<?php echo $price ?></h2>
	<h2>課堂名額 : <?php echo $quota?></h2>
	<h2>尚餘名額 : <?php echo $quotaleft?></h2>
<?php if(!$summayPage) {?>
	<form action='applyClass.php' method='POST' id="applyClass">
	<input type="hidden" name="eventId" id="eventId" value="<?php echo $eventId ?>">
	<input type="hidden" name="action" id="action" value="applyClass">
	<div class="subfieldsset">
	<table width="100%">
		<tr>
			<td><label for="name">姓名:</label></td>
			<td>
				<input type="text" name="name" id="name" value="<?php echo $name ?>">
				<p class="errorMsg"><?php echo $errors['name'] ?></p>
			</td>
		</tr>
		<tr>
			<td><label for="tel">電話號碼</label></td>
			<td>
				<input type="text" name="tel" id="tel" value="<?php echo $tel ?>">
				<p class="errorMsg"><?php echo $errors['tel'] ?></p>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" name="whatsapp" id="whatsapp" value="Y" <?php if($whatsapp =='Y') echo 'checked' ?>>有使用WhatsApp</td>
		</tr>
		<tr>
			<td><label for="email">電郵</label></td>
			<td>
				<input type="text" name="email" id="email" value="<?php echo $email ?>">
				<p class="errorMsg"><?php echo $errors['email'] ?></p>
				<p class="errorMsg">請儘量使用hotmail以外的電郵地址</p>				
			</td>
		</tr>
		<tr>
			<td><label for="nop">人數</label></td>
			<td>
				<select id="nop" name="nop">
				<?php
					for ($i=1; $i<=$quotaleft; $i++)
					{
						if($nop == $i)
							echo "<option value=\"".$i."\" selected>".$i."</option>";
						 else
							echo "<option value=\"".$i."\" >".$i."</option>";
					}
				?>
				<?php
					/*
					<option value="1" <?php if($nop =='1') echo 'selected' ?>>1</option>
					<option value="2" <?php if($nop =='2') echo 'selected' ?>>2</option>
					<option value="3" <?php if($nop =='3') echo 'selected' ?>>3</option>
					<option value="4" <?php if($nop =='4') echo 'selected' ?>>4</option>	
					*/
				?>
				</select>			
			</td>
		</tr>		
		<tr>
			<td><label for="remarks">備註</label></td>
			<td><textarea id="remarks" name="remarks" cols="40" rows="6"><?php echo $remarks ?></textarea></td>
		</tr>
		<tr>
			<td><label for="captcha">驗證碼</label></td>
			<td>
				<input type="text" name="captcha" id="captcha" maxlength="16" />
				<a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = './captcha/securimage_show.php?sid=' + Math.random(); this.blur(); return false"><img src="./captcha/images/refresh.png" alt="Reload Image" height="32" width="32" onclick="this.blur()" align="bottom" border="0" /></a><br />
				<?php if ($errors['captcha'] != ''){ ?>
				<p class="errorMsg"><?php echo $errors['captcha'] ?></p>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<img id="siimage" style="border: 1px solid #000; margin-right: 15px" src="./captcha/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" align="left" />			
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<div class="buttonsarea">
				<input type="button" value="提交報名" onclick="formSubmit('applyClass')">
				</div>
			</td>
		</tr>		
	</table>
	</div>
	</form>
<?php }  else { ?>
	<form action='insertApplication.php' method='POST' id="applyClass">
	<input type="hidden" name="eventId" id="eventId" value="<?php echo $eventId ?>">
	<input type="hidden" name="action" id="action" value="applyClass">
	<div class="subfieldsset">
	<table class="summary" width="100%">
		<tr class="summary">
			<td><label for="name">姓名:</label></td>
			<td>
				<?php echo $name ?>
				<input type="hidden" name="name" id="name" value="<?php echo $name ?>">
			</td>
		</tr>
		<tr class="summary">
			<td><label for="tel">電話號碼</label></td>
			<td>
				<?php echo $tel ?>
				<input type="hidden" name="tel" id="tel" value="<?php echo $tel ?>">
			</td>
		</tr>
		<tr class="summary">
			<td></td>
			<td>
				<input type="hidden" name="whatsapp" id="whatsapp" value="<?php echo $whatsapp ?>">
				<input type="checkbox" value="Y" <?php if($whatsapp =='Y') echo 'checked' ?> disabled="true">有使用WhatsApp
			</td>
		</tr>
		<tr class="summary">
			<td><label for="email">電郵</label></td>
			<td>
				<?php echo $email ?>
				<input type="hidden" name="email" id="email" value="<?php echo $email ?>">
				<p class="errorMsg">請儘量使用hotmail以外的電郵地址</p>
			</td>
		</tr>
		<tr class="summary">
			<td><label for="nop">人數</label></td>
			<td>
				<?php echo $nop ?>
				<input type="hidden" name="nop" id="nop" value="<?php echo $nop ?>">					
			</td>
		</tr>
		<tr class="summary">
			<td><label for="nop">總費用</label></td>
			<td>
				<?php echo '$'.$totalPrice ?>				
			</td>
		</tr>		
		<tr class="summary">
			<td><label for="remarks">備註</label></td>
			<td>
				<?php echo nl2br($remarks) ?>
				<input type="hidden" name="remarks" id="remarks" value="<?php echo $remarks ?>">						
			</td>
		</tr>
		<tr class="summary">
			<td></td>
			<td>
				<div class="buttonsarea">
					<input type="button" value="確認資料" onclick="formSubmit('applyClass')">
				</div>
				<div class="buttonsarea">
					<input type="button" onClick="back();" value="返回" />
				</div>
			</td>
		</tr>		
	</table>
	</div>
	</form>
<?php }?>
</div>
</body>
</html>