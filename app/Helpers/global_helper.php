<?php

if(!function_exists('generateUniqueSlug')){

    /** Create unique slug */
    function generateUniqueSlug($model, $name): string
    {
        $modelClass = "App\\Models\\$model";

        if (!class_exists($modelClass)) {
            throw new \InvalidArgumentException("Model $model not found.");
        }

        $slug = \Str::slug($name);
        $count = 2;

        while ($modelClass::where('slug', $slug)->exists()) {
            $slug = \Str::slug($name) . '-' . $count;
            $count++;
        }

        return $slug;
    }
}

if (!function_exists('currencyPosition')){
    function currencyPosition($price) : string{

        if(config('settings.site_currency_icon_position') === 'left') {
            return config('settings.site_currency_icon') . $price;
        } else {
            return $price . config ('settings.site_currency_icon');
        }
    }
}

if (!function_exists('cartTotal')) {
    function cartTotal()
    {
        $total = 0;

        foreach (Cart::content() as $item) {
            $productPrice = $item->price;

            $total += $productPrice  * $item->qty;
        }

        return $total;
    }

}

if (!function_exists('productTotal')) {
    function productTotal($rowId)
    {
        $total = 0;

        $product = Cart::get($rowId);

        $productPrice = $product->price;


        $total += $productPrice  * $product->qty;

        return $total;
    }

}

/** grand cart total */
if (!function_exists('grandCartTotal')) {
    function grandCartTotal()
    {
        $total = 0;
        $cartTotal = cartTotal();

        if (session()->has('coupon')) {
            $discount = session()->get('coupon')['discount'];
            $total = $cartTotal - $discount;

            return $total;
        }else {
            $total = $cartTotal;
            return $total;
        }

    }
}


/** Generate Invoice Id */
if (!function_exists('generateInvoiceId')) {
    function generateInvoiceId()
    {
        $randomNumber = rand(1, 9999);
        $currentDateTime = now();

        $invoiceId = $randomNumber . $currentDateTime->format('yd') . $currentDateTime->format('s');

        return $invoiceId;
    }
}

/** get product discount in percent */
if (!function_exists('truncate')) {
    function truncate(string $string, int $limit = 100)
    {
        return \Str::limit($string, $limit, '...');
    }
}

/** get product discount in percent */
if (!function_exists('getYtThumbnail')) {
    function getYtThumbnail($link, $size = 'medium')
    {
        try {
            $videoId = explode("?v=", $link);
            $videoId = $videoId[1];

            $finalSize = match ($size) {
                'low' => 'sddefault',
                'medium' => 'mqdefault',
                'high' => 'hqdefault',
                'max' => 'maxresdefault'
            };

            return "https://img.youtube.com/vi/$videoId/$finalSize.jpg";
        } catch (\Exception $e) {
            logger($e);
            return NULL;
        }
    }

    /** get product discount in percent */
    if (!function_exists('setSidebarActive')) {
        function setSidebarActive(array $routes)
        {
            foreach($routes as $route){
                if(request()->routeIs($route)){
                    return 'active';
                }
            }
            return '';
        }
    }
}
