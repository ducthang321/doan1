import requests
from ultralytics import YOLO
from collections import Counter
import time

# Địa chỉ của tệp PHP trên máy chủ
php_file_url = "http://127.0.0.1/getdatatest.php"

while True:
    model = YOLO("best.pt")

    results = model.predict(source="D:\doan1\gogouya-fruits.jpg", show=True)
    object_classes = results[0].boxes.cls.to('cpu').tolist()
    class_names = results[0].names
    
    class_counts = Counter(object_classes)
    
    # Biến đếm id, bắt đầu từ 1
    fruit_data = []
    for class_id_str, count in class_counts.items():
        class_id = int(class_id_str)
        fruit_name = class_names[class_id]
        fruit_data.append({"fruits_id": class_id + 1, "name": fruit_name, "quantity": count})

    # Kiểm tra và thêm dữ liệu cho những loại trái cây không xuất hiện trong ảnh
    for class_id, fruit_name in class_names.items():
        if fruit_name not in [f["name"] for f in fruit_data]:
            fruit_data.append({"fruits_id": class_id + 1, "name": fruit_name, "quantity": 0})

    # Gửi dữ liệu qua POST request tới tệp PHP
    response = requests.post(php_file_url, json=fruit_data)
    #print(fruit_data)
    # In ra phản hồi từ máy chủ
    print(response.text)

    break
