<?php
class DonHangModel
{
    public $conn;

    // Kết nối CSDL
    public function __construct()
    {
        $this->conn = connectDB();
    }
    // Lấy danh sách đơn hàng theo tài khoản
    public function getOrdersByUser($tai_khoan_id)
    {
        $sql = "SELECT * FROM don_hangs WHERE tai_khoan_id = :tai_khoan_id ORDER BY ngay_dat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['tai_khoan_id' => $tai_khoan_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTrangThaiDonHang($trang_thai_id)
    {
        $sql = "SELECT ten_trang_thai FROM trang_thai_don_hangs WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $trang_thai_id]);
        return $stmt->fetchColumn(); // Lấy giá trị cột đầu tiên
    }
    public function getPhuongThucThanhToan($phuong_thuc_id)
    {
        $sql = "SELECT ten_phuong_thuc FROM phuong_thuc_thanh_toans WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $phuong_thuc_id]);
        return $stmt->fetchColumn(); // Lấy giá trị cột đầu tiên
    }

    // Lấy chi tiết đơn hàng
    public function getDonHangByMaDonHang($ma_don_hang)
{
    $sql = "SELECT dh.*, ctdh.san_pham_id, ctdh.so_luong, ctdh.don_gia 
            FROM don_hangs dh
            JOIN chi_tiet_don_hangs ctdh ON dh.id = ctdh.don_hang_id
            WHERE dh.ma_don_hang = :ma_don_hang";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['ma_don_hang' => $ma_don_hang]);
    
    // Lấy tất cả chi tiết đơn hàng
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Nếu bạn muốn nhóm sản phẩm trong đơn hàng, có thể xử lý thêm ở đây
    $donHang = [];
    foreach ($result as $row) {
        $donHang[$row['san_pham_id']][] = $row; // Nhóm theo san_pham_id
    }
    
    return $donHang;
}
    

    // Hủy đơn hàng
    public function huyDonHang($ma_don_hang)
    {
        $sql = "UPDATE don_hangs SET trang_thai_id = 7 WHERE ma_don_hang = :ma_don_hang AND trang_thai_id = 1";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['ma_don_hang' => $ma_don_hang]);
    }
}
