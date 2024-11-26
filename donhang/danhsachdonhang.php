<?php require_once 'views/layout/header.php'; ?>
<?php require_once 'views/layout/menu.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky">
                <form class="p-3" id="searchOrderForm" aria-label="Tìm kiếm đơn hàng">
                    <h5 class="text-primary">Tìm Kiếm Đơn Hàng</h5>
                    <div class="mb-3">
                        <label for="searchOrderCode" class="form-label">Tìm Theo Mã Đơn Hàng</label>
                        <input type="text" class="form-control" id="searchOrderCode" placeholder="Nhập mã đơn hàng"
                            oninput="searchOrderTable()">
                    </div>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-3">
            <section class="order-list-section py-5">
                <div class="container">
                    <h1 class="text-center mb-5 section-title">🛒 Danh Sách Đơn Hàng 📦</h1>
                    <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error']; ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['success']; ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>


                    <!-- Bảng Danh Sách Đơn Hàng -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Mã Đơn Hàng</th>
                                <th>Tên Người Nhận</th>
                                <th>Tổng Tiền</th>
                                <th>Phương Thức Thanh Toán</th>
                                <th>Trạng Thái</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($orders as $order): ?>
    <tr>
        <td><?= htmlspecialchars($order['ma_don_hang']); ?></td>
        <td><?= htmlspecialchars($order['ten_nguoi_nhan']); ?></td>
        <td><?= number_format($order['tong_tien'], 0, ',', '.'); ?> VNĐ</td>
        <td><?= $order['phuong_thuc_thanh_toan_id'] == 1 ? 'COD' : 'Online'; ?></td>
        <?php
        $statusMap = [
            1 => ['label' => 'Chờ Xác Nhận', 'class' => 'bg-secondary'],
            2 => ['label' => 'Đã Xác Nhận', 'class' => 'bg-primary'],
            3 => ['label' => 'Đang Giao', 'class' => 'bg-warning text-dark'],
            4 => ['label' => 'Đã Giao', 'class' => 'bg-info'],
            5 => ['label' => 'Thành Công', 'class' => 'bg-success'],
            6 => ['label' => 'Hoàn Hàng', 'class' => 'bg-dark'],
            7 => ['label' => 'Hủy Đơn', 'class' => 'bg-danger'],
        ];
        ?>
        <td>
            <span class="badge <?= $statusMap[$order['trang_thai_id']]['class']; ?>">
                <?= $statusMap[$order['trang_thai_id']]['label']; ?>
            </span>
        </td>
        <td>
            <button class="bg-primary" onclick="viewOrderDetails(<?= $order['id']; ?>)">
                <i class="bi bi-eye"></i> Xem Chi Tiết
            </button>
            <?php if ($order['trang_thai_id'] != 7): ?>
                <a href="index.php?act=huy-don-hang&ma_don_hang=<?= $order['ma_don_hang'] ?>"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Bạn có chắc chắn muốn hủy?')">Hủy</a>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>

                        </tbody>
                    </table>

                    <div id="noResult" class="text-center text-muted" style="display: none;">
                        Không có đơn hàng nào phù hợp.
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>

<script>
function searchOrderTable() {
    let inputOrderCode = document.getElementById('searchOrderCode').value.trim().toLowerCase();
    let rows = document.querySelectorAll('table tbody tr');
    let hasResult = false;

    rows.forEach(row => {
        let code = row.querySelector('td:first-child').innerText.toLowerCase();

        if (code.includes(inputOrderCode) || inputOrderCode === '') {
            row.style.display = '';
            hasResult = true;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('noResult').style.display = hasResult ? 'none' : 'block';
}

function viewOrderDetails(orderId) {
    // Hiển thị chi tiết đơn hàng bằng AJAX hoặc chuyển hướng
    window.location.href = `order_details.php?id=${orderId}`;
}


</script>