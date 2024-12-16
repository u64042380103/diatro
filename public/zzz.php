<?php 
$conn = mysqli_connect("182.52.87.60:3333", "root", "123456", "jhcisdb");

// Check the connection
if ($conn) {
    echo "OK";
    // You can add more code here if the connection is successful
} else {
    echo "none";
}

// Close the connection when you're done
mysqli_close($conn);

?>