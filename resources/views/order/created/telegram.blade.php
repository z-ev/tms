<b>🛒 Создан новый заказ@if (env('APP_ENV') != 'production') | {{strtoupper(env('APP_ENV'))}}@endif</b>

        <b>Поставщик: {{$order->merchant->name}}</b>

        <b>№:</b> {{$order->number}}

        <b>Адрес:</b>
        {{$order->address}}

        <b>GUID Поставщика:</b> {{$order->merchant->id}}
        <b>GUID Заказа:</b> {{$order->id}}

        <b>Дата создания: </b>{{\Carbon\Carbon::parse($order->created_at)->format('d.m.Y')}}

@if (count($order->items) > 0)
        <b>🚚 Состав:</b>
@foreach ($order->items as $product)
        {{$product['sku']}} | <a href="{{$product['url']}}">{{$product['title']}}</a> | {{ number_format($product['amount']/100,2, ',', ' ')}} {{$order->currency}} x {{$product['qty']}} {{$product['baseMeasure']}} = {{number_format($product['totalAmount']/100,2, ',', ' ')}} {{$order->currency}}
@endforeach

@endif
@if ($order->total_vat_amount > 0)
        <b>Сумма:</b> {{number_format($order->total_amount_without_vat/100,2, ',', ' ')}} {{$order->currency}}
        <b>НДС:</b> {{number_format($order->total_vat_amount/100,2, ',', ' ')}} {{$order->currency}}

@endif
        <b>ИТОГО:</b> {{number_format($order->total_amount/100,2, ',', ' ')}} {{$order->currency}}

@if ($order->description !== null)
        <b>Комментарий: </b>{{$order->description}}

@endif
        <a href="{{env('FRONT_URL') . '/' . env('FRONT_URL_ORDERS')}}">Перейти в заказ</a>
