<?php
 	session_start();
	require 'db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
		$ShopType = $_POST['type'];
		$ShopName = dataFilter($_POST['pname']);
		$ShopInfo = $_POST['pinfo'];
		$fid = $_SESSION['id'];

		$sql = "INSERT INTO Area (fid, shopname,shopinfoinfo)
			   VALUES ('$fid', '$ShopName', '$shopType', '$ShopInfo'";
		$result = mysqli_query($conn, $sql);
		if(!$result)
		{
			$_SESSION['message'] = "Unable to upload Shop !!!";
			header("Location: Login/error.php");
		}
		else {
			$_SESSION['message'] = "successfull !!!";
		}

		$pic = $_FILES['ShopPic'];
		$picName = $pic['name'];
		$picTmpName = $pic['tmp_name'];
		$picSize = $pic['size'];
		$picError = $pic['error'];
		$picType = $pic['type'];
		$picExt = explode('.', $picName);
		$picActualExt = strtolower(end($picExt));
		$allowed = array('jpg','jpeg','png');

		if(in_array($picActualExt, $allowed))
		{
			if($picError === 0)
			{
				$_SESSION['ShopPicId'] = $_SESSION['id'];
				$picNameNew = $ShopName.$_SESSION['ShopPicId'].".".$picActualExt ;
				$_SESSION['ShopPicName'] = $picNameNew;
				$_SESSION['shopPicExt'] = $picActualExt;
				$picDestination = "images/shopImages/".$picNameNew;
				move_uploaded_file($picTmpName, $picDestination);
				$id = $_SESSION['id'];

				$sql = "UPDATE fproduct SET picStatus=1, pimage='$picNameNew' WHERE product='$ShopName';";

				$result = mysqli_query($conn, $sql);
				if($result)
				{

					$_SESSION['message'] = "Shop Image Uploaded successfully !!!";
					header("Location: area.php");
				}
				else
				{
					//die("bad");
					$_SESSION['message'] = "There was an error in uploading your image! Please Try again!";
					header("Location: Login/error.php");
				}
			}
			else
			{
				$_SESSION['message'] = "There was an error in uploading your  image! Please Try again!";
				header("Location: Login/error.php");
			}
		}
		else
		{
			$_SESSION['message'] = "You cannot upload files with this extension!!!";
			header("Location: Login/error.php");
		}
	}

	function dataFilter($data)
	{
	    $data = trim($data);
	    $data = stripslashes($data);
	    $data = htmlspecialchars($data);
	    return $data;
	}
?>



	</head>
	<body>

		<?php require 'menu.php'; ?>

		<!-- One -->

			<section id="one" class="wrapper style1 align-center">
				<div class="container">
					<form method="POST" action="uploadShop.php" enctype="multipart/form-data">
						<h2>Enter the Information here..!!</h2>
						<br>
				<center>
					<input type="file" name="ShopPic"></input>
					<br />
				</center>
				<div class="row">
					  <div class="col-sm-6">
						  <div class="select-wrapper" style="width: auto" >
							  <select name="type" id="type" required style="background-color:white;color: black;">
								  <option value="" style="color: black;">- Category -</option>
								  <option value="Clothing" style="color: black;">Fruit</option>
								  <option value="Grocery" style="color: black;">Vegetable</option>
								  <option value="hardware" style="color: black;">Grains</option>
							  </select>
						</div>
					  </div>
					  <div class="col-sm-6">
						<input type="text" name="shopname" id="shopname" value="" placeholder="Name" style="background-color:white;color: black;" />
					  </div>
				</div>
				<br>
				<div>
					<textarea  name="pinfo" id="shopinfo" rows="12"></textarea>
				</div>
			<br>
			
			</form>
		</div>
	</section>

		<script>
			 CKEDITOR.replace( 'Shopinfo' );
		</script>
	</body>
</html>
