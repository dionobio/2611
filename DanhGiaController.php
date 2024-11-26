<?php
class DanhGiaController {
    public $modelDanhGia;

    public function __construct() {
        $this->modelDanhGia = new DanhGia();
    }

    public function listDanhGia() {
        $listDanhGia = $this->modelDanhGia->getAll();
        
        require_once 'views/sanpham/list_danhgia.php'; 
    }
    
    public function deleteDanhGia($id)
    {
        if ($id && is_numeric($id)) {  // Kiểm tra ID hợp lệ
            $this->modelDanhGia->delete($id);
            header("Location: index.php?act=danh-gia");
        } else {
            echo "ID không hợp lệ!";
        }
    
    }
}       
?>
