<?php
include_once('db_connect.php');
session_start();

if (!isset($_SESSION['user'])) {
    ?>
    <script>window.location.href = "index.php";</script>
    <?php
    exit;
}

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $user_id = $_SESSION['user'];

    // Check if the user has purchased the book
    $purchase_query = "SELECT * FROM `purchases` WHERE `user_id` = '$user_id' AND `book_id` = '$book_id'";
    $purchase_result = $con->query($purchase_query);

    if ($purchase_result->num_rows > 0) {
        // Fetch the book file path
        $book_query = "SELECT `b_name`, `b_file` FROM `books` WHERE `b_id` = '$book_id'";
        $book_result = $con->query($book_query);

        if ($book_result->num_rows > 0) {
            $book = $book_result->fetch_assoc();
            $file_path = 'files/book_file/' . $book['b_file'];

            if (file_exists($file_path)) {
                ?>
                <script>
                    function downloadFile(filePath, fileName) {
                        fetch(filePath)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('File not found or cannot be downloaded.');
                                }
                                return response.blob(); // Convert the response to a Blob
                            })
                            .then(blob => {
                                const url = window.URL.createObjectURL(blob); // Create a URL for the Blob
                                const a = document.createElement('a'); // Create an anchor element
                                a.style.display = 'none';
                                a.href = url;
                                a.download = fileName; // Set the file name for the download
                                document.body.appendChild(a);
                                a.click(); // Trigger the download
                                window.URL.revokeObjectURL(url); // Revoke the Blob URL after download
                                document.body.removeChild(a); // Remove the anchor element
                            })
                            .catch(error => {
                                console.error('Error downloading file:', error);
                                alert('Failed to download the file.');
                            })
                            .finally(() => {
                                if (document.referrer) {
                                    window.location.href = document.referrer;
                                } else {
                                    window.location.href = "index.php"; // Fallback if no referrer is available
                                }
                            });
                    }

                    const filePath = "<?php echo $file_path; ?>"; // Path to the file on the server
                    const fileName = "<?php echo $book['b_name']; ?>.pdf"; // Desired file name for the download
                    downloadFile(filePath, fileName);
                </script>
                <?php
                exit;
            } else {
                setcookie('error', 'File not found.', time() + 3, '/');
            }
        } else {
            setcookie('error', 'Book not found.', time() + 3, '/');
        }
    } else {
        setcookie('error', 'You have not purchased this book.', time() + 3, '/');
    }
} else {
    setcookie('error', 'Invalid request.', time() + 3, '/');
    ?>
    <script>window.location.href = "index.php";</script>
    <?php
    exit;
}
?>
<script>
    if (document.referrer) {
        window.location.href = document.referrer;
    } else {
        window.location.href = "index.php";
    }
</script>