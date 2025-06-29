{{-- Dashboard --}}

@extends('vendor.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>

        <div class="section-body">
            <div class="row">
                {{-- Revenue Over Time --}}
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Revenue Over Time</h5>
                            <p class="card-text text-muted mb-2">
                                Track your monthly revenue trends. Use this to identify growth periods and plan for seasonal
                                changes.
                            </p>
                            <div class="mb-3">
                                <span class="badge bg-success text-white">Total Revenue: ${{ $totalRevenue }}</span>
                                <span class="badge bg-info text-white">Highest: ${{ $highestRevenue }}
                                    ({{ $highestMonth }})</span>
                                <span class="badge bg-warning text-white">Lowest: ${{ $lowestRevenue }}
                                    ({{ $lowestMonth }})</span>
                            </div>
                            <div style="height: 300px;">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
            <div class="row">
                {{-- Number of Orders Over Time --}}
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Number of Orders Over Time</h5>
                            <p class="card-text text-muted mb-2">
                                Monitor how many orders you receive each month. Spot trends and evaluate the impact of
                                promotions or campaigns.
                            </p>
                            <div class="mb-3">
                                <span class="badge bg-primary text-white">Total Orders: {{ $totalOrders }}</span>
                                <span class="badge bg-info text-white">Peak: {{ $peakOrders }}
                                    ({{ $peakMonth }})</span>
                                <span class="badge bg-secondary text-white">Avg/Month: {{ $averageOrders }}</span>
                            </div>
                            <div style="height: 300px;">
                                <canvas id="ordersChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                {{-- Top Selling Products --}}
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Top Selling Products</h5>
                            <p class="card-text text-muted mb-2">
                                See which products are driving your sales. Focus your marketing and inventory on these
                                bestsellers.
                            </p>
                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Product A <span class="badge bg-success rounded-pill text-white">120 sold</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Product B <span class="badge bg-info rounded-pill text-white">95 sold</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Product C <span class="badge bg-warning rounded-pill text-white">80 sold</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Product D <span class="badge bg-secondary rounded-pill text-white">60 sold</span>
                                </li>
                            </ul>
                            <div style="height: 300px;">
                                <canvas id="topProductsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="row">
                {{-- Order Status Breakdown --}}
                <div class="col-12 ">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Order Status Breakdown</h5>
                            <p class="card-text text-muted mb-2">
                                Review the distribution of your order statuses. Quickly spot issues with pending, cancelled,
                                or refunded orders.
                            </p>
                            <div class="mb-3">
                                <span class="badge bg-success text-white">Completed: 120</span>
                                <span class="badge bg-warning text-white">Pending: 30</span>
                                <span class="badge bg-danger text-white">Cancelled: 10</span>
                                <span class="badge bg-info text-white">Refunded: 5</span>
                            </div>
                            <div style="height: 300px;">
                                <canvas id="orderStatusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // 1. Revenue Over Time (Line Chart)
        const revenueCtx = document.getElementById('revenueChart');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json(array_keys($revenueData)),
                datasets: [{
                    label: 'Revenue ($)',
                    data: @json(array_values($revenueData)),
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // 2. Number of Orders Over Time (Bar Chart)
        const ordersCtx = document.getElementById('ordersChart');
        new Chart(ordersCtx, {
            type: 'bar',
            data: {
                labels: @json(array_keys($ordersData)),
                datasets: [{
                    label: 'Orders',
                    data: @json(array_values($ordersData)),
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // 3. Top Selling Products (Horizontal Bar Chart)
        const topProductsCtx = document.getElementById('topProductsChart');
        new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: ['Product A', 'Product B', 'Product C', 'Product D'],
                datasets: [{
                    label: 'Units Sold',
                    data: [120, 95, 80, 60],
                    backgroundColor: [
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });

        // 4. Order Status Breakdown (Pie Chart)
        const orderStatusCtx = document.getElementById('orderStatusChart');
        new Chart(orderStatusCtx, {
            type: 'pie',
            data: {
                labels: ['Completed', 'Pending', 'Cancelled', 'Refunded'],
                datasets: [{
                    label: 'Orders',
                    data: [120, 30, 10, 5],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(220, 53, 69, 0.7)',
                        'rgba(23, 162, 184, 0.7)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(23, 162, 184, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
@endpush
