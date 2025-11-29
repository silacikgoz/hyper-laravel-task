<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Sepet</title>

<style>
body {
    font-family: 'Inter', Arial, sans-serif;
    background: #f3f4f6;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 960px;
    margin: 30px auto;
    padding: 0 20px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.btn {
    background: #2563eb;
    color: #fff;
    padding: 10px 16px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    transition: 0.2s;
    text-decoration: none;
    display: inline-block;
}

.btn:hover {
    background: #1e4ecb;
}

.btn.secondary {
    background: #e5e7eb;
    color: #111;
}

.btn.secondary:hover {
    background: #d1d5db;
}

.table {
    background: #fff;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}

.item {
    display: flex;
    gap: 16px;
    align-items: center;
    padding: 16px 0;
    border-bottom: 1px solid #eee;
}

.item:last-child {
    border-bottom: none;
}

.item img {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.item-details {
    flex: 1;
}

.item-details .title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 4px;
}

.item-details .price {
    font-size: 14px;
    color: #6b7280;
}

.qty input {
    width: 60px;
    padding: 6px 8px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    font-size: 14px;
}

.total {
    margin-top: 20px;
    font-size: 20px;
    font-weight: 700;
    text-align: right;
}

.empty {
    text-align: center;
    padding: 40px 0;
    font-size: 18px;
    color: #6b7280;
}
</style>
</head>

<body>
<div class="container">

  <div class="header">
    <h1>Sepetim</h1>
    <a href="{{ route('products.index') }}" class="btn secondary">Ürünlere Dön</a>
  </div>

  <div class="table">
    @forelse($cart->items as $item)
      <div class="item" data-price="{{ $item->price }}" data-id="{{ $item->id }}">
        <img src="{{ $item->image ?? 'https://via.placeholder.com/90' }}" alt="{{ $item->name }}">

        <div class="item-details">
          <div class="title">{{ $item->name }}</div>
          <div class="price">{{ number_format($item->price,2,',','.') }} ₺</div>
        </div>

        <div class="qty">
          <form method="post" class="qty-form" action="{{ route('cart.updateQty', $item->id) }}">
            @csrf
            <input type="number" name="qty" class="qty-input" value="{{ $item->qty }}" min="1">
          </form>
        </div>

        <div class="remove-btn">
          <form method="post" action="{{ route('cart.remove', $item->id) }}">
            @csrf
            <button class="btn secondary" type="submit">Kaldır</button>
          </form>
        </div>
      </div>

    @empty
      <div class="empty">Sepetiniz şu anda boş.</div>
    @endforelse

    <div class="total" id="cart-total">
      Toplam: {{ number_format($total,2,',','.') }} ₺
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    function updateTotal() {
        let total = 0;

        document.querySelectorAll('.item').forEach(item => {
            const price = parseFloat(item.dataset.price);
            const qty = parseInt(item.querySelector('.qty-input').value);
            total += price * qty;
        });

        document.querySelector('#cart-total').innerText =
            "Toplam: " + total.toLocaleString('tr-TR', { minimumFractionDigits: 2 }) + " ₺";
    }


    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', function() {
            updateTotal();
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                this.closest('.qty-form').submit();
            }, 700);
        });
    });

    updateTotal();
});
</script>

</body>
</html>
