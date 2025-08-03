@props(['payments/details'])
<div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-labelledby="paymentDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered h-100">
        <div class="modal-content h-75">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="paymentDetailsModal">Payment Detail</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body overflow-scroll">
                <div class="col">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="payment-image-container">
                                <img src="" class="img-fluid rounded" alt="payment Image" id="payment-image">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <h4 class="text-warning" id="payment-name"><i class='bx bxs-category'></i>
                                Premium
                                Payment</h4>
                            <p id="payment-count">Count</p>
                            <p id="payment-email">email</p>
                            <p id="payment-description"></p>
                        </div>
                    </div>
                    <table class="table table-striped table-hover mt-20 overflow-scroll">
                        <thead class="bg-warning">
                            <tr>
                                <th scope="col" class='text-center'>Price</th>
                                <th scope="col" class='text-center'>Deadline</th>
                                <th scope="col" class='text-center'>Description</th>
                                <th scope="col" class='text-center'>Date Time</th>
                            </tr>
                        </thead>
                        <tbody id="payment-table-body">
                            <!-- Example row, replace with dynamic content -->
                            {{-- @can('Payment access') --}}
                            @foreach ($payments as $payment)
                                @php
                                    // $fixer = $users->where('id',$payment->user_id);
                                    $user = $users->where('id', $payment->user_id);

                                @endphp
                                <tr>
                                    {{-- <td class='text-center'>{{ $fixer->name }}</td> --}}
                                    {{-- <td class='text-center'>{{ $payment->price }}$</td>
                                                <td class='text-center'>{{ $payment->deadline }}</td>
                                                <td class='text-center'>{{ $payment->description }}</td>
                                                <td class='text-center'>{{ $payment->created_at }}</td> --}}
                                </tr>
                            @endforeach
                            {{-- @endcan --}}
                            <!-- Add more rows here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
