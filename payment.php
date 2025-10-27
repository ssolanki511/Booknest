<?php
ob_start();
include_once('db_connect.php');

session_start();

if (!isset($_SESSION['user'])) {
    echo "<script> window.location.href = 'login.php';</script>";
    exit();
}
// Fetch the user's email from the database
$user_email = '';
$user_query = "SELECT email FROM users WHERE user_id = ?";
$user_stmt = $con->prepare($user_query);
$user_stmt->bind_param("s", $_SESSION['user']);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
    $user_email = $user_data['email'];
}

if (isset($_GET['book_id'])) {
    $book_ids = explode(',', $_GET['book_id']);
    $placeholders = implode(',', array_fill(0, count($book_ids), '?'));
    $types = str_repeat('i', count($book_ids));

    $book_sql = "SELECT * FROM books WHERE b_id IN ($placeholders)";
    $stmt = $con->prepare($book_sql);
    $stmt->bind_param($types, ...array_map('intval', $book_ids));
    $stmt->execute();
    $book_result = $stmt->get_result();

    if ($book_result->num_rows === 0) {
        echo "<script>window.location.href = 'index.php';</script>";
        exit();
    }

    $books = [];
    $total_price = 0;
    $total_discount = 0;

    while ($book = $book_result->fetch_assoc()) {
        $b_price = floatval($book['b_price']);
        $b_discount = floatval($book['b_discount']); // percentage
        $discount_amount = ($b_price * $b_discount) / 100;
        $discounted_price = $b_price - $discount_amount;

        $book['discount_amount'] = $discount_amount;
        $book['discounted_price'] = $discounted_price;

        $total_price += $b_price;
        $total_discount += $discount_amount;

        $books[] = $book;
    }



    $gst = ($total_price - $total_discount) * 0.10;
    $coupon_discount = 0;
    $final_price = ($total_price - $total_discount) + $gst;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['coupon_code'])) {
        $coupon_code = mysqli_real_escape_string($con, $_POST['coupon_code']);
        $today = date('Y-m-d');
    
        // Check if the coupon exists and is valid
        $coupon_sql = "SELECT * FROM coupons WHERE coupon_code = ? AND start_date <= ? AND end_date >= ?";
        $coupon_stmt = $con->prepare($coupon_sql);
        $coupon_stmt->bind_param("sss", $coupon_code, $today, $today);
        $coupon_stmt->execute();
        $coupon_result = $coupon_stmt->get_result();
    
        if ($coupon_result->num_rows > 0) {
            $coupon = $coupon_result->fetch_assoc();
    
            // Check if the coupon has already been used by the user
            $coupon_id = $coupon['coupon_id'];
            $user_id = $_SESSION['user'];
    
            $used_coupon_sql = "SELECT * FROM coupon_used WHERE coupon_id = ? AND user_id = ?";
            $used_coupon_stmt = $con->prepare($used_coupon_sql);
            $used_coupon_stmt->bind_param("ii", $coupon_id, $user_id);
            $used_coupon_stmt->execute();
            $used_coupon_result = $used_coupon_stmt->get_result();
    
            if ($used_coupon_result->num_rows > 0) {
                $coupon_message = "You have already used this coupon!";
            } else {
                // Apply the coupon
                if ($coupon['coupon_type'] === 'percentage') {
                    $coupon_discount = $final_price * ($coupon['coupon_value'] / 100);
                } else {
                    $coupon_discount = $coupon['coupon_value'];
                }
                $final_price -= $coupon_discount;
                if ($final_price < 0) $final_price = 0;
                $coupon_message = "Coupon applied successfully!";
            }
        } elseif ($coupon_code === "") {
            $coupon_message = "Please enter a coupon code!";
        } else {
            $coupon_message = "Invalid or expired coupon!";
        }
    }
} else {
    echo "<script>window.location.href = 'index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment - Booknest</title>
    <link rel="icon" href="files/Logo/logo.svg?v=<?php echo time(); ?>" type="image/icon type">
    <link rel="stylesheet" href="files/tailwindcss/output.css?v=<?php echo time() ?>">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4 py-8">
    <?php include_once('cookie_display.php'); ?>
    <div class="bg-white shadow-xl rounded-2xl p-8 max-w-xl w-full border border-gray-200">
        <h1 class="text-3xl font-extrabold text-indigo-700 text-center mb-8">Complete Your Purchase</h1>

        <!-- Display All Books -->
        <div class="<?php echo count($books) > 1 ? 'max-h-60 overflow-y-scroll' : ''; ?>  mb-6">
            <?php foreach ($books as $book): ?>
                <div class="flex flex-col sm:flex-row items-center mb-4 p-4 bg-gray-50 rounded-lg shadow-md border border-gray-200">
                    <img src="files/book_cover/<?php echo $book['b_cover_tmp']; ?>" alt="Book Cover"
                        class="w-20 h-28 object-cover rounded-lg shadow border border-gray-200">
                    <div class="sm:ml-4 mt-2 sm:mt-0 text-center sm:text-left">
                        <h2 class="text-base font-semibold text-gray-800"><?php echo $book['b_name'] ?></h2>
                        <p class="text-sm text-gray-500 mb-1">by <?php echo $book['b_author'] ?></p>
                        <p class="text-sm text-gray-800">
                            <?php if ($book['b_discount'] > 0): ?>
                                <span class="line-through text-gray-500">₹<?php echo number_format($book['b_price'], 2); ?></span>
                                <span class="ml-2 text-green-600 font-semibold">-<?php echo $book['b_discount']; ?>%</span><br>
                                <span class="text-indigo-700 font-bold">₹<?php echo number_format($book['discounted_price'], 2); ?></span>
                            <?php else: ?>
                                <span class=" text-indigo-700 font-bold">₹<?php echo number_format($book['b_price'], 2); ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Your Email </label>
            <input type="email" id="email" name="email" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                placeholder="Enter your email" value="<?php echo htmlspecialchars($user_email); ?>">
        </div>

        <!-- Coupon Form -->
        <form method="POST" class="mb-6">
            <label for="coupon_code" class="block text-sm font-medium text-gray-700 mb-1">Apply Coupon</label>
            <div class="flex gap-2">
                <input type="text" id="coupon_code" name="coupon_code"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    placeholder="Enter coupon code"
                    value="<?php echo isset($_POST['coupon_code']) ? htmlspecialchars($_POST['coupon_code']) : ''; ?>">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2 rounded-lg transition">
                    Apply
                </button>
            </div>
            <?php if (isset($coupon_message)) { ?>
                <p class="mt-2 text-sm font-medium <?php echo strpos($coupon_message, 'successfully') !== false ? 'text-green-600' : 'text-red-600'; ?>">
                    <?php echo $coupon_message; ?>
                </p>
            <?php } ?>
        </form>

        <!-- Price Summary -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Price Details</h2>
            <div class="bg-gray-50 p-4 rounded-xl shadow-inner text-sm border border-gray-200 space-y-2">
                <div class="flex justify-between">
                    <span>Total Original Price :</span>
                    <span class="text-gray-800 font-medium">₹<?php echo number_format($total_price, 2); ?></span>
                </div>
                <?php if ($total_discount > 0): ?>
                    <div class="flex justify-between text-green-600">
                        <span>Total Discount :</span>
                        <span>-₹<?php echo number_format($total_discount, 2); ?></span>
                    </div>
                <?php endif; ?>
                <div class="flex justify-between text-yellow-600">
                    <span>GST (10%) :</span>
                    <span>+₹<?php echo number_format($gst, 2); ?></span>
                </div>
                <?php if ($coupon_discount > 0): ?>
                    <div class="flex justify-between text-green-600">
                        <span>Coupon Applied
                            <?php
                            if ($coupon['coupon_type'] === 'percentage') {
                                echo "(Flat " . $coupon['coupon_value'] . "% Off ) ";
                            } else {
                                echo "(Flat ₹" . number_format($coupon['coupon_value'], 2) . ") ";
                            } ?>:</span>
                        <span>-₹<?php echo number_format($coupon_discount, 2); ?></span>
                    </div>
                <?php endif; ?>
                <hr class="border-t my-2">
                <div class="flex justify-between font-bold text-lg text-indigo-800">
                    <span>Total Payable:</span>
                    <span>₹<?php echo number_format($final_price, 2); ?></span>
                </div>
            </div>
        </div>

        <!-- Razorpay Button -->
        <div class="text-center mt-4">
            <input type="submit" id="rzp-button" value="Pay Now"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-8 py-3 rounded-full shadow-lg transform transition-transform duration-300 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-indigo-300 w-full cursor-pointer">
        </div>
    </div>

    <script>
        const options = {
            "key": "rzp_test_5xZnUmdBhRiDnz", // Replace with your Razorpay API key
            "amount": "<?php echo round($final_price * 100); ?>", // In paise
            "currency": "INR",
            "name": "Booknest",
            "description": "Payment for books",
            "image": "files/Logo/logo.svg",
            "handler": function(response) {
                // Call a PHP script to update the coupon_used table
                fetch("update_coupon_used.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        payment_id: response.razorpay_payment_id,
                        book_id: "<?php echo implode(',', $book_ids); ?>",
                        coupon_id: "<?php echo isset($coupon['id']) ? $coupon['id'] : ''; ?>",
                        user_id: "<?php echo $_SESSION['user']; ?>"
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "payment_success.php?payment_id=" + response.razorpay_payment_id + "&book_id=<?php echo implode(',', $book_ids); ?>";
                    } else {
                        alert("Failed to update coupon usage. Please contact support.");
                    }
                })
                .catch(error => {
                    console.error("Error updating coupon usage:", error);
                    alert("An error occurred. Please contact support.");
                });
            },
            "prefill": {
                "email": document.getElementById('email').value
            },
            "theme": {
                "color": "#6366F1"
            }
        };

        const rzp = new Razorpay(options);

        document.getElementById('rzp-button').onclick = function(e) {
            e.preventDefault();
            // Email validation
            const emailInput = document.getElementById('email');
            const email = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!email) {
                alert("Please enter your email address.");
                emailInput.focus();
                return;
            }

            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address.");
                emailInput.focus();
                return;
            }

            rzp.open();
        };
    </script>
</body>

</html>