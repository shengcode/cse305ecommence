<?php
	include_once 'header.php'; 
	if (isset($_SESSION['isCustomerLogin'])){
		$duration=$_SESSION['isCustomerLogin']['duration'];
		$start =$_SESSION['isCustomerLogin']['start'];
		$CustomerId=$_SESSION['isCustomerLogin']['Id'];
		if ((time()-$start)>$duration){
			echo "<p> YOU OUT </p>";
			unset($_SESSION['isCustomerLogin']['duration']);
			unset($_SESSION['isCustomerLogin']['start']);
			unset($_SESSION['isCustomerLogin']['logintype']);
			unset($_SESSION['isCustomerLogin']['Id']);
			unset($_SESSION['isCustomerLogin']);
			session_destroy();
			
		}		
	}	
	else{
		header("Location: login.php?statust=error&msg=No session found. Please login!");
	}
	
	if (isset($_POST['update_CustomerInfor'])){
		$sql_update="UPDATE customer SET Password = "."'".$_POST['Password'] ."',"." FirstName= "."'". $_POST['FirstName'] ."',"." LastName = "."'". $_POST['LastName']. "',"." Address = "."'".$_POST['Address']."',"." Email = '".$_POST['Email']."', "."PhoneNumber =' ".$_POST['PhoneNumber']."' WHERE CustomerId = ".$CustomerId.";";
		$dbServername="localhost";
		$dbUsername="root";
		$dbPassword="12345";
		$dbName="ecommence";
		$conn=mysqli_connect($dbServername,$dbUsername,$dbPassword,$dbName);
		mysqli_query($conn,$sql_update);	 
	}
	if (isset($_POST['view_customer_orders'])){
		$dbServername="localhost";
		$dbUsername="root";
		$dbPassword="12345";
		$dbName="ecommence";
		$conn=mysqli_connect($dbServername,$dbUsername,$dbPassword,$dbName);
		$sql_see_orders="SELECT * FROM customerorders WHERE CustomerId = ". $CustomerId. ";";
		$result=mysqli_query($conn,$sql_see_orders);
		if(mysqli_num_rows($result)!=0){
			$OrderIdArray=array();
			$row=mysqli_fetch_array($result);
			do{	
				$single_order=array(
					$row['OrderId'],
					$row['TotalCharge'],
				);
				array_push($OrderIdArray,$single_order);
			}while($row=mysqli_fetch_array($result));
			echo "THe count of orders is :  ";
			echo count($OrderIdArray);
			for($i=0;$i<count($OrderIdArray);$i++){
				$OrderId=$OrderIdArray[$i][0]; // for each order there is an array of items
				$OrderCharge=$OrderIdArray[$i][1];
				echo "There order ID you have is : ";
				echo $OrderId;
				echo "<br>";
				echo "The total charge for this order is: ";
				echo $OrderCharge;
				$order_search_query="SELECT * FROM orderitems WHERE OrderId = ".$OrderId.";";
				$order_item_result=mysqli_query($conn,$order_search_query);
				if (mysqli_num_rows($order_item_result)!=0){ // there is a bun of orderitem 
					$all_item_infor=array();
					$item_row=mysqli_fetch_array($order_item_result);
					do {
						$single_item=array(
						$item_row['OrderId'],
						$item_row['SellerId'],
						$item_row['ItemId'],
						$item_row['ItemName'],
						$item_row['ItemPrice'],
						$item_row['Quantity']						
						);
						array_push($all_item_infor,$single_item);
					}while($item_row=mysqli_fetch_array($order_item_result));
				}
				
				echo "<table border=1><tr><th>SellerId</th><th>ItemId</th><th>ItemName</th><th>ItemPrice</th><th>Quantity</th></tr>";	
				for($j=0;$j<count($all_item_infor);$j++){					
					echo "<form >";
					echo "<tr>";
					echo "<td>" .$all_item_infor[$j][1]. " </td>"; 
					echo "<td>" .$all_item_infor[$j][2]. " </td>"; 
					echo "<td>" .$all_item_infor[$j][3]. " </td>";
					echo "<td>" .$all_item_infor[$j][4]. " </td>";
					echo "<td>" .$all_item_infor[$j][5]. " </td>";
					echo "</tr>";
					echo "</form>"; 
					}
				}
		}
		
		else if (mysqli_num_rows($result)==0){
			echo "There is no order history in your account!";
		}
	}	
	
?>

<section class="main-container">	
	<div class ="search_class">Search the products you want to buy 
	<form name ='form1' method ='POST'	action='searchresults.php'>
	<input name = 'search' type='text' size='40' maxlength='50'/>
	<input type = 'submit' name ='submit' value='search' />
	</form>	
	</div>
	<style>
	.search_class{
		background-color: tomato;
		height:100px;
		color: white;
		margin:20px;
		padding: 20px;
		font-size:30px;			
		text-align:center;
		vertical-align:middle;
	} 
	</style>
	<div class="customerAccount">Now you are logged in as customer, here's what you can do:
	<section class="main-container"> 
	<div class="main-wrapper">
		<form class="sellerfunction"  method="POST" >
			<input type='submit' value='Update customer information' name='submit_updateCustomer' >
			<input type='submit' value='See shopping cart' name='view_shoppingcart' >
			<input type='submit' value='View customer orders' name='view_customer_orders' >
			<input type='submit' value='Logout' name='submit_logout' >
		</form>
	</div>
</section>
	</div>	
</section>







<?php
	if(isset($_POST['submit_updateCustomer'])){
		//echo "Customer information";
		echo "<table border=1> <tr><th> Password </th> <th> FirstName </th> <th> LastName </th> <th> Address </th><th> Email </th><th> PhoneNumber </th></tr>";	
		$dbServername="localhost";
		$dbUsername="root";
		$dbPassword="12345";
		$dbName="ecommence";		
		$conn=mysqli_connect($dbServername,$dbUsername,$dbPassword,$dbName);
		$sql= "SELECT * FROM customer WHERE CustomerId = ".$CustomerId. ";";
		$result=mysqli_query($conn,$sql);
		if(mysqli_num_rows($result)!=0){
		$row=mysqli_fetch_array($result);	
		echo "<form action='customerLoggedin.php' method='POST'>";
		echo "<tr>";
		echo "<td>" ."<input type=text name= Password value= '". $row['Password']. "' </td>";
		echo "<td>" ."<input type=text name= FirstName value= '". $row['FirstName']. "' </td>"; 
		echo "<td>" ."<input type=text name= LastName value= '". $row['LastName']. "' </td>"; 
		echo "<td>" ."<input type=text name= Address value= '". $row['Address']. "' </td>";
		echo "<td>" ."<input type=text name= Email value= '". $row['Email']. "' </td>";
		echo "<td>" ."<input type=text name= PhoneNumber value= '". $row['PhoneNumber']. "' </td>";
		echo "<td>" ."<input type=hidden name= hidden_CustomerId value= '". $row['CustomerId']. "' </td>";
		echo "<td>" ."<input type=submit name= update_CustomerInfor value= Update ". " </td>";
		echo "</tr>";
		echo "</form>"; 
	}
		
	} else if(isset($_POST['view_shoppingcart'])){
		header( "Location: addshoppingcart.php" );

	}
	else if(isset($_POST['submit_logout'])){
		echo "You are logged out! Now redirecting you to the homepage!";
		unset($_SESSION['isCustomerLogin']['duration']);
		unset($_SESSION['isCustomerLogin']['start']);
		unset($_SESSION['isCustomerLogin']['logintype']);
		unset($_SESSION['isCustomerLogin']['Id']);
		unset($_SESSION['isCustomerLogin']);
		session_destroy();
		header( "refresh:3;url=homepage.php" );
	}


	
	include_once 'footer.php';
?>