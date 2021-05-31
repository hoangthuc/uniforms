@if( isset($billing_address['address_1']) )
    <span>{!! $billing_address['address_1'] !!}</span><br/>
    <span>{!! $billing_address['address_2'] !!}</span><br/>
    <span>{!! $billing_address['city'].', '.$billing_address['state'].', '.$billing_address['zipcode'] !!}</span><br/>
    <span>{!! $billing_address['email'].', '.$billing_address['phone'] !!}</span>
@endif

@if( isset($shipping_address['address_1']) )
    <span>{!! $shipping_address['address_1'] !!}</span><br/>
    <span>{!! $shipping_address['address_2'] !!}</span><br/>
    <span>{!! $shipping_address['city'].', '.$shipping_address['state'].', '.$shipping_address['zipcode'] !!}</span><br/>
    <span>{!! $shipping_address['email'].', '.$shipping_address['phone'] !!}</span>
@endif

@if( isset($tracking_order['Tracking']) )
    <span>Tracking number: {!! $tracking_order['Tracking'] !!}</span><br/>
    <span>Company: {!! $tracking_order['Carrier'] !!}</span><br/>
    <span>URL: {!! $tracking_order['url_tracking'] !!}</span><br/>
    <span>Ship Date: {!! $tracking_order['ShipDate'] !!}</span><br/>
    <span>Packing List: {!! $tracking_order['PackingList'] !!}</span>
@endif