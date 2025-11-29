<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Ürünler</title>
<style>
:root{
  --bg:#f6f8fb;
  --card:#ffffff;
  --accent:#2563eb;
  --muted:#6b7280;
}
*{box-sizing:border-box}
body { font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; margin:0; padding:0; background:var(--bg); color:#111; }
.container { max-width:1200px; margin:28px auto; padding:0 20px; }
.header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; gap:12px; }
.header .title { font-size:1.6rem; font-weight:700; }
.controls { display:flex; gap:10px; align-items:center; }
.btn { background:var(--accent); color:#fff; padding:8px 12px; border-radius:10px; border:none; cursor:pointer; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:8px; }
.btn.ghost { background:transparent; color:var(--accent); border:1px solid rgba(37,99,235,0.12); }
.card-grid { display:grid; grid-template-columns: repeat(auto-fill,minmax(220px,1fr)); gap:16px; }
.card { background:var(--card); border-radius:12px; padding:12px; box-shadow: 0 6px 18px rgba(15,23,42,0.06); display:flex; flex-direction:column; min-height:320px; }
.card .media { height:160px; border-radius:8px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#f3f4f6; }
.card .media img{ width:100%; height:100%; object-fit:cover; display:block; }
.card .body { padding-top:10px; display:flex; flex-direction:column; gap:8px; flex:1; }
.meta { display:flex; justify-content:space-between; align-items:center; gap:8px; }
.title { font-size:1rem; font-weight:700; color:#0f172a; }
.price { font-weight:800; color:#0b6ff3; }
.card form { margin-top:auto; display:flex; gap:8px; }
.qty-input { width:72px; padding:8px; border-radius:8px; border:1px solid #e6e9ef; text-align:center; }
.pager { margin-top:20px; display:flex; justify-content:center; align-items:center; gap:10px; color:var(--muted); }
.page-btn { background:#fff; border:1px solid #e6e9ef; padding:8px 10px; border-radius:8px; cursor:pointer; text-decoration:none; color:#111; }
.badge { background:#f1f5f9; color:#0f172a; padding:6px 8px; border-radius:999px; font-weight:600; font-size:0.85rem; }
.empty { text-align:center; padding:40px 0; color:var(--muted); }
@media (max-width:640px){
  .header { flex-direction:column; align-items:flex-start; gap:8px; }
  .card { min-height:320px; }
}
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <div class="title">Ürünler</div>
    <div class="controls">
      
      <a href="{{ route('cart.index') }}" class="btn ghost">
        Sepet
        <span class="badge">
          {{ \App\Models\Cart::where('session_id', session()->getId())->first()?->items()->count() ?? 0 }}
        </span>
      </a>
    
    </div>
  </div>

  @if(session('success'))
    <div style="background:#ecfdf5;padding:10px;border-radius:8px;margin-bottom:12px;color:#065f46">
      {{ session('success') }}
    </div>
  @endif

  @if(empty($products) || count($products) === 0)
    <div class="empty">
      <p>Şu anda gösterilecek ürün bulunamadı.</p>
    </div>
  @else
    <div class="card-grid">
      @foreach($products as $p)
        @php
          
          $id = $p['productID'] ?? $p['id'] ?? $p['product_id'] ?? ($p['id'] ?? null);
          $name = $p['productName'] ?? $p['name'] ?? $p['title'] ?? 'Ürün';
          $image = $p['productData']['productMainImage'] ?? ($p['image'] ?? ($p['images'][0] ?? 'https://via.placeholder.com/400'));
          $price = $p['salePrice'] ?? $p['price'] ?? $p['listPrice'] ?? 0;
        @endphp

        <div class="card">
          <div class="media">
            <img src="{{ $image }}" alt="{{ $name }}">
          </div>
          <div class="body">
            <div class="meta">
              <div class="title">{{ $name }}</div>
              <div class="price">{{ number_format($price,2,',','.') }} ₺</div>
            </div>

            <div style="color:var(--muted);font-size:0.9rem">
              {{ Str::limit($p['productData']['productInfo'] ?? ($p['info'] ?? ''), 80) }}
            </div>

            <form method="post" action="{{ route('cart.add') }}">
              @csrf
              <input type="hidden" name="product_id" value="{{ $id }}">
              <input type="hidden" name="name" value="{{ $name }}">
              <input type="hidden" name="price" value="{{ $price }}">
              <input type="hidden" name="image" value="{{ $image }}">
              <input class="qty-input" type="number" name="qty" value="1" min="1">
              <button class="btn" type="submit">Sepete Ekle</button>
            </form>
          </div>
        </div>
      @endforeach
    </div>

    <div class="pager">
      @php
        $cur = $meta['current_page'] ?? 1;
        $last = $meta['last_page'] ?? 1;
      @endphp

      @if($cur > 1)
        <a class="page-btn" href="{{ route('products.index', ['page' => $cur - 1]) }}">◀ Önceki</a>
      @endif

      <span class="badge"> Sayfa {{ $cur }} / {{ $last }} </span>

      @if($cur < $last)
        <a class="page-btn" href="{{ route('products.index', ['page' => $cur + 1]) }}">Sonraki ▶</a>
      @endif
    </div>
  @endif
</div>
</body>
</html>
