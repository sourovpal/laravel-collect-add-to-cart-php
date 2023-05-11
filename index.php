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
