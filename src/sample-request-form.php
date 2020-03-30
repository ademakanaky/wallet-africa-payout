<!DOCTYPE html>
<html lang="en">
<head>
  <title>Fund Request Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body>

<div class="container">
  <center><h3>Customer Fund Request</h3></center>
		<!-- Default form register -->
		<form class="text-center border border-light p-5" action="process-transfer.php" method="POST">

			<p class="h4 mb-4">Fill in the details correctly and click on 'Request Now'.</p>

			<div class="form-row mb-4">
				<div class="col">
					<!-- First name -->
					<input type="text" name="accname" placeholder="Enter Account Name" required id="" class="form-control" value="Test Account">
				</div>
				<div class="col">
					<!-- Last name -->
					<input type="text" name="accno" placeholder="Enter Account Number" required id="" class="form-control" value="3038739704">
				</div>
			</div>
			
			<div class="form-row mb-4">
				<div class="col">
					<!-- First name -->
					<input type="number" name="amount" placeholder="Enter Amount" required id="" class="form-control" value="40">
				</div>
				<div class="col">
					<!-- Last name -->
					<!--<input type="text" name="bankname" placeholder="Enter Bank Name" required class="form-control">-->
					<select name="bankname" class="form-control">
                                   <option>Values to be populated using the banks.json file</option>
                             </select>
				</div>
			</div>


			<!-- Sign up button -->
			<!--<button class="btn btn-info my-4 btn-block" type="submit">Request Now</button>-->
      <input type="submit" name="submit" value="Request Now" class="btn btn-info my-4 btn-block">
			<hr>

			<!-- Terms of service -->
			<p>By clicking
				<em>Sign up</em> you agree to our
				<a href="" target="_blank">terms of service</a>

		</form>
		<!-- Default form register -->
</div>

</body>
</html>


