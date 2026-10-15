<style>
    /* Thanh lọc ngày */
    .filter-bar-report { 
        background: #171d23 !important; 
        padding: 16px 20px !important; 
        border-radius: 16px !important; 
        margin-bottom: 22px !important; 
        border: 1px solid #28323c !important; 
        display: flex; 
        flex-wrap: wrap;
        align-items: center; 
        justify-content: space-between;
        gap: 15px; 
        box-shadow: 0 4px 20px rgba(0,0,0,0.25) !important; 
    }
    .filter-title {
        font-weight: 700;
        font-size: 14px;
        color: #f5d565 !important;
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: .3px;
    }
    .input-date-custom {
        background: #101418 !important;
        color: #f1f5f9 !important;
        border: 1px solid #2d3844 !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        outline: none;
        color-scheme: dark;
    }
    .input-date-custom:focus {
        border-color: #f5d565 !important;
        box-shadow: 0 0 0 2px rgba(245, 213, 101, 0.2) !important;
    }
    .btn-view-report { 
        background: linear-gradient(135deg, #f5d565, #d4a017) !important; 
        color: #111 !important; 
        border: none !important; 
        padding: 9px 24px !important; 
        border-radius: 8px !important; 
        cursor: pointer; 
        font-weight: 800 !important;
        font-size: 13px !important;
        transition: all 0.2s ease !important;
    }
    .btn-view-report:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
    }
    .btn-export-excel { 
        background: linear-gradient(135deg, #10b981, #059669) !important; 
        color: white !important; 
        border: none !important; 
        padding: 9px 18px !important; 
        border-radius: 8px !important; 
        cursor: pointer; 
        text-decoration: none; 
        display: inline-flex; 
        align-items: center; 
        gap: 8px; 
        font-weight: 700 !important;
        font-size: 13px !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25) !important;
        transition: all 0.2s ease !important;
    }
    .btn-export-excel:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35) !important;
    }

    /* 3 thẻ tóm tắt nhanh cho Báo cáo */
    .report-kpi-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 22px;
    }
    .report-kpi-card {
        background: #171d23;
        border: 1px solid #28323c;
        border-radius: 16px;
        padding: 18px 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.22);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    .report-kpi-card:after {
        content: "";
        position: absolute;
        height: 3px;
        left: 20px;
        right: 20px;
        bottom: 8px;
        border-radius: 3px;
    }
    .report-kpi-card.kpi-1:after { background: linear-gradient(90deg, #f5d565, #d4a017); }
    .report-kpi-card.kpi-2:after { background: linear-gradient(90deg, #60a5fa, #2563eb); }
    .report-kpi-card.kpi-3:after { background: linear-gradient(90deg, #38ef7d, #059669); }

    .report-kpi-info p {
        color: #94a3b8;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin: 0;
    }
    .report-kpi-info h3 {
        font-size: 20px;
        font-weight: 800;
        margin: 6px 0 0;
        letter-spacing: -0.3px;
    }
    .report-kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Card báo cáo chính */
    .card-report-main { 
        background: #171d23 !important; 
        border-radius: 16px !important; 
        box-shadow: 0 8px 25px rgba(0,0,0,0.25) !important; 
        padding: 22px !important; 
        border: 1px solid #28323c !important; 
    }
    .card-report-header {
        border-bottom: 1px solid #28323c;
        padding-bottom: 16px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .card-report-title { 
        font-weight: 800; 
        font-size: 16px; 
        color: #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }
    .badge-report-period {
        background: rgba(245, 213, 101, 0.12);
        color: #f5d565;
        border: 1px solid rgba(245, 213, 101, 0.3);
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Bảng dữ liệu */
    .table-report-custom { 
        width: 100%; 
        border-collapse: collapse; 
        font-size: 13px; 
    }
    .table-report-custom th { 
        background: #12171c !important; 
        padding: 12px 14px !important; 
        text-align: left; 
        color: #94a3b8 !important; 
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: .5px !important;
        border-bottom: 1px solid #28323c !important;
    }
    .table-report-custom td { 
        padding: 14px !important; 
        border-bottom: 1px solid #20272f !important; 
        color: #e2e8f0 !important; 
        vertical-align: middle !important;
    }
    .table-report-custom tbody tr:hover td { 
        background: rgba(255, 255, 255, 0.03) !important; 
    }

    /* Rank badges */
    .rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 12px;
        margin-right: 10px;
    }
    .rank-1 { background: rgba(255, 215, 0, 0.2); color: #ffd700; border: 1px solid rgba(255, 215, 0, 0.5); }
    .rank-2 { background: rgba(203, 213, 225, 0.2); color: #cbd5e1; border: 1px solid rgba(203, 213, 225, 0.5); }
    .rank-3 { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.5); }
    .rank-other { background: rgba(255, 255, 255, 0.06); color: #94a3b8; border: 1px solid rgba(255, 255, 255, 0.1); }

    .qty-sold-badge {
        display: inline-block;
        background: rgba(59, 130, 246, 0.18);
        color: #60a5fa;
        border: 1px solid rgba(59, 130, 246, 0.35);
        border-radius: 999px;
        padding: 3px 12px;
        font-weight: 700;
        font-size: 12px;
    }
    .revenue-amount {
        color: #38ef7d !important;
        font-weight: 800 !important;
        font-size: 15px !important;
    }

    @media (max-width: 900px) {
        .report-kpi-grid { grid-template-columns: 1fr; }
        .filter-bar-report { flex-direction: column; align-items: stretch; }
    }
</style>

<!-- THANH LỌC THỜI GIAN -->
<div class="filter-bar-report">
    <div class="filter-title">
        <i class="fas fa-filter" style="color: #f5d565;"></i>
        <span>LỌC THỜI GIAN BÁO CÁO</span>
    </div>
    
    <form action="/Baitaplon/Baocao/Get_data" method="POST" style="display:flex; gap:10px; align-items:center; flex-wrap: wrap;">
        <input type="date" name="txtTuNgay" value="<?php echo htmlspecialchars($data['tungay'], ENT_QUOTES, 'UTF-8'); ?>" class="input-date-custom">
        <span style="color: #94a3b8; font-weight: 600;">đến</span>
        <input type="date" name="txtDenNgay" value="<?php echo htmlspecialchars($data['denngay'], ENT_QUOTES, 'UTF-8'); ?>" class="input-date-custom">
        <button type="submit" class="btn-view-report">
            <i class="fas fa-chart-pie" style="margin-right: 5px;"></i> Xem báo cáo
        </button>
    </form>

    <form action="/Baitaplon/Baocao/XuatExcel" method="POST">
        <input type="hidden" name="txtTuNgayEx" value="<?php echo htmlspecialchars($data['tungay'], ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="txtDenNgayEx" value="<?php echo htmlspecialchars($data['denngay'], ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit" class="btn-export-excel">
            <i class="fas fa-file-excel"></i> Xuất Excel Bán Chạy
        </button>
    </form>
</div>

<?php
// Tính toán dữ liệu tổng hợp cho Top sản phẩm
$rowsData = [];
$totalQty = 0;
$totalRev = 0;
$top1Name = '--';

if(isset($data['top_sp']) && mysqli_num_rows($data['top_sp']) > 0) {
    while($item = mysqli_fetch_array($data['top_sp'])){
        $rowsData[] = $item;
        $totalQty += (int)$item['SoLuongBan'];
        $totalRev += (float)$item['DoanhSo'];
    }
    if(!empty($rowsData)) {
        $top1Name = $rowsData[0]['TenSP'];
    }
}
?>

<!-- 3 THẺ KPI TỔNG QUAN -->
<div class="report-kpi-grid">
    <div class="report-kpi-card kpi-1">
        <div class="report-kpi-info">
            <p>Sản phẩm bán chạy nhất</p>
            <h3 style="color: #f5d565; font-size: 16px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;" title="<?php echo htmlspecialchars($top1Name, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($top1Name, ENT_QUOTES, 'UTF-8'); ?>
            </h3>
        </div>
        <div class="report-kpi-icon" style="background: rgba(245, 213, 101, 0.18); color: #f5d565;">
            <i class="fas fa-crown"></i>
        </div>
    </div>

    <div class="report-kpi-card kpi-2">
        <div class="report-kpi-info">
            <p>Tổng số lượng bán ra (Top 10)</p>
            <h3 style="color: #60a5fa;"><?php echo number_format($totalQty); ?> món</h3>
        </div>
        <div class="report-kpi-icon" style="background: rgba(59, 130, 246, 0.18); color: #60a5fa;">
            <i class="fas fa-bag-shopping"></i>
        </div>
    </div>

    <div class="report-kpi-card kpi-3">
        <div class="report-kpi-info">
            <p>Tổng doanh thu (Top 10)</p>
            <h3 style="color: #38ef7d;"><?php echo number_format($totalRev); ?> đ</h3>
        </div>
        <div class="report-kpi-icon" style="background: rgba(56, 239, 125, 0.18); color: #38ef7d;">
            <i class="fas fa-coins"></i>
        </div>
    </div>
</div>

<!-- BẢNG BÁO CÁO TOP SẢN PHẨM BÁN CHẠY -->
<div class="card-report-main">
    <div class="card-report-header">
        <h4 class="card-report-title">
            <i class="fas fa-ranking-star" style="color: #f5d565; font-size: 18px;"></i>
            Top 10 Sản Phẩm Bán Chạy Nhất
        </h4>
        <div class="badge-report-period">
            <i class="fas fa-calendar-alt" style="margin-right: 5px;"></i>
            Từ <?php echo date('d/m/Y', strtotime($data['tungay'])); ?> đến <?php echo date('d/m/Y', strtotime($data['denngay'])); ?>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="table-report-custom">
            <thead>
                <tr>
                    <th style="width: 80px; text-align: center;">Hạng</th>
                    <th>Tên Sản Phẩm</th>
                    <th style="text-align: center; width: 140px;">Đã Bán</th>
                    <th style="text-align: right; width: 180px;">Doanh Số</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(!empty($rowsData)) {
                    $stt = 0;
                    foreach($rowsData as $top){ 
                        $stt++;
                        $rankClass = ($stt == 1) ? 'rank-1' : (($stt == 2) ? 'rank-2' : (($stt == 3) ? 'rank-3' : 'rank-other'));
                        $trophyIcon = ($stt == 1) ? '<i class="fas fa-trophy" style="color: #ffd700; margin-right: 6px;"></i>' : 
                                     (($stt == 2) ? '<i class="fas fa-medal" style="color: #cbd5e1; margin-right: 6px;"></i>' : 
                                     (($stt == 3) ? '<i class="fas fa-award" style="color: #f59e0b; margin-right: 6px;"></i>' : ''));
                ?>
                <tr>
                    <td style="text-align: center;">
                        <span class="rank-badge <?php echo $rankClass; ?>">
                            #<?php echo $stt; ?>
                        </span>
                    </td>
                    <td style="font-weight: 600; color: #f1f5f9;">
                        <?php echo $trophyIcon; ?>
                        <?php echo htmlspecialchars($top['TenSP'], ENT_QUOTES, 'UTF-8'); ?>
                    </td>
                    <td style="text-align: center;">
                        <span class="qty-sold-badge">
                            <?php echo number_format($top['SoLuongBan']); ?> món
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <span class="revenue-amount">
                            <?php echo number_format($top['DoanhSo']); ?> đ
                        </span>
                    </td>
                </tr>
                <?php 
                    } 
                } else { ?>
                <tr>
                    <td colspan="4" style="text-align:center; padding: 40px; color: #94a3b8;">
                        <i class="fas fa-folder-open" style="font-size: 32px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                        Chưa có dữ liệu bán hàng trong khoảng thời gian đã chọn.
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
