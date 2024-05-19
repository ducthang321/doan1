<?php

// Nhận dữ liệu JSON từ POST request
$json_data = file_get_contents("php://input");

// Kiểm tra xem JSON có hợp lệ hay không
if ($json_data === false) {
    die("Không thể nhận dữ liệu JSON từ POST request.");
}

// Decode dữ liệu JSON nhận được
$data = json_decode($json_data, true);

// Kiểm tra xem dữ liệu JSON có hợp lệ hay không
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Dữ liệu JSON không hợp lệ: " . json_last_error_msg());
}

// Thông tin kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "ducthang";
$dbname = "fridge_test";

// Kết nối cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối cơ sở dữ liệu thất bại: " . $conn->connect_error);
}

// Lấy ngày và giờ hiện tại
date_default_timezone_set('Asia/Ho_Chi_Minh');
$d = date("Y-m-d");
$t = date("H:i:s");

// Kiểm tra xem dữ liệu có hợp lệ không
if (!empty($data)) {
    foreach ($data as $all_information) {
        if (!empty($all_information['fruits_id']) && !empty($all_information['name']) && isset($all_information['quantity'])) {
            // Gán giá trị cho biến
            $fruits_id = $all_information['fruits_id'];
            $name = $all_information['name'];
            $quantity = $all_information['quantity'];

            // Kiểm tra xem bản ghi có tồn tại không
            $check_query = "SELECT * FROM all_information WHERE fruits_id = ?";
            $stmt_check = $conn->prepare($check_query);
            $stmt_check->bind_param("i", $fruits_id);
            $stmt_check->execute();
            $result = $stmt_check->get_result();

            if ($result->num_rows > 0) {
                // Bản ghi tồn tại, cập nhật bản ghi
                $update_query = "UPDATE all_information SET name = ?, quantity = ?, Date = ?, Time = ? WHERE fruits_id = ?";
                $stmt_update = $conn->prepare($update_query);
                $stmt_update->bind_param("ssssi", $name, $quantity, $d, $t, $fruits_id);

                if ($stmt_update->execute()) {
                    echo "CẬP NHẬT THÀNH CÔNG";
                } else {
                    echo "Có lỗi xảy ra khi cập nhật dữ liệu: " . $stmt_update->error;
                }

                $stmt_update->close();
            } else {
                // Bản ghi không tồn tại, bạn có thể chọn bỏ qua hoặc thêm mới tùy theo yêu cầu
                echo "Bản ghi với fruits_id = $fruits_id không tồn tại.";
            }

            $stmt_check->close();
        } else {
            echo "Dữ liệu không hợp lệ!";
        }
    }
} else {
    echo "Dữ liệu không hợp lệ!";
}

$conn->close();
?>
