@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form id="productForm" method="post" action="{{ route('invoice_update',$invoice_id) }}">
                    @csrf
                    
                    <div class="row card-body">
                        <h4 class="card-title">Invoice Edit</h4>
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" value="{{ $customer_get->name }}" name="name" placeholder="Enter full name...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" value="{{ $customer_get->phone_number }}"  name='phone_number'
                                    placeholder="Enter Phone number...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" value="{{ $customer_get->gst_number }}" name="gst_number" placeholder="Enter GST...">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="form-control" rows="3" value="{{ $customer_get->full_address }}" name="full_address" placeholder="Full Address..." style="height: 7%;">{{ $customer_get->full_address }}</textarea>
                            </div>
                        </div>
                       <div class="col-md-12">
                            <select class="form-select" onchange="customerType(this)" name="customer_type">
                                <option>Select customer type</option>
                                <option value="distributer" {{ $customer_get->customer_type == "distributer" ? "selected" : "" }}>Distributer</option>
                                <option value="retailer" {{ $customer_get->customer_type == "retailer" ? "selected" : "" }}>Retailer</option>
                            </select>
                        </div>

                        <div class="col-md-12 mt-2">
                            <select class="js-example-basic-single w-100" onchange="productSearch(this)" name="state">
                                <option>Select Product</option>
                                @foreach ($product as $prod)
                                    <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-12 mt-3 ">
                            <table class="table table-bordered productcheck">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Original Price</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach ($productData as $product)
                                     
                                        <tr>
                                            <td class="border-right">
                                                <input type="hidden" name="id[]" value="{{ $product->id }}">
                                                <input type="hidden" name="selling_price[]" value="{{ $customer_get->customer_type == 'distributer' ? $product->distributer_price : $product->retailer_price }}">
                                                {{ $product->name }}</td>
                                            <td class="border-right">{{ $product->se_price }}</td>
                                            <td><input type="number" value="{{ $product->quantity }}" name="quantity[]" class="form-control" onkeyup="calculateTotal(this)"></td>
                                            <td class="border-right totalPrice">
                                                <input type="hidden" name="totalprice[]" value="{{ $product->total_price }}">{{ $product->total_price }}</td>
                                            <td><button onclick="removeRow(this)" class="btn btn-danger btn-sm">Remove</button></td></tr>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="m-2">
                                <input type="button" value="Invoice" onclick="submitForm()" class="btn btn-primary">
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <script type="text/javascript">
       function productSearch(id) {
        var customerType = $('select[name="customer_type"]').val();
        var url = "{{ route('admin.product_Search', ':id') }}"; 
        url = url.replace(':id', id.value); 
        

        $.ajax({
            url: url, // Use the updated URL
            type: 'get',
            data: {
                'customerType': customerType
            },
            success: function(result) {
                var price = customerType == 'distributer' ? result.data.distributer_price : result.data.retailer_price;
                $('.productcheck tbody').append('<tr><td><input type="hidden" name="id[]" value="' + result.data.id + '"><input type="hidden" name="selling_price[]" value="' + price + '">' + result.data.name + '</td><td>' + price + '</td><td><input type="number" name="quantity[]" class="form-control" onkeyup="calculateTotal(this)"></td><td class="totalPrice"></td><td><button onclick="removeRow(this)" class="btn btn-danger btn-sm">Remove</button></td></tr>');
            }
        });
    }



        function customerType(customerType) {
            var product_id = $('select[name="state"]').val();
            if (product_id != 'Select Product') {
                productSearch({
                    value: product_id
                });
            }
        }

        function removeRow(button) {
            $(button).closest('tr').remove();
        }

        function calculateTotal(input) {
            var row = $(input).closest('tr');
            var sellingPrice = parseFloat(row.find('td:eq(1)').text());
            var quantity = parseInt($(input).val());
            var totalPrice = sellingPrice * quantity;
            row.find('.totalPrice').html('<input type="hidden" name="totalprice[]" value="' + totalPrice.toFixed(2) + '">' +
                totalPrice.toFixed(2));
        }

        function submitForm() {
            $('#productForm').submit();
            // $('.productcheck tbody').empty(); // Remove table rows
        }
       

    </script>

@endsection
