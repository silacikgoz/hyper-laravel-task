<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;

class CartController extends Controller
{
    protected function getCart()
    {
        $sessionId = session()->getId();
        return Cart::firstOrCreate(['session_id'=>$sessionId]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id'=>'required',
            'name'=>'nullable|string',
            'price'=>'required|numeric',
            'qty'=>'nullable|integer|min:1',
            'image'=>'nullable|string'
        ]);

        $cart = $this->getCart();
        $item = $cart->items()->where('product_id',$data['product_id'])->first();

        if ($item) {
            $item->qty += $data['qty'] ?? 1;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id'=>$data['product_id'],
                'name'=>$data['name']??'',
                'price'=>$data['price'],
                'qty'=>$data['qty']??1,
                'image'=>$data['image']??null,
            ]);
        }

        return redirect()->back()->with('success','Ürün sepete eklendi.');
    }

    public function index()
    {
        $cart = $this->getCart()->load('items');
        return view('cart.index', ['cart'=>$cart, 'total'=>$cart->total()]);
    }

    public function remove($itemId)
    {
        $cart = $this->getCart();
        $item = $cart->items()->findOrFail($itemId);
        $item->delete();
        return redirect()->route('cart.index')->with('success','Ürün kaldırıldı.');
    }

    public function updateQty(Request $request, $itemId)
    {
        $qty = max(1, (int) $request->get('qty',1));
        $cart = $this->getCart();
        $item = $cart->items()->findOrFail($itemId);
        $item->qty = $qty;
        $item->save();
        return redirect()->route('cart.index');
    }
}
