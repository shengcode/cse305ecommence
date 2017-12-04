<?php
	include_once 'header.php'; 
	session_start();
	if (isset($_SESSION['isCustomerLogin'])){
		$duration=$_SESSION['isCustomerLogin']['duration'];
		$start =$_SESSION['isCustomerLogin']['start'];
		$customerId=$_SESSION['isCustomerLogin']['Id'];
		if ((time()-$start)>$duration){
			echo "<p> time is OUT, you are automatically LOG OUT </p>";
			unset($_SESSION['isCustomerLogin']['duration']);
			unset($_SESSION['isCustomerLogin']['start']);
			unset($_SESSION['isCustomerLogin']['logintype']);
			unset($_SESSION['isCustomerLogin']['Id']);
			unset($_SESSION['isCustomerLogin']);	
			unset($_SESSION['productsAdded']);
			session_destroy();
			echo "</br>";
			echo "Now I am redirecting you to the Login page";		
			header( "refresh:5;url=login.php?statust=error&msg=No session found. Please login!" );	
			exit();
		}
		else {
			/*echo "you are all set!!!";
			echo "</br>";
			echo "and you are NOT time out!";*/
		}
	}	
	else{
		echo "You are not log in as Customer, you can't add to shopping cart!";
		echo "</br>";
		echo "Now I am redirecting you to the Login page";		
		header( "refresh:5;url=login.php?statust=error&msg=No session found. Please login!" );
		exit();
	}	
	$myData=array(array());
	if (isset($_SESSION['searchResults'])){
		$myData=$_SESSION['searchResults'];
		for ($i=0; $i<count($myData);$i++){
			//echo "the stuff that's on the search results are:";
			//echo implode(" ", $myData[$i]);
			//echo "</br>";
		}	
	}	
	//echo "  STUFF THAT'S SELECTED   ";
	$myselection=$_SESSION['productsAdded'];
	if (isset($_POST['checkbox'])){
		$checkbox=$_POST['checkbox'];
		for($i=0;$i<count($checkbox);$i++){
			$check1=$checkbox[$i];
			//echo implode("   ",$myData[$check1]);
			array_push($myselection, $myData[$check1]);
			echo "</br>";
		}	
		$_SESSION['productsAdded']=$myselection;	
		for($i=0;$i<count($_SESSION['productsAdded']);$i++){
			echo implode("   ",$_SESSION['productsAdded'][$i]);
			echo "</br>";
		}	
	}
	else {
		echo "Please select an item to shoppingcart!";
		header( "refresh:2;url=searchresults.php" );
		exit();
	}
	
	
?>
<div class ="checkout">
	<form method='POST' action= 'checkoutOrKeepShopping.php'>;
	<input type='submit' name= 'keepsearch' value='KEEPshoping' >
	<input type='submit' name= 'checkout' value='CHECKOUT' >
	</form>
</div>

<?php	
	include_once 'footer.php';
?>


