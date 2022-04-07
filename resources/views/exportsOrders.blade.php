<style>
    table, th, td {
  border: 1px solid black;
}
td {
    min-width: 100px
}
</style>
<table>

    <tbody>
        <tr>
            @foreach (collect($orders[0])->keys() as $k)
            <td>{{$k}}</td>
            @endforeach
            @if (sizeof($orders[0]->items) > 0)
                @foreach (collect($orders[0]->items[0])->keys() as $k)
                        <td>{{$k}}</td>
                @endforeach
            @endif
        </tr>

        @foreach ($orders as $order_key => $order)
        <tr>
            @php
            unset($order['items'])
            @endphp
            @foreach (collect($order)->values() as $k => $v)
                @if (gettype($v) == "string")
                    <td>{{$v}}</td>
                @else
                    <td>@php echo json_encode($v)@endphp</td>
                @endif
                @endforeach

                @foreach ($order->items as $item_key => $item)
                    @if($item_key == 0)
                        @foreach (collect($item)->values() as $item_v)
                            <td>{{$item_v}}</td>
                        @endforeach
                    @endif
                @endforeach
            </tr>

                @foreach ($order->items as $k => $item)
                    @if($k != 0)
                        <tr>
                            @php
                            unset($order['items'])
                            @endphp
                            @foreach (collect($order)->values() as $k => $v)
                                <td></td>
                            @endforeach
                            @foreach (collect($item)->values() as $v)
                                <td>{{$v}}</td>
                            @endforeach
                        </tr>
                        @endif
                    @endforeach

            @endforeach

    </tbody>
</table>
