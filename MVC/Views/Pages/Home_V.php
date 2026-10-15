<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="dashboard-cards">
    <div class="card-box">
        <div class="card-info">
            <p>Tổng Doanh Thu</p>
            <h3 style="color: #38ef7d !important;"><?php echo number_format($data['doanhthu']); ?> đ</h3>
        </div>
        <div class="card-icon bg-1"><i class="fas fa-coins"></i></div>
    </div>
    <div class="card-box">
        <div class="card-info">
            <p>Tổng Đơn Hàng</p>
            <h3 style="color: #60a5fa !important;"><?php echo number_format($data['donhang']); ?></h3>
        </div>
        <div class="card-icon bg-2"><i class="fas fa-shopping-cart"></i></div>
    </div>
    <div class="card-box">
        <div class="card-info">
            <p>Sản Phẩm Đang Bán</p>
            <h3 style="color: #fbbf24 !important;"><?php echo number_format($data['sanpham']); ?></h3>
        </div>
        <div class="card-icon bg-3"><i class="fas fa-boxes-stacked"></i></div>
    </div>
    <div class="card-box">
        <div class="card-info">
            <p>Tổng Khách Hàng</p>
            <h3 style="color: #c084fc !important;"><?php echo number_format($data['khachhang']); ?></h3>
        </div>
        <div class="card-icon bg-4"><i class="fas fa-users"></i></div>
    </div>
</div>

<div class="revenue-periods">
    <?php 
    $periods = [
        ['label' => 'Doanh thu hôm nay', 'key' => 'Today', 'icon' => 'fa-calendar-day'],
        ['label' => 'Doanh thu tháng này', 'key' => 'ThisMonth', 'icon' => 'fa-calendar-alt'],
        ['label' => 'Doanh thu năm nay', 'key' => 'ThisYear', 'icon' => 'fa-calendar-check']
    ];
    foreach ($periods as $period) { 
        $amount = (float)($data['revenuePeriods'][$period['key']] ?? 0);
    ?>
        <div class="card-box">
            <div class="card-info">
                <p><i class="fas <?php echo $period['icon']; ?>" style="color: #dfb936; margin-right: 6px;"></i> <?php echo $period['label']; ?></p>
                <h3><?php echo number_format($amount); ?> đ</h3>
            </div>
        </div>
    <?php } ?>
</div>

<div class="status-strip">
    <?php 
    $orderStatusLabels = [
        1 => 'Chờ xác nhận',
        2 => 'Chờ lấy hàng',
        3 => 'Đang giao',
        4 => 'Hoàn thành',
        5 => 'Đã hủy'
    ]; 
    foreach ($orderStatusLabels as $status => $label) { 
        $count = (int)($data['orderStatusCounts'][$status] ?? 0);
    ?>
        <span class="status-pill status-<?php echo $status; ?>">
            <?php echo $label; ?> <span class="pill-num"><?php echo $count; ?></span>
        </span>
    <?php } ?>
</div>

<div class="row-dashboard">
    <div class="card-chart">
        <h4 class="card-title"><i class="fas fa-chart-line"></i> Biểu đồ doanh thu 7 ngày qua</h4>
        <div style="position: relative; height: 320px; width: 100%;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="card-list">
        <h4 class="card-title"><i class="fas fa-receipt"></i> Đơn hàng mới nhất</h4>
        <table class="table-sm">
            <thead>
                <tr>
                    <th>Mã ĐH</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th style="text-align: center;">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($data['newOrders']) && mysqli_num_rows($data['newOrders']) > 0) {
                    while($row = mysqli_fetch_array($data['newOrders'])){
                        $status = (int)$row['TrangThai'];
                        $statusNames = [
                            1 => 'Chờ xác nhận',
                            2 => 'Chờ lấy hàng',
                            3 => 'Đang giao',
                            4 => 'Hoàn thành',
                            5 => 'Đã hủy'
                        ];
                ?>
                <tr>
                    <td class="td-order-id">#<?php echo $row['MaHD']; ?></td>
                    <td><?php echo htmlspecialchars($row['TenKH'] ?? 'Khách lẻ', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="td-price"><?php echo number_format($row['TongTien']); ?> đ</td>
                    <td style="text-align: center;">
                        <span class="order-badge badge-<?php echo $status; ?>">
                            <?php echo htmlspecialchars($statusNames[$status] ?? 'Chờ xử lý', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </td>
                </tr>
                <?php 
                    }
                } else { ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: #94a3b8; padding: 25px;">Chưa có đơn hàng nào.</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div style="text-align: center; margin-top: 18px;">
            <a href="/Baitaplon/Donhang" class="view-all-orders-btn">
                <span>Xem tất cả đơn hàng</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<script>
    (function(){
        var labels = <?php echo $data['chartLabels'] ?? '[]'; ?>;
        var values = <?php echo $data['chartValues'] ?? '[]'; ?>;

        var canvas = document.getElementById('revenueChart');
        if (!canvas) return;
        var ctx = canvas.getContext('2d');

        var gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(245, 213, 101, 0.9)');
        gradient.addColorStop(1, 'rgba(217, 148, 26, 0.25)');

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: values,
                    backgroundColor: gradient,
                    borderColor: '#f5d565',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    maxBarThickness: 45
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: { 
                            color: '#cbd5e1', 
                            font: { size: 12, weight: '600' } 
                        },
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            color: '#cbd5e1',
                            font: { size: 11, weight: '500' },
                            callback: function(val) {
                                if (val >= 1000000) return (val / 1000000) + ' Tr';
                                if (val >= 1000) return (val / 1000) + ' K';
                                return val;
                            }
                        },
                        grid: { 
                            color: 'rgba(255, 255, 255, 0.06)' 
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#101418',
                        titleColor: '#cbd5e1',
                        bodyColor: '#f5d565',
                        borderColor: '#28323c',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Doanh thu: ' + Number(context.raw || 0).toLocaleString('vi-VN') + ' đ';
                            }
                        }
                    }
                }
            }
        });
    })();
</script>
