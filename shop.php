<form action="save_order.php" method="POST">
    <div class="modal-body">
        <div class="mb-3">
            <label>ชื่อ-นามสกุล ผู้รับ:</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>เบอร์โทรศัพท์:</label>
            <input type="text" name="cust_phone" id="cust-phone" class="form-control">
        </div>
        <div class="mb-3">
            <label>ที่อยู่จัดส่ง:</label>
            <textarea name="cust_address" id="cust-address" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label>ยอดรวมทั้งสิ้น (บาท):</label>
            <input type="number" name="total_price" value="840" class="form-control" readonly>
            </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">ยืนยันการสั่งซื้อ</button>
    </div>
</form>