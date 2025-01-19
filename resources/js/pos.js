document.addEventListener('DOMContentLoaded', function() {
    let cart = [];
    let customer = null;
    const cartItems = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');
    const customerInfo = document.getElementById('customerInfo');
    const processPaymentBtn = document.getElementById('processPayment');
    const searchInput = document.getElementById('search');

    // Search functionality
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('.product-item').forEach(item => {
            const productName = item.getAttribute('data-name').toLowerCase();
            item.style.display = productName.includes(searchTerm) ? '' : 'none';
        });
    });

    // Add to cart
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productItem = this.closest('.product-item');
            const productId = productItem.getAttribute('data-id');
            const productName = productItem.getAttribute('data-name');
            const productPrice = parseFloat(productItem.getAttribute('data-price'));
            const productStock = parseInt(productItem.getAttribute('data-stock'));

            const existingItem = cart.find(item => item.id === productId);
            if (existingItem) {
                if (existingItem.quantity < productStock) {
                    existingItem.quantity++;
                    existingItem.subtotal = existingItem.quantity * existingItem.price;
                } else {
                    alert('Stok tidak mencukupi');
                    return;
                }
            } else {
                cart.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1,
                    subtotal: productPrice,
                    stock: productStock
                });
            }

            updateCartDisplay();
        });
    });

    // Scan QR Code
    document.getElementById('scanQr').addEventListener('click', async function() {
        try {
            const qrCode = prompt('Scan QR Code atau masukkan kode manual:');
            if (!qrCode) return;

            const response = await fetch('/staff/pos/scan-qr', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ qr_code: qrCode })
            });

            const data = await response.json();
            if (data.success) {
                customer = data.user;
                customerInfo.classList.remove('hidden');
                document.querySelector('.customer-name').textContent = customer.name;
                document.querySelector('.customer-balance').textContent = 
                    `Saldo: Rp ${new Intl.NumberFormat('id-ID').format(customer.balance)}`;
                processPaymentBtn.disabled = false;
            } else {
                alert(data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses QR Code');
        }
    });

    // Process Payment
    processPaymentBtn.addEventListener('click', async function() {
        if (!customer || cart.length === 0) return;

        try {
            const paymentMethod = document.getElementById('paymentMethod').value;
            const response = await fetch('/staff/pos/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    user_id: customer.id,
                    items: cart.map(item => ({
                        product_id: item.id,
                        quantity: item.quantity
                    })),
                    payment_method: paymentMethod
                })
            });

            const data = await response.json();
            if (data.success) {
                alert('Pembayaran berhasil!');
                window.open(`/staff/pos/receipt/${data.order_id}`, '_blank');
                resetCart();
            } else {
                alert(data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses pembayaran');
        }
    });

    function updateCartDisplay() {
        cartItems.innerHTML = '';
        let total = 0;

        cart.forEach((item, index) => {
            const itemElement = document.createElement('div');
            itemElement.className = 'flex items-center justify-between p-2 border rounded';
            itemElement.innerHTML = `
                <div>
                    <h4 class="font-medium">${item.name}</h4>
                    <p class="text-sm text-gray-600">
                        ${item.quantity} x Rp ${new Intl.NumberFormat('id-ID').format(item.price)}
                    </p>
                </div>
                <div class="text-right">
                    <p class="font-medium">Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</p>
                    <button class="text-red-600 hover:text-red-800" onclick="removeFromCart(${index})">
                        Hapus
                    </button>
                </div>
            `;
            cartItems.appendChild(itemElement);
            total += item.subtotal;
        });

        cartTotal.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
        processPaymentBtn.disabled = !customer || cart.length === 0;
    }

    function resetCart() {
        cart = [];
        customer = null;
        customerInfo.classList.add('hidden');
        updateCartDisplay();
    }

    window.removeFromCart = function(index) {
        cart.splice(index, 1);
        updateCartDisplay();
    };
}); 