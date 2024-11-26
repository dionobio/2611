
    <?php
    class DonHangController {
        public $model;

        public function __construct() {
            $this->model = new DonHangModel();
        }
    // Hiển thị danh sách đơn hàng
    public function LichSuMuaHang()
    {
        if (isset($_SESSION['nguoidungs_client'])) {
            $tai_khoan_id = $_SESSION['nguoidungs_client']['id'];
            $orders = $this->model->getOrdersByUser($tai_khoan_id);
    
            // Thêm tên trạng thái và phương thức thanh toán
            foreach ($orders as &$order) {
                $order['ten_trang_thai'] = $this->model->getTrangThaiDonHang($order['trang_thai_id']);
                $order['ten_phuong_thuc'] = $this->model->getPhuongThucThanhToan($order['phuong_thuc_thanh_toan_id']);
            }

            // Render view
            require_once './views/donhang/danhsachdonhang.php';
        } else {
            header("Location: index.php?act=login");
            exit();
        }
    }


    // Hiển thị chi tiết đơn hàng
    public function ChiTietMuaHang(){
        if (isset($_GET['ma_don_hang'])) {
            $ma_don_hang = $_GET['ma_don_hang'];
            // Thực hiện logic xử lý chi tiết đơn hàng với $ma_don_hang
            // Ví dụ: gọi model để lấy thông tin đơn hàng từ database
            $donHang = $this->model->getDonHangByMaDonHang($ma_don_hang);
            // Trả về dữ liệu cho view
        } else {
            // Nếu không có ma_don_hang thì có thể trả về lỗi hoặc thông báo
        }
    }
    
    // Hủy đơn hàng
    public function HuyDonHang(){
        if (isset($_GET['ma_don_hang'])) {
            $ma_don_hang = $_GET['ma_don_hang'];
    
            // Kiểm tra trạng thái của đơn hàng có phải là "Chờ Xác Nhận" (id = 1) hay không
            $order = $this->model->getDonHangByMaDonHang($ma_don_hang);
            
            if (empty($order)) {
                // Nếu không có đơn hàng với mã đó
                $_SESSION['error'] = 'Đơn hàng không tồn tại.';
                header('Location: index.php?act=lich-su-mua-hang');
                exit();
            }
    
            // Kiểm tra trạng thái đơn hàng có phải là "Chờ Xác Nhận" (id = 1)
            if ($order[0]['trang_thai_id'] != 1) {
                // Nếu không phải trạng thái "Chờ Xác Nhận", không thể hủy đơn
                $_SESSION['error'] = 'Không thể hủy đơn hàng này, chỉ đơn hàng có trạng thái "Chờ Xác Nhận" mới có thể hủy.';
                header('Location: index.php?act=lich-su-mua-hang');
                exit();
            }
    
            // Tiến hành hủy đơn hàng
            $this->model->huyDonHang($ma_don_hang);
    
            // Lưu thông báo thành công
            $_SESSION['success'] = 'Đơn hàng đã được hủy thành công.';
            
            // Chuyển hướng về trang lịch sử đơn hàng
            header('Location: index.php?act=lich-su-mua-hang');
            exit();
        } else {
            // Nếu không có ma_don_hang thì trả về lỗi hoặc thông báo
            $_SESSION['error'] = 'Mã đơn hàng không hợp lệ.';
            header('Location: index.php?act=lich-su-mua-hang');
            exit();
        }
    }
    
    
}
