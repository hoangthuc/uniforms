<div class="modal fade" id="editProductOrderModal" tabindex="-1" aria-labelledby="editProductOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductOrderModalLabel">
                    Update Product Order
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <table class="table table-bordered ">
                                <tr>
                                    <td class="text-center" >
                                        <b>Product</b>
                                    </td>
                                    <td id="edit_product_name">

                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" >
                                        <b>Qty</b>
                                    </td>
                                    <td>
                                        <input type="number" min="0" id="edit_product_qty" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        <b>
                                            Unit
                                        </b>
                                    </td>
                                    <td id="edit_product_unit_price">

                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        <b>
                                            Subtotal
                                        </b>
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control" id="edit_product_subtotal">
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-6">
                            <table class="table table-bordered ">
                                <tbody id="product_order_detail">

                                </tbody>
                            </table>
                        </div>

                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>