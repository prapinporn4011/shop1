<div class="container mt-4">
    <h3>เพิ่ม/แก้ไขสินค้า</h3>
    <form action="save_product.php" method="POST" enctype="multipart/form-data">>
        <div class="mb-3">
    <label class="form-label">ชื่อสินค้า</label>
    <input type="text" name="product_name" class="form-control" required>
    <input type="file" name="image" class="form-control"> </div>

        </div>
        <div class="mb-3">
            <label class="form-label">รายละเอียด</label>
            <textarea class="form-control" rows="3"></textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">ราคา</label>
                <input type="number" name="price" class="form-control" required>
                <input type="number" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">จำนวนสต็อก</label>
                <input type="number" name="stock" class="form-control" required>
                <input type="number" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
        <a href="index.php" class="btn btn-secondary">ยกเลิก</a>
    </form>
</div>
