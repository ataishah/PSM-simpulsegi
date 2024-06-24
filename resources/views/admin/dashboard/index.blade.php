@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>STOCK MANAGEMENT WEB-BASED SYSTEM</h1>
        </div>
        <div class="row">
            @php
                $cards = [
                    [
                        'title' => 'Todays Orders',
                        'icon' => 'fa-cart-plus',
                        'value' => $todaysOrders,
                        'bg' => 'bg-primary',
                    ],
                    [
                        'title' => 'Todays Earnings',
                        'icon' => 'fa-dollar-sign',
                        'value' => currencyPosition($todaysEarnings),
                        'bg' => 'bg-danger',
                    ],
                    [
                        'title' => 'This Month Orders',
                        'icon' => 'fa-cart-plus',
                        'value' => $thisMonthsOrders,
                        'bg' => 'bg-primary',
                    ],
                    [
                        'title' => 'This Months Earnings',
                        'icon' => 'fa-dollar-sign',
                        'value' => currencyPosition($thisMonthsEarnings),
                        'bg' => 'bg-danger',
                    ],
                    [
                        'title' => 'This Year Orders',
                        'icon' => 'fa-cart-plus',
                        'value' => $thisYearOrders,
                        'bg' => 'bg-primary',
                    ],
                    [
                        'title' => 'This Year Earnings',
                        'icon' => 'fa-dollar-sign',
                        'value' => currencyPosition($thisYearEarnings),
                        'bg' => 'bg-danger',
                    ],
                    [
                        'title' => 'Total Orders',
                        'icon' => 'fa-cart-plus',
                        'value' => $totalOrders,
                        'bg' => 'bg-primary',
                    ],
                    [
                        'title' => 'Total Earnings',
                        'icon' => 'fa-dollar-sign',
                        'value' => currencyPosition($totalEarnings),
                        'bg' => 'bg-danger',
                    ],
                    ['title' => 'Total Users', 'icon' => 'fa-users', 'value' => $totalUsers, 'bg' => 'bg-primary'],
                    [
                        'title' => 'Total Admins',
                        'icon' => 'fa-user-shield',
                        'value' => $totalAdmins,
                        'bg' => 'bg-danger',
                    ],
                    ['title' => 'Total Products', 'icon' => 'fa-th', 'value' => $totalProducts, 'bg' => 'bg-primary'],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon {{ $card['bg'] }}">
                            <i class="fas {{ $card['icon'] }}"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ $card['title'] }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $card['value'] }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <section class="section">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>High Selling Product</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="highSalesChart" width="400" height="225"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Sales Performance Over Time</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="salesPerformanceChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="card card-primary">
            <div class="card-header">
                <h4>Todays Orders</h4>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="order_modal" tabindex="-1" role="dialog" aria-labelledby="order_modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" class="order_status_form">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="">Payment Status</label>
                            <select class="form-control payment_status" name="payment_status" id="">
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Order Status</label>
                            <select class="form
                            <select class="form-control order_status"
                                name="order_status" id="">
                                <option value="pending">Pending</option>
                                <option value="in_process">In Process</option>
                                <option value="delivered">Delivered</option>
                                <option value="declined">Declined</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary submit_btn">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function() {
            var orderId = '';

            $(document).on('click', '.order_status_btn', function() {
                let id = $(this).data('id');

                orderId = id;

                let paymentStatus = $('.payment_status option');
                let orderStatus = $('.order_status option');

                $.ajax({
                    method: 'GET',
                    url: '{{ route('admin.orders.status', ':id') }}'.replace(":id", id),
                    beforeSend: function() {
                        $('.submit_btn').prop('disabled', true);
                    },
                    success: function(response) {
                        paymentStatus.each(function() {
                            if ($(this).val() == response.payment_status) {
                                $(this).attr('selected', 'selected');
                            }
                        })

                        orderStatus.each(function() {
                            if ($(this).val() == response.order_status) {
                                $(this).attr('selected', 'selected');
                            }
                        })

                        $('.submit_btn').prop('disabled', false);
                    },
                    error: function(xhr, status, error) {

                    }
                })
            })

            $('.order_status_form').on('submit', function(e) {
                e.preventDefault();
                let formContent = $(this).serialize();
                $.ajax({
                    method: 'POST',
                    url: '{{ route('admin.orders.status-update', ':id') }}'.replace(":id", orderId),
                    data: formContent,
                    success: function(response) {
                        $('#order_modal').modal("hide");
                        $('#order-table').DataTable().draw();

                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        toastr.error(xhr.responseJSON.message);
                    }
                })
            })
        })
        $(document).ready(function() {
            console.log("Chart.js loaded successfully!");

            var productSales = {!! json_encode($productSales) !!};
            console.log(productSales);

            productSales.sort((a, b) => b.sales - a.sales);

            var topProducts = productSales.slice(0, 5);

            var labels = topProducts.map(product => product.product_name);
            var data = topProducts.map(product => product.sales);

            var colors = ['#FF5733', '#FFC300', '#36A2EB', '#4BC0C0', '#9966FF'];

            var salesData = {
                labels: labels,
                datasets: [{
                    label: 'High Selling Products',
                    data: data,
                    backgroundColor: colors,
                    borderWidth: 1
                }]
            };

            var ctx = document.getElementById('highSalesChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: salesData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutoutPercentage: 50, // Adjust the size of the center hole (optional)
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                fontSize: 10
                            }
                        }
                    },

                    layout: {
                        padding: {
                            left: 10,
                            right: 10,
                            top: 10,
                            bottom: 10
                        }
                    }
                }
            });
        });

        // Sales Performance Over Time chart
        var salesPerformanceData = {!! json_encode($salesData) !!};

        var ctx2 = document.getElementById('salesPerformanceChart').getContext('2d');
        var salesPerformanceChart = new Chart(ctx2, {
            type: 'line',
            data: salesPerformanceData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        display: true,
                        title: {
                            display: true,
                            text: 'Total Sales (RM)'
                        }
                    },
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true
                    },
                }
            }
        });
    </script>
@endpush
