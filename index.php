$product = Product::with('variations')->findOrFail($request->id);
        
// Session::forget('addtocarts');
// return;
if(Session::has('addtocarts')){
    $data = collect(Session::get('addtocarts'));
    // return $data->sum(function($item){
    //     return $item->variations->sum('default_sell_price');
    // });
    $find = $data->where('id', $product->id);
    if(count($find)){
        $temp = $find[$product->id];
        $temp->select_qty = ($temp->select_qty) + ($request->qty??1);
        return back()->with('success', 'Product added to cart successfully.');
    }else{
        $product->select_qty = $request->qty??1;
        $data->put($product->id, $product);
        Session::forget('addtocarts');
        Session::put('addtocarts', $data->all());
        return back()->with('success', 'Product added to cart successfully.');
    }
}

$product->select_qty = $request->qty??1;
Session::put('addtocarts', [$product->id => $product]);
return back()->with('success', 'Product added to cart successfully.');

$totalPrice = collect(\Session::get('addtocarts'))->map(function($item){ 
return ['total_price' => collect($item->variations)->map(function($vari)use($item){
    return $vari->default_sell_price * $item->select_qty;
})[0]];
});
@endphp
{{ collect($totalPrice)->sum('total_price') }}
