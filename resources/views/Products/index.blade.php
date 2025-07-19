<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product JSON App</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <div class="container">
        <h2>Add Product</h2>
        <form id="productForm" class="row g-3 mb-4">
            <div class="col-md-4">
            <input type="text" name="product" class="form-control" placeholder="Product Name" required>
            </div>
            <div class="col-md-3">
            <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
            </div>
            <div class="col-md-3">
            <input type="number" name="price" class="form-control" placeholder="Price" required>
            </div>
            <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>

        <h3>Products</h3>
        <table class="table table-bordered">
            <thead>
            <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Submitted</th>
            <th>Total</th>
            <th>Action</th>
            </tr>
            </thead>
            <tbody id="productTable">
            @foreach($products as $i => $product)
            <tr>
                <td>{{ $product['product'] }}</td>
                <td>{{ $product['quantity'] }}</td>
                <td>{{ $product['price'] }}</td>
                <td>{{ $product['created_at'] }}</td>
                <td>{{ $product['quantity'] * $product['price'] }}</td>
                <td>
                <button class="btn btn-sm btn-info editBtn"
                        data-index="{{ $i }}"
                        data-product="{{ $product['product'] }}"
                        data-quantity="{{ $product['quantity'] }}"
                        data-price="{{ $product['price'] }}">
                    Edit
                </button>
                </td>
            </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
            <td colspan="4" class="text-end"><strong>Total Sum:</strong></td>
            <td colspan="2"><strong>{{ $totalSum }}</strong></td>
            </tr>
            </tfoot>
        </table>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <form id="editForm">
                <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <input type="hidden" id="edit-index">
                <input type="text" id="edit-product" class="form-control mb-2" required>
                <input type="number" id="edit-quantity" class="form-control mb-2" required>
                <input type="number" id="edit-price" class="form-control mb-2" required>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Submit new product
        $('#productForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
            type: 'POST',
            url: '/products',
            data: $(this).serialize(),
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: () => location.reload()
            });
        });

        // Open edit modal
        $('.editBtn').click(function () {
            $('#edit-index').val($(this).data('index'));
            $('#edit-product').val($(this).data('product'));
            $('#edit-quantity').val($(this).data('quantity'));
            $('#edit-price').val($(this).data('price'));
            new bootstrap.Modal(document.getElementById('editModal')).show();
        });

        // Submit edit
        $('#editForm').submit(function(e) {
        e.preventDefault();
        const index = $('#edit-index').val();
        $.ajax({
        type: 'PUT',
        url: '/products/' + index,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
            product: $('#edit-product').val(),
            quantity: $('#edit-quantity').val(),
            price: $('#edit-price').val()
        },
        success: () => location.reload()
        });
        });
    </script>
</body>
</html>
