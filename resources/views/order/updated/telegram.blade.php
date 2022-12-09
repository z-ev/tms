<b>🛒 {{$order->getStatus()['title']}}@if (env('APP_ENV') != 'production') | {{strtoupper(env('APP_ENV'))}}@endif</b>

        <b>Поставщик: {{$order->merchant->name}}</b>

        <b>№:</b> {{$order->number}}

        <b>GUID Поставщика:</b> {{$order->merchant->id}}
        <b>GUID Заказа:</b> {{$order->id}}

        <a href="{{env('FRONT_URL') . '/' . env('FRONT_URL_ORDERS')}}">Перейти в заказ</a>
