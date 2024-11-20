@extends('layout')
@section('content')
<div class="container-fluid p-5">
  <h1 class="fw-bold pt-2">Dashboard</h1>
  {{-- summary cards --}}
  <div class="row">
    <div class="col-md-3 col-sm-6 col-12">
      <div class="info-box">
        <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
        <div class="info-box-content">
          <span class="info-box-text lead">Clients</span>
          <span class="info-box-number">{{$clientCount}}</span>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
      <div class="info-box">
        <span class="info-box-icon bg-success"><i class="fas fa-edit"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Bookkeping Sales</span>
          <span class="info-box-number">410</span>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
      <div class="info-box">
        <span class="info-box-icon bg-warning"><i class="far fa-copy text-light"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">External Services Sales</span>
          <span class="info-box-number">13,648</span>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
      <div class="info-box">
        <span class="info-box-icon bg-danger"><i class="ion ion-stats-bars"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Sales</span>
          <div class="infor-box-number">{{number_format($totalSales)}}</div>
        </div>
      </div>
    </div>
  </div>
  {{-- end of summary cards --}}
  {{-- chart --}}
  <div class="row">
    <div class="col-sm-8">
      <div class="card">
        <div class="card-header">
          <h4 class="h6 fw-bold m-3">Sales Chart</h4>
        </div>
        <div class="card-body">
          <div class="chart">
            <canvas id="lineChart" width="400" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header text-sm fw-bold">Recently Added Clients</div>
        <div class="card-body">
        </div>
      </div>
    </div>
   </div>
  {{--end of chart --}}

  {{-- transaction history --}}
  <div class="row">
    <div class="col-sm-8">
      <div class="card">
        <div class="card-header">
        <h4 class="h6 fw-bold m-3">Recent Activity</h4>
        </div>
        <div class="card-body">
          <div class="chart">
            {{-- <canvas id="lineChart" width="400" height="200"></canvas> --}}

          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header text-sm fw-bold">Payment Status</div>
        <div class="card-body">
          <table class="table table-striped">
            <tbody>
                @foreach($clientPaymentStatus as $client)
                    <tr>
                        <td>
                            @if($client->image_path)
                                <img src="{{ asset('storage/' . $client->image_path) }}" alt="Company Profile Image" width="50" style="border-radius: 50%;">
                            @else
                                <p>No profile image available</p>
                            @endif
                        </td>
                        <td class="text-center fw-bold" style="color: #063D58;">{{$client->CompanyName}}</td>
                        <td><span class="badge text-sm bg-warning fw-bold text-light">Pending</span></td>
                      </tr>
                @endforeach
            </tbody>
        </table>
        </div>
      </div>
    </div>
   </div>
    {{--end of transaction history --}}
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var monthlySales = @json($monthlySales);

    var ctx = document.getElementById('lineChart').getContext('2d');
    var lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                'January', 'February', 'March', 'April', 'May', 'June', 
                'July', 'August', 'September', 'October', 'November', 'December'
            ],
            datasets: [
                {
                    label: 'Sales',
                    data: monthlySales,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Income',
                    data: monthlySales,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Expense',
                    data: monthlySales,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Months'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Sales (Price + Requirement Price)'
                    },
                    beginAtZero: true
                }
            }
        }
    });
});

</script>

  
@endsection
