@foreach($invoices as $invoice)
    <a href="{{route('invoice.show',['invoice' => $invoice->id])}}" >{{$invoice->number}}</a> {{$invoice->status}}</br>
@endforeach
