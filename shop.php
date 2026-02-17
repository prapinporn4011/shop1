<script>
// แก้ไขฟังก์ชัน openCart ให้ดึงที่อยู่อัตโนมัติ
shop.openCart = () => {
    const modal = document.getElementById('cartModal');
    modal.style.display = "block";
    
    // ดึงข้อมูลที่อยู่จาก Session (ที่เก็บไว้ตอน Login)
    const savedPhone = sessionStorage.getItem('user_phone') || '';
    const savedAddress = sessionStorage.getItem('user_address') || '';
    
    document.getElementById('cust-phone').value = savedPhone;
    document.getElementById('cust-address').value = savedAddress;
    
    // ... โค้ดส่วนเรนเดอร์สินค้าเดิมของคุณ ...
}
</script>