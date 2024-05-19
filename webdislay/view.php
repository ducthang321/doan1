<!-- /index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Information</title>
    <link rel="stylesheet" href="http://localhost/webdislay/style.css">
    <script src="http://localhost/webdislay/js/script.js"></script>
</head>
<body>
    <header>
        <div class="container">
            <h1>All Information</h1>
        </div>
    </header>
    <div class="container">
        <?php
        // Kết nối tới cơ sở dữ liệu
        $servername = "localhost";
        $username = "root";
        $password = "ducthang";
        $dbname = "fridge_test";

        // Tạo kết nối
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Kiểm tra kết nối
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Truy vấn dữ liệu từ bảng all_information
        $sql = "SELECT * FROM all_information";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Time</th>
                        <th>Date</th>
                    </tr>";

            // Mảng lưu trữ các món ăn được đề xuất
            $suggested_foods = array();

            // Xuất dữ liệu từng hàng
            while($row = $result->fetch_assoc()) {
                if ($row["quantity"] > 0) { // Kiểm tra nếu quantity > 0
                    echo "<tr>
                            <td>" . $row["name"]. "</td>
                            <td>" . $row["quantity"]. "</td>
                            <td>" . $row["time"]. "</td>
                            <td>" . $row["date"]. "</td>
                        </tr>";

                    // Kiểm tra và đề xuất món ăn dựa trên loại hoa quả và số lượng
                    if ($row["fruits_id"] == 1 && $row["quantity"] >= 2) {
                        $suggested_foods[] = $row["name"];
                    }
                }
            }

            echo "</table>";

            // Hiển thị các món ăn được đề xuất (nếu có)
            if (!empty($suggested_foods)) {
                echo "<h2>Suggested Foods:</h2>";
                echo "<ul>";
                foreach ($suggested_foods as $food) {
                    echo "<li>$food</li>";
                }
                echo "</ul>";
            }
        } else {
            echo "0 results";
        }
        // Đóng kết nối
        $conn->close();
        ?>
    </div>
</body>
</html>
