<!DOCTYPE html>
<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$conn = mysqli_connect("Localhost", "root", "", "book_store");
	if (!$conn) {
		echo mysqli_connect_error();
		exit;
	}

	$email = mysqli_real_escape_string($conn, $_POST["email"]);
	$password = sha1($_POST['password']);

	// Retrieve the inserted user
	$query = "SELECT * FROM `users` WHERE email='$email' and password='$password'";
	echo $query;
	$result = mysqli_query($conn, $query);
	if ($result && $row = mysqli_fetch_assoc($result)) {
		$user_id = $row['id'];
		$ip_address = mysqli_real_escape_string($conn, $_SERVER['REMOTE_ADDR']);
		$log_query = "INSERT INTO `logs` (`user_id`, `ip_address`, `log_type`, `created_at`)
                      VALUES ('$user_id', '$ip_address', 'LOGIN', CURRENT_TIMESTAMP)";
		if (!mysqli_query($conn, $log_query)) {
			echo "Error logging login: " . mysqli_error($conn);
		}
		// Store user data in session
		$_SESSION['id'] = $row['id'];
		$_SESSION['email'] = $row['email'];
		$_SESSION['name'] = $row['name'];
		$_SESSION['role'] = $row['role'];
		// Redirect to home page
		if ($row["role"] == "C") {
			header("Location: shop.php");
			exit;
		} elseif ($row["role"] == "A") {
			header("Location: Manager_DashBoard.php");
			exit;
		}
	} else {
		$error = "Invalid Email Or Password";
	}
    mysqli_close($conn);
}
?>


<html>

<head>
	<title>Login Page</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="Style.css">
	<meta name="description" content="This websit for sell books">
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
	<div class="background"></div>

	<div class="UnderWrapper">
		<br><br><br><br><br><br><br><br><br>
		<h1 id="t1">Free </h1>
		<h1 id="t2">Palastine </h1>
	</div>
	<div class="wrapper">
		<form method="post">
			<br><br><br><br>

			<a href="login.php" id="login"><b>Login</b></a>

			<b style="   font-size: 30px;
	color: black;
	text-decoration: none;
	margin-inline-start: 17.5px;
	font-size: 30px;">|</b>

			<a href="signup.php" id="signup"><b>Sign up</b></a>


			<div class="input_box">
				<br><br>
				<i class='bx bxs-user'></i>
				<input class="input_box2" type="email" name="email" placeholder="Email" style="font-size: 18px;"
					value="<?php echo !empty($_POST["email"]) ? $_POST["email"] : ""; ?>" required>
				<?php
				if (!empty($error)) {
					echo "<p style='color:orangered'>$error Used</p> ";
				}

				?>
			</div>

			<div class="input_box">
				<i class='bx bxs-lock-alt'></i>
				<input class="input_box2" type="password" name="password" placeholder="Password "
					style="font-size: 18px;" required>
				<br><br>
			</div>

			<div class="Register" style="margin-inline-start: 50px;">
				<!-- <a href="RegestraionPahe.html">Are You New?</a> -->
				</label>
			</div>
			<br><br>
			<button class="button">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
					stroke="currentColor" class="w-6 h-6">
					<path stroke-linecap="round" stroke-linejoin="round"
						d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"></path>
				</svg>
				<div class="text">
					Login
				</div>

			</button>
		</form>

	</div>
</body>

</html>

<style>
	body {
		height: 100%;
		width: 100%;
		margin: 0;
		padding: 0;
	}

	.background {
		height: 741px;
		width: 100%;
		background-image: url("../media/icons/wallpaperflare.com_wallpaper (6).jpg");
		background-repeat: no-repeat;
		background-size: cover;
		background-position: top;
		background-clip: border-box;
		opacity: .75;
		margin-bottom: -100px;
	}

	.UnderWrapper {

		width: 800px;
		height: 500px;
		background-image: url("../media/icons/wallpaperflare.com_wallpaper (6).jpg");
		background-size: 100%;
		background-position-y: -70px;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		border-radius: 12px;
		align-items: center;
		margin-top: 5%;

	}

	.wrapper {
		width: 390px;
		height: 500px;
		position: absolute;
		top: 50%;
		left: 33.1%;
		transform: translate(-47.25%, -50%);
		backdrop-filter: blur(2px) saturate(500%);
		-webkit-backdrop-filter: blur(16px) saturate(1%);
		background-color: rgba(255, 255, 255, 0.60);
		border-top-left-radius: 12px;
		border-bottom-left-radius: 12px;
		border: 1px solid rgba(209, 213, 219, 0.3);
		margin-top: 5%;
	}


	.input_box {
		font-size: 15px;
		margin-top: 10px;
		width: 220px;
		margin-inline-start: 82px;

	}

	.input_box2 {
		font-family: inherit;
		width: 100%;
		border: 0;
		border-bottom: 2px solid black;
		outline: 0;
		font-size: 1.3rem;
		color: black;
		padding: 7px 0;
		background: transparent;
		transition: border-color 0.2s;

		&::placeholder {
			color: rgba(0, 0, 0, 0.75)
		}
	}



	.button {
		margin-top: 20px;
		margin-inline-start: 30%;
		background-color: white;
		color: black;
		width: 150px;
		height: 2.4em;
		border: black 0.2em solid;
		border-radius: 11px;
		transition: all 0.6s ease;
	}

	.button:hover {
		background-color: black;
		color: white;
		cursor: pointer;
	}

	.button svg {
		width: 1.6em;
		margin-left: 20px;
		margin-top: 2px;
		position: absolute;
		display: flex;
		transition: all 0.6s ease;
	}

	.button:hover svg {
		transform: translateX(5px);
	}

	.text {
		margin: 0 3.2em;
		font: 1.3em sans-serif;
	}

	#t1 {
		margin-inline-start: 55%;
		margin-top: -30px;

		color: antiquewhite;
		color: rgba(255, 255, 255, 0.75);
		font-size: 400%;
	}

	#t2 {
		margin-inline-start: 60%;
		margin-top: -60px;
		color: antiquewhite;
		color: rgba(255, 255, 255, 0.75);
		font-size: 400%;
	}

	#signup {
		text-decoration: none;
		color: black;
		margin-inline-start: 17.5px;
		font-size: 30px;
	}

	#login {
		color: black;
		text-decoration: none;
		margin-inline-start: 80px;
		font-size: 30px;

	}

	#login:hover {
		font-size: 35px;
	}

	#signup:hover {
		font-size: 35px;

	}
</style>
