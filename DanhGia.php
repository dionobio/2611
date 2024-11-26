<?php 
class DanhGia {
    private $pdo;

    public function __construct()
    {
        $this->pdo = connectDB(); // Giả sử hàm connectDB() đã được định nghĩa bên ngoài
    }

    public function getAll() {
        try {
            $sql = "
                SELECT 
                    danh_gias.*, 
                    san_phams.ten_san_pham,
                    nguoi_dungs.ten_nguoi_dung AS nguoi_danh_gia
                FROM 
                    danh_gias
                JOIN    
                    san_phams ON danh_gias.san_pham_id = san_phams.id
                JOIN 
                    nguoi_dungs ON danh_gias.tai_khoan_id = nguoi_dungs.id
            ";
    
            // Chuẩn bị và thực thi câu lệnh SQL
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        
            // Lấy kết quả
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
    
        } catch (PDOException $e) {
            echo 'Lỗi: ' . $e->getMessage();
        }
    }
    
    
    
    public function getDanhGiasBySanPhamId($san_pham_id) {
        $query = "
    SELECT 
        danh_gias.id AS danh_gia_id,
        danh_gias.noi_dung,
        danh_gias.diem_so,
        danh_gias.ngay_danh_gia,
        danh_gias.trang_thai,
        san_phams.ten_san_pham,
        nguoi_dungs.ten_nguoi_dung AS nguoi_danh_gia
    FROM 
        danh_gias
    JOIN 
        san_phams ON danh_gias.san_pham_id = san_phams.id
    JOIN 
        nguoi_dungs ON danh_gias.tai_khoan_id = nguoi_dungs.id
    WHERE 
        danh_gias.san_pham_id = :san_pham_id
    ORDER BY 
        danh_gias.id DESC;
";
    
$stmt = $this->pdo->prepare($query);
$stmt->bindParam(':san_pham_id', $san_pham_id, PDO::PARAM_INT); // Đảm bảo kiểu dữ liệu là INT
$stmt->execute();

return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    

    // Cập nhật trạng thái đánh giá (Hiện/Ẩn)
    public function toggleStatus($id) {
        try {
            // Lấy trạng thái hiện tại của đánh giá
            $sql = "SELECT trang_thai FROM danh_gias WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $newStatus = $result['trang_thai'] == 1 ? 0 : 1; // Đổi trạng thái từ 1 sang 0 hoặc ngược lại

                // Cập nhật trạng thái đánh giá
                $updateSql = "UPDATE danh_gias SET trang_thai = :newStatus WHERE id = :id";
                $updateStmt = $this->pdo->prepare($updateSql);
                $updateStmt->execute([':newStatus' => $newStatus, ':id' => $id]);

                return true; // Cập nhật thành công
            }
            return false; // Không tìm thấy đánh giá
        } catch (PDOException $e) {
            echo 'Lỗi: ' . $e->getMessage();
            return false;
        }
    }

    public function delete($id) {
        try {
            // Xóa đánh giá dựa trên id
            $sql = "DELETE FROM danh_gias WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Kiểm tra xem xóa thành công hay không
            if ($stmt->rowCount() > 0) {
                return true; // Xóa thành công
            } else {
                return false; // Không tìm thấy đánh giá để xóa
            }
        } catch (PDOException $e) {
            echo 'Lỗi: ' . $e->getMessage();
            return false;
        }
    }
}
?>
