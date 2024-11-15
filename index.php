<?php date_default_timezone_set("Etc/GMT+8"); ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Microfinance Management System</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-gray-900 text-white p-4">
        <div class="container mx-auto">
            <a class="text-2xl font-semibold" href="">Microfinance Management System</a>
        </div>
    </nav>

 <!-- Main Content -->
<div class="flex justify-center items-start pt-30"> <!-- pt-20 to add padding at the top -->
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-6 mt-10">
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
            <!-- Left Side Image -->
            <div class="hidden md:block relative">
                <!-- Background Image -->
                <img src="image/background.jpg" alt="Background Image" class="rounded-lg w-full h-full object-cover">

                <!-- User Login Text on Top of Image -->
                <h1 class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-2xl font-semibold text-black bg-white bg-opacity-90 px-6 py-3 rounded-md mb-4 flex items-center">
                    <!-- Material Icon -->
                    <span class="material-icons mr-2">account_circle</span>
                    USER LOGIN
                </h1>



            </div>


            <!-- Right Side Login Form -->
            <div class="flex flex-col justify-center items-center">
               
                <form method="POST" class="w-full" action="login.php">
                    <div class="mb-4">
                        <input type="text" class="w-full p-3 border border-gray-300 rounded-md" name="username" placeholder="Enter Username here..." required>
                    </div>
                    <div class="mb-4">
                        <input type="password" class="w-full p-3 border border-gray-300 rounded-md" name="password" placeholder="Enter Password here..." required>
                    </div>

                    <?php 
                    session_start();
                    if (isset($_SESSION['message'])) {
                        echo "<div class='text-red-600 text-center mb-4'>" . $_SESSION['message'] . "</div>";
                    }
                    ?>

                    <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-md hover:bg-blue-600 transition duration-300">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>



    <!-- Footer -->
    <nav class="bg-gray-900 text-white p-4 fixed bottom-0 w-full">
        <div class="container mx-auto text-center">
            <span>Microfinance Management System <?php echo date("Y"); ?></span>
        </div>
    </nav>



    
</body>

</html>
