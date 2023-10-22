{{$invoice->status}}

<a href="{{route('invoice.approve', ['invoice' => $invoice->id])}}">Approve</a>
<a href="{{route('invoice.reject', ['invoice' => $invoice->id])}}">Reject</a>
