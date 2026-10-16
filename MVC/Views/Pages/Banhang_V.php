<style>
    /* --- CSS CHO CỘT TRÁI (SẢN PHẨM) --- */
    .search-box { 
        display: flex; 
        width: 100%; 
        margin-bottom: 15px; 
    }
    
    .input-search { 
        flex: 1; 
        padding: 10px 15px;
        background: #11161b !important;
        color: #f1f5f9 !important;
        border: 1px solid #2d3844 !important;
        border-right: none !important;
        border-radius: 8px 0 0 8px !important;
        outline: none;
        font-size: 14px;
    }
    .input-search:focus { 
        border-color: #f5d565 !important;
        background: #161c22 !important; 
    }

    .btn-search { 
        background: linear-gradient(135deg, #f5d565, #d4a017) !important;
        color: #111 !important; 
        border: 1px solid #d4a017 !important;
        padding: 0 25px; 
        border-radius: 0 8px 8px 0 !important; 
        cursor: pointer; 
        font-weight: 800; 
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    .btn-search:hover { filter: brightness(1.1); transform: translateY(-1px); }
    
    .btn-clear {
        background: #dc2626 !important; 
        color: white !important; 
        border: none !important;
        padding: 0 15px; 
        border-radius: 6px !important; 
        margin-left: 6px;
        cursor: pointer; 
        display: flex; 
        align-items: center;
        text-decoration: none;
        font-weight: bold;
    }

    .product-list { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(135px, 1fr)); 
        gap: 14px; 
        max-height: 75vh; 
        overflow-y: auto;
        padding-right: 4px;
    }
    .product-list::-webkit-scrollbar { width: 6px; }
    .product-list::-webkit-scrollbar-thumb { background: #2f3a46; border-radius: 4px; }

    .product-item { 
        border: 1px solid #28323c !important; 
        border-radius: 12px !important; 
        padding: 12px !important; 
        text-align: center; 
        box-shadow: 0 4px 14px rgba(0,0,0,0.22) !important; 
        background: #171d23 !important;
        transition: all 0.2s ease; 
        display: flex; 
        flex-direction: column; 
        justify-content: space-between;
    }
    .product-item:hover { 
        transform: translateY(-3px); 
        box-shadow: 0 8px 20px rgba(0,0,0,0.35) !important; 
        border-color: #f5d565 !important; 
    }
    .prod-img { 
        width: 100%; 
        height: 100px; 
        object-fit: contain; 
        border-radius: 6px; 
        margin-bottom: 8px; 
        background: rgba(255,255,255,0.02);
    }
    .prod-name { 
        font-weight: 600 !important; 
        font-size: 13px !important; 
        color: #e2e8f0 !important;
        margin: 4px 0 6px !important; 
        line-height: 1.4; 
        height: 36px; 
        overflow: hidden; 
        display: -webkit-box; 
        -webkit-line-clamp: 2; 
        -webkit-box-orient: vertical; 
    }
    .prod-price { 
        color: #38ef7d !important; 
        font-weight: 800 !important; 
        font-size: 14px !important; 
        margin-bottom: 10px; 
    }

    /* --- CSS CHO CỘT PHẢI (THANH TOÁN) --- */
    .card-pos {
        background: #171d23 !important;
        border: 1px solid #28323c !important;
        border-radius: 16px !important;
        box-shadow: 0 8px 25px rgba(0,0,0,0.25) !important;
        overflow: hidden;
    }

    .payment-group { 
        display: flex; 
        gap: 8px; 
        margin-bottom: 14px; 
        flex-wrap: wrap; 
    }
    .payment-option { display: none; } 
    .payment-label {
        flex: 1; 
        min-width: 90px; 
        text-align: center; 
        padding: 12px 6px !important; 
        border: 1px solid #2d3844 !important; 
        border-radius: 10px !important; 
        cursor: pointer;
        font-weight: 600 !important; 
        color: #cbd5e1 !important; 
        background: #12171c !important; 
        transition: all 0.2s ease; 
        font-size: 12px !important;
        display: flex !important; 
        flex-direction: column !important; 
        align-items: center !important; 
        justify-content: center !important;
        gap: 6px !important;
    }
    .payment-label i { 
        font-size: 18px !important; 
        color: #94a3b8 !important; 
        transition: color 0.2s ease; 
    }
    .payment-label:hover {
        border-color: #f5d565 !important;
        color: #f5d565 !important;
        background: #181f26 !important;
    }
    .payment-label:hover i { color: #f5d565 !important; }
    
    .payment-option:checked + .payment-label {
        background: rgba(245, 213, 101, 0.12) !important; 
        color: #f5d565 !important; 
        border-color: #f5d565 !important; 
        box-shadow: 0 0 12px rgba(245, 213, 101, 0.25) !important;
        font-weight: 700 !important;
    }
    .payment-option:checked + .payment-label i {
        color: #f5d565 !important;
    }

    /* QR Code Card */
    .qr-card {
        display: none; 
        background: #12171c !important; 
        color: #fff !important; 
        padding: 15px !important; 
        border-radius: 10px !important;
        border: 1px solid #2d3844 !important;
        align-items: center; 
        gap: 15px; 
        margin-bottom: 14px; 
        box-shadow: 0 4px 14px rgba(0,0,0,0.3) !important;
    }
    .qr-img-box { 
        width: 90px; 
        height: 90px; 
        background: #fff; 
        padding: 5px; 
        border-radius: 8px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        cursor: zoom-in; 
    }
    .qr-image { width: 100%; height: 100%; object-fit: contain; }
    .qr-info { flex: 1; overflow: hidden; } 
    .qr-bank-name { 
        font-weight: 800; 
        font-size: 15px; 
        color: #f5d565 !important; 
        margin-bottom: 4px; 
        white-space: nowrap; 
        overflow: hidden; 
        text-overflow: ellipsis; 
    }
    .qr-note { font-size: 12px; color: #94a3b8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* Vùng tiền mặt */
    .money-area { 
        display: block; 
        background: #12171c !important; 
        padding: 12px !important; 
        border-radius: 10px !important; 
        border: 1px solid #28323c !important;
        margin-bottom: 14px;
    }
    .money-grid { 
        display: grid; 
        grid-template-columns: repeat(3, 1fr); 
        gap: 6px; 
        margin-top: 10px; 
    }
    .btn-money { 
        background: #20272f !important; 
        color: #e2e8f0 !important; 
        border: 1px solid #2f3a46 !important; 
        padding: 9px 0 !important; 
        border-radius: 6px !important; 
        cursor: pointer; 
        font-size: 13px !important; 
        font-weight: 700 !important;
        transition: all 0.2s ease !important; 
    }
    .btn-money:hover { 
        background: #2c3642 !important; 
        color: #f5d565 !important;
        border-color: #f5d565 !important;
    }
    
    .input-money { 
        font-size: 18px !important; 
        font-weight: 800 !important; 
        color: #38ef7d !important; 
        text-align: right !important; 
        background: #101418 !important;
        border: 1px solid #2d3844 !important;
        border-radius: 8px !important;
    }
    .return-money { 
        font-size: 18px !important; 
        font-weight: 800 !important; 
        color: #f5d565 !important; 
        text-align: right !important; 
        background: #101418 !important;
        border: 1px solid #2d3844 !important;
        border-radius: 8px !important;
    }
    
    .form-sm { font-size: 13px; padding: 8px; }
    label { 
        font-size: 12px; 
        font-weight: 700; 
        color: #94a3b8 !important; 
        margin-bottom: 4px; 
        display: block; 
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    /* Modal phóng to QR */
    .qr-modal {
        display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; 
        background-color: rgba(0,0,0,0.85); justify-content: center; align-items: center; animation: fadeIn 0.3s;
    }
    .qr-modal-content { max-width: 90%; max-height: 90%; border-radius: 12px; box-shadow: 0 0 25px rgba(245,213,101,0.3); }
    .close-qr { position: absolute; top: 20px; right: 35px; color: #f5d565; font-size: 40px; font-weight: bold; cursor: pointer; }
    @keyframes fadeIn { from {opacity: 0} to {opacity: 1} }
</style>

<div class="row" style="display:flex; gap:20px; height: calc(100vh - 80px);">
    
    <!-- CỘT TRÁI: DANH SÁCH SẢN PHẨM -->
    <div class="col-left" style="flex: 1.25; display: flex; flex-direction: column;">
        <div class="card-pos" style="height: 100%; display: flex; flex-direction: column;">
            <div style="background:#141a1f; color:#f5d565; padding: 14px 18px; border-bottom: 1px solid #28323c;">
                <h3 style="font-size: 16px; margin:0; font-weight:700; color:#f5d565; display:flex; align-items:center;">
                    <i class="fas fa-boxes-stacked" style="color:#f5d565; margin-right:8px; font-size:18px;"></i> Danh Sách Sản Phẩm
                </h3>
            </div>
            <div class="card-body" style="flex: 1; overflow: hidden; display: flex; flex-direction: column; padding: 16px;">
                
                <form action="/Baitaplon/Banhang/Get_data" method="POST" class="search-box">
                    <input type="text" name="txtTimKiem" class="input-search" 
                           placeholder="Nhập tên hoặc mã sản phẩm (F4)..." 
                           value="<?php echo isset($data['keyword']) ? htmlspecialchars($data['keyword'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                    <button type="submit" name="btnTimKiem" class="btn-search">
                        <i class="fas fa-search"></i> TÌM KIẾM
                    </button>
                    <?php if(isset($data['keyword']) && $data['keyword'] != '') { ?>
                        <a href="/Baitaplon/Banhang" class="btn-clear" title="Xóa tìm kiếm">✕</a>
                    <?php } ?>
                </form>

                <div class="product-list">
                    <?php
                    if(isset($data['sp'])){
                        while($row = mysqli_fetch_array($data['sp'])){
                    ?>
                        <div class="product-item">
                            <img src="/Baitaplon/Public/Images/<?php echo $row['HinhAnh'] ?>" class="prod-img" onerror="this.src='https://placehold.co/100x100/171d23/f5d565?text=No+Image'">
                            <div>
                                <div class="prod-name" title="<?php echo htmlspecialchars($row['TenSP'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['TenSP'], ENT_QUOTES, 'UTF-8'); ?></div>
                                <div class="prod-price"><?php echo number_format($row['GiaBan']); ?> đ</div>
                            </div>
                            <a href="/Baitaplon/Banhang/ThemGioHang/<?php echo $row['MaSP']; ?>"
                               style="display:block; font-size:12px; font-weight:700; padding:8px 0; text-align:center; text-decoration:none; background:linear-gradient(135deg, #f5d565, #d4a017); color:#111; border-radius:8px; transition:all 0.2s ease;">
                               + Thêm vào giỏ
                            </a>
                        </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- CỘT PHẢI: HÓA ĐƠN & THANH TOÁN -->
    <div class="col-right" style="flex: 1; min-width: 370px;">
        <div class="card-pos" style="height:100%; display: flex; flex-direction: column;">
            <div style="background:#e6bd2f; color:#17191c; padding: 14px 18px; border-bottom: 1px solid #28323c;">
                <h3 style="font-size: 16px; margin:0; font-weight:700; color:#17191c; display:flex; align-items:center;">
                    <i class="fas fa-receipt" style="color:#17191c; margin-right:8px; font-size:18px;"></i> Hóa Đơn Bán Hàng
                </h3>
            </div>
            <div class="card-body" style="flex: 1; overflow-y: auto; padding: 16px;">
                <form action="/Baitaplon/Banhang/ThanhToan" method="post" style="height: 100%; display: flex; flex-direction: column;">
                    
                    <!-- BẢNG DANH SÁCH MÓN ĐÃ CHỌN -->
                    <div style="flex: 1; min-height: 130px; max-height: 220px; overflow-y:auto; border:1px solid #28323c; margin-bottom:12px; border-radius: 8px; background:#12171c;">
                        <table style="width:100%; font-size:12px; border-collapse: collapse;">
                            <tr style="background:#e6bd2f; color:#17191c; font-weight:700; position: sticky; top: 0; border-bottom:1px solid #28323c;">
                                <td style="padding:8px 10px; color:#17191c;">Sản phẩm</td>
                                <td style="padding:8px 10px; text-align:center; color:#17191c;">SL</td>
                                <td style="padding:8px 10px; text-align:right; color:#17191c;">Thành tiền</td>
                                <td style="width:25px; text-align:center;"></td>
                            </tr>
                            <?php
                            $tongtien = 0;
                            if(isset($_SESSION['giohang']) && !empty($_SESSION['giohang'])){
                                foreach($_SESSION['giohang'] as $item){
                                    $thanhtien = $item['gia'] * $item['soluong']; 
                                    $tongtien += $thanhtien;
                                    echo "<tr style='border-bottom:1px solid #1f2730;'>
                                        <td style='padding:8px 10px; color:#e2e8f0;'>".htmlspecialchars($item['ten'], ENT_QUOTES, 'UTF-8')."</td>
                                        <td style='padding:8px 10px; text-align:center; font-weight:bold; color:#fbbf24;'>{$item['soluong']}</td>
                                        <td style='padding:8px 10px; text-align:right; font-weight:bold; color:#38ef7d;'>".number_format($thanhtien)." đ</td>
                                        <td style='padding:8px 10px; text-align:center;'><a href='/Baitaplon/Banhang/XoaGioHang/{$item['id']}' style='color:#ef4444; font-size:16px; font-weight:bold; text-decoration:none;' title='Xóa món'>&times;</a></td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' style='text-align:center; padding:35px 15px; color:#94a3b8;'>Giỏ hàng đang trống. Hãy chọn sản phẩm bên trái!</td></tr>";
                            }
                            ?>
                        </table>
                    </div>

                    <?php
                        $giamgia = isset($_SESSION['giamgia']) ? $_SESSION['giamgia']['SoTien'] : 0;
                        $thanhtoan = max(0, $tongtien - $giamgia);
                    ?>

                    <!-- MÃ KHUYẾN MÃI -->
                    <div style="background:#12171c; border:1px solid #28323c; padding:6px 10px; margin-bottom:12px; display:flex; gap:8px; border-radius: 8px;">
                        <select name="txtMaGiamGia" class="form-control form-sm" style="flex:1; background:#101418; color:#f1f5f9; border:1px solid #333d47; border-radius:6px; font-size: 13px;">
                            <option value="">-- Chọn mã khuyến mãi --</option>
                            <?php 
                            if(isset($data['dskm'])){
                                while($km = mysqli_fetch_array($data['dskm'])){
                                    echo '<option value="'.$km['TenMa'].'">';
                                    echo $km['TenMa'] . ' (-' . number_format($km['SoTienGiam']) . ' đ)';
                                    echo '</option>';
                                }
                            }
                            ?>
                        </select>
                        <button type="submit" name="btnApDung" formaction="/Baitaplon/Banhang/ApDungMa" 
                                style="background:linear-gradient(135deg, #f5d565, #d4a017); border:none; color:#111; font-weight:700; font-size: 12px; padding: 0 16px; border-radius:6px; cursor:pointer; white-space:nowrap; transition:all 0.2s ease;">
                            Áp dụng
                        </button>
                    </div>

                    <!-- TỔNG TIỀN VÀ KHÁCH TRẢ -->
                    <div style="text-align:right; border-top:1px solid #28323c; padding-top:8px; margin-bottom: 12px;">
                        <p style="margin:2px 0; font-size: 13px; color: #94a3b8;">
                            Tổng tiền: <b style="color:#e2e8f0;"><?php echo number_format($tongtien); ?> đ</b> 
                            - Giảm giá: <b style="color:#38ef7d;"><?php echo number_format($giamgia); ?> đ</b>
                        </p>
                        <h3 style="color:#f5d565; font-size:22px; margin: 6px 0; font-weight:800; letter-spacing:0.3px;">
                            KHÁCH TRẢ: <span id="spanCanTra"><?php echo number_format($thanhtoan); ?></span> đ
                        </h3>
                        <input type="hidden" id="txtCanTra" value="<?php echo $thanhtoan; ?>">
                    </div>

                    <!-- PHƯƠNG THỨC THANH TOÁN -->
                    <label>Phương thức thanh toán:</label>
                    <div class="payment-group">
                        <?php 
                        if(isset($data['pt'])){
                            $first = true;
                            while($pt = mysqli_fetch_array($data['pt'])){ 
                                $checked = $first ? 'checked' : ''; $first = false;
                                $icon = 'fa-money-bill-wave';
                                $name_lower = mb_strtolower($pt['TenPT'], 'UTF-8');
                                if(strpos($name_lower, 'khoản')!==false) $icon = 'fa-university';
                                if(strpos($name_lower, 'momo')!==false) $icon = 'fa-qrcode';
                                $qrLink = !empty($pt['HinhAnh']) ? "/Baitaplon/Public/Images/".$pt['HinhAnh'] : "";
                        ?>
                            <input type="radio" name="radPhuongThuc" id="pt_<?php echo $pt['MaPT']; ?>" 
                                   class="payment-option" value="<?php echo $pt['MaPT']; ?>" <?php echo $checked; ?>
                                   data-qr="<?php echo $qrLink; ?>"
                                   data-name="<?php echo $name_lower; ?>"
                                   onchange="checkPaymentMethod()">
                            <label for="pt_<?php echo $pt['MaPT']; ?>" class="payment-label">
                                <i class="fas <?php echo $icon; ?>"></i>
                                <span><?php echo htmlspecialchars($pt['TenPT'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                        <?php }} ?>
                    </div>

                    <!-- KHU VỰC HIỂN THỊ MÃ QR NẾU CHỌN CHUYỂN KHOẢN / VÍ -->
                    <div id="areaQR" class="qr-card">
                        <div class="qr-img-box" title="Bấm để phóng to" onclick="zoomQR(document.getElementById('imgQR').src)">
                            <img id="imgQR" src="" class="qr-image" onerror="this.src='https://img.icons8.com/ios/100/ffffff/qr-code--v1.png'">
                        </div>
                        <div class="qr-info">
                            <div class="qr-bank-name" id="lblBankName">NGÂN HÀNG</div>
                            <div class="qr-note">Nội dung: <span style="color:#f5d565; font-weight:bold;">DH<?php echo date('dmHis'); ?></span></div>
                            <div style="margin-top: 5px; font-size: 11px; color: #94a3b8;"><i>*Bấm vào mã QR để phóng to toàn màn hình</i></div>
                        </div>
                    </div>

                    <!-- KHU VỰC TÍNH TIỀN MẶT -->
                    <div id="areaTienMat" class="money-area">
                        <div class="row" style="display:flex; gap:10px; margin-bottom:8px;">
                            <div style="flex:1">
                                <label>Khách đưa:</label>
                                <input type="text" id="txtKhachDua" class="form-control form-sm input-money" value="0" onkeyup="tinhTienThua()" onclick="this.select()">
                            </div>
                            <div style="flex:1">
                                <label>Tiền thừa:</label>
                                <input type="text" id="txtTienThua" class="form-control form-sm return-money" readonly value="0">
                            </div>
                        </div>
                        <div class="money-grid">
                            <button type="button" class="btn-money" onclick="addMoney(1000)">1k</button>
                            <button type="button" class="btn-money" onclick="addMoney(2000)">2k</button>
                            <button type="button" class="btn-money" onclick="addMoney(5000)">5k</button>
                            <button type="button" class="btn-money" onclick="addMoney(10000)">10k</button>
                            <button type="button" class="btn-money" onclick="addMoney(20000)">20k</button>
                            <button type="button" class="btn-money" onclick="addMoney(50000)">50k</button>
                            <button type="button" class="btn-money" onclick="addMoney(100000)">100k</button>
                            <button type="button" class="btn-money" onclick="addMoney(200000)">200k</button>
                            <button type="button" class="btn-money" onclick="addMoney(500000)">500k</button>
                        </div>
                        <button type="button" class="btn-money" style="width:100%; margin-top:8px; background:#dc2626 !important; color:#fff !important; font-weight:700; border:none; border-radius:6px; padding:9px 0;" onclick="resetMoney()">
                            <i class="fas fa-undo" style="margin-right:6px;"></i> Xóa tiền
                        </button>
                    </div>

                    <!-- NÚT THANH TOÁN -->
                    <button type="submit" name="btnThanhToan" 
                            style="width:100%; padding:14px; font-size:16px; margin-top:auto; border-radius:30px; font-weight:800; letter-spacing:0.5px; background:linear-gradient(135deg, #f5d565, #d4a017); color:#111; border:none; box-shadow:0 4px 18px rgba(245, 213, 101, 0.3); cursor:pointer; transition:all 0.2s ease;">
                        <i class="fas fa-check-circle" style="margin-right:8px;"></i> THANH TOÁN
                    </button>
                    <a href="/Baitaplon/Banhang/HuyDon" style="display:block; text-align:center; margin-top:10px; color:#94a3b8; font-size:13px; text-decoration:none;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#94a3b8'">
                        <i class="fas fa-trash-alt" style="margin-right:4px;"></i> Hủy đơn hàng
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PHÓNG TO QR -->
<div id="qrModal" class="qr-modal" onclick="closeQR()">
    <span class="close-qr" onclick="closeQR()">&times;</span>
    <img class="qr-modal-content" id="imgBigQR">
</div>

<script>
    function formatNumber(num) { return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'); }
    function addMoney(amount) {
        var current = parseInt(document.getElementById("txtKhachDua").value.replace(/,/g, '')) || 0;
        document.getElementById("txtKhachDua").value = formatNumber(current + amount);
        tinhTienThua();
    }
    function resetMoney() { document.getElementById("txtKhachDua").value = 0; tinhTienThua(); }
    
    function tinhTienThua() {
        var khachDua = parseInt(document.getElementById("txtKhachDua").value.replace(/,/g, '')) || 0;
        var canTra = parseInt(document.getElementById("txtCanTra").value) || 0;
        var thua = khachDua - canTra;
        var elTienThua = document.getElementById("txtTienThua");
        elTienThua.value = formatNumber(thua);
        if(thua >= 0) { 
            elTienThua.style.color = "#38ef7d"; 
            elTienThua.style.background = "#13231b"; 
            elTienThua.style.borderColor = "#10b981";
        } else { 
            elTienThua.style.color = "#f87171"; 
            elTienThua.style.background = "#2a1518"; 
            elTienThua.style.borderColor = "#ef4444";
        }
    }

    function checkPaymentMethod() {
        var radios = document.getElementsByName('radPhuongThuc');
        var selectedQR = "", selectedName = "", selectedLabelText = "";
        
        for (var i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                selectedQR = radios[i].getAttribute('data-qr');
                selectedName = radios[i].getAttribute('data-name') || '';
                var label = document.querySelector('label[for="' + radios[i].id + '"]');
                selectedLabelText = label ? label.innerText.trim() : '';
                break;
            }
        }

        var isOnlinePayment = selectedName.includes("khoản") || selectedName.includes("momo") || selectedName.includes("qr") || selectedName.includes("ví") || selectedName.includes("thẻ");

        if (isOnlinePayment) {
            document.getElementById("areaQR").style.display = "flex";
            document.getElementById("areaTienMat").style.display = "none";
            document.getElementById("lblBankName").innerText = selectedLabelText.toUpperCase();
            if(selectedQR && selectedQR !== "") { document.getElementById("imgQR").src = selectedQR; } 
            else { document.getElementById("imgQR").src = "https://img.icons8.com/ios/100/ffffff/qr-code--v1.png"; }

            var canTra = document.getElementById("txtCanTra").value;
            document.getElementById("txtKhachDua").value = formatNumber(canTra);
        } else {
            document.getElementById("areaQR").style.display = "none";
            document.getElementById("areaTienMat").style.display = "block";
            document.getElementById("txtKhachDua").value = "0";
            tinhTienThua();
        }
    }
    checkPaymentMethod();

    function zoomQR(src) {
        var modal = document.getElementById("qrModal");
        var modalImg = document.getElementById("imgBigQR");
        modal.style.display = "flex";
        modalImg.src = src;
    }

    function closeQR() {
        document.getElementById("qrModal").style.display = "none";
    }
</script>
