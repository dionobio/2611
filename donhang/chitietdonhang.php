<h2>Chi Tiết Đơn Hàng</h2>
<p><strong>Tên Người Nhận:</strong> <?= htmlspecialchars($orderDetails[0]['ten_nguoi_nhan']) ?></p>
<p><strong>Địa Chỉ:</strong> <?= htmlspecialchars($orderDetails[0]['dia_chi']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($orderDetails[0]['email_nguoi_nhan']) ?></p>
<p><strong>Số Điện Thoại:</strong> <?= htmlspecialchars($orderDetails[0]['sdt_nguoi_nhan']) ?></p>

<h3>Danh Sách Sản Phẩm</h3>
<table class="table">
    <thead>
        <tr>
            <th>Sản Phẩm</th>
            <th>Số Lượng</th>
            <th>Giá</th>
            <th>Tổng</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orderDetails as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['san_pham_id']) ?></td>
            <td><?= htmlspecialchars($item['so_luong']) ?></td>
            <td><?= number_format($item['don_gia']) ?> VND</td>
            <td><?= number_format($item['so_luong'] * $item['don_gia']) ?> VND</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
