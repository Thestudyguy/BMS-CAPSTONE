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
          <span class="info-box-text">Income</span>
          <span class="info-box-number">{{number_format($income, 2)}}</span>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
      <div class="info-box">
        <span class="info-box-icon bg-warning"><i class="far fa-copy text-light"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Expenses</span>
          <span class="info-box-number">{{number_format($expenses, 2)}}</span>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
      <div class="info-box">
        <span class="info-box-icon bg-danger"><i class="ion ion-stats-bars"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Sales</span>
          <div class="infor-box-number">{{number_format($totalSales + $income, 2)}}</div>
        </div>
      </div>
    </div>
  </div>
  {{-- end of summary cards --}}
  {{-- chart --}}
  <div class="row">
    <div class="col-sm-6">
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
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h4 class="h6 fw-bold m-3">Clients Chart</h4>
        </div>
        <div class="card-body">
          <div class="client-chart">
            <canvas id="client-lineChart" width="400" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h4 class="h6 fw-bold m-3">Expense Chart</h4>
        </div>
        <div class="card-body">
          <div class="expense-chart">
            <canvas id="expense-lineChart" width="400" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h4 class="h6 fw-bold m-3">Income Chart</h4>
        </div>
        <div class="card-body">
          <div class="income-chart">
            <canvas id="income-lineChart" width="400" height="200"></canvas>
          </div>
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
          <div style="overflow-x: auto; max-height: 400px;">
            <table class="table table-striped" style="font-size: 0.8em; table-layout: fixed; word-wrap: break-word;">
              <thead>
                <tr>
                  <th style="width: 15%;">User</th>
                  <th style="width: 20%;">User Agent</th>
                  <th style="width: 10%;">Activity Type</th>
                  <th style="width: 20%;">Activity Description</th>
                  <th style="width: 15%;">Action</th>
                  <th style="width: 10%;">Time Stamps</th>
                  <th style="width: 5%;">Browser</th>
                  <th style="width: 5%;">Platform</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($activityLog as $log)
                <tr>
                  <td>{{$log->LastName}}, {{$log->FirstName}} - {{$log->Role}}</td>
                  <td>{{$log->user_agent}}</td>
                  <td>{{$log->action}}</td>
                  <td>{{$log->activity}}</td>
                  <td>{{$log->description}}</td>
                  <td>{{$log->created_at}}</td>
                  <td>{{$log->browser}}</td>
                  <td>{{$log->platform}}/{{$log->platform_version}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header text-sm fw-bold">Payment Status</div>
        <div class="card-body">
          <div style="overflow-x: auto; max-height: 400px;">
            <table class="table">
            <tbody>
              @foreach($clientPaymentStatus as $client)
                  <tr style="font-size: 0.8em">
                      <td>
                        {{$client->ClientService}}
                          {{-- @if($client->image_path)
                              <img src="{{ asset('storage/' . $client->image_path) }}" alt="Company Profile Image" width="50" style="border-radius: 50%;">
                          @else
                              <p>No profile image available</p>
                          @endif --}}
                      </td>
                      <td class="text-center fw-bold" style="color: #063D58;">{{$client->CompanyName}}</td>
                      <td><span class="badge bg-warning fw-bold text-light" style="font-size: 0.8em">Pending</span></td>
                    </tr>
              @endforeach
          </tbody>
          </table>
          </div>
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
      var incomeMonthly = @json($monthlyIncome);
      var expensesMonthly = @json($monthlyExpenses); 
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
                  // {
                  //     label: 'Income',
                  //     data: incomeMonthly,
                  //     borderColor: 'rgb(253, 193, 7)',
                  //     backgroundColor: 'rgba(253, 193, 7, 0.1)',
                  //     fill: true,
                  //     tension: 0.4
                  // },
                  // {
                  //     label: 'Expenses',
                  //     data: expensesMonthly,
                  //     borderColor: 'rgb(54, 162, 235)',
                  //     backgroundColor: 'rgba(54, 162, 235, 0.2)',
                  //     fill: true,
                  //     tension: 0.4
                  // }
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
                          text: 'Amount (Sales, Income, Expenses)'
                      },
                      beginAtZero: true
                  }
              }
          }
      });
      var ctx = document.getElementById('income-lineChart').getContext('2d');
      var lineChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: [
                  'January', 'February', 'March', 'April', 'May', 'June', 
                  'July', 'August', 'September', 'October', 'November', 'December'
              ],
              datasets: [
                  {
                      label: 'Income',
                      data: incomeMonthly,
                      borderColor: 'rgb(253, 193, 7)',
                      backgroundColor: 'rgba(253, 193, 7)',
                      fill: true,
                      tension: 0.4
                  },
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
                          text: 'Income'
                      },
                      beginAtZero: true
                  }
              }
          }
      });
      var ctx = document.getElementById('expense-lineChart').getContext('2d');
var lineChart = new Chart(ctx, {
    type: 'pie', // Change to 'pie'
    data: {
        labels: [
            'January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'
        ],
        datasets: [
            {
                label: 'Expense',
                data: expensesMonthly, // Data for each month
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',  // January
                    'rgba(54, 162, 235, 0.6)',  // February
                    'rgba(255, 206, 86, 0.6)',  // March
                    'rgba(75, 192, 192, 0.6)',  // April
                    'rgba(153, 82, 255)', // May
                    'rgba(255, 159, 64, 0.6)',  // June
                    'rgba(199, 199, 199, 0.6)', // July
                    'rgba(255, 99, 71, 0.6)',   // August
                    'rgba(0, 206, 209, 0.6)',  // September
                    'rgba(255, 140, 0, 0.6)',  // October
                    'rgba(154, 205, 50, 0.6)', // November
                    'rgba(123, 104, 238, 0.6)' // December
                ],
                borderColor: 'rgba(255, 255, 255, 1)', // White border between segments
                borderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top', // Position of the legend
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return ` ${tooltipItem.label}: ${tooltipItem.raw}`;
                    }
                }
            }
        }
    }
});

var clientsMonthly = @json($monthlyClients);
var ctx = document.getElementById('client-lineChart').getContext('2d');
var clientsChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ],
        datasets: [
            {
                label: 'New Clients',
                data: clientsMonthly,
                backgroundColor: [
                    'rgba(255, 99, 132)',   // January
                    'rgba(54, 162, 235)',   // February
                    'rgba(255, 206, 86)',   // March
                    'rgba(75, 192, 192)',   // April
                    'rgba(153, 102, 255)',  // May
                    'rgba(255, 159, 64)',   // June
                    'rgba(255, 99, 132, 0.6)',   // July
                    'rgba(54, 162, 235, 0.6)',   // August
                    'rgba(255, 206, 86, 0.6)',   // September
                    'rgba(75, 192, 192, 0.6)',   // October
                    'rgba(153, 102, 255, 0.6)',  // November
                    'rgba(255, 159, 64, 0.6)'    // December
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',   // January
                    'rgba(54, 162, 235, 1)',   // February
                    'rgba(255, 206, 86, 1)',   // March
                    'rgba(75, 192, 192, 1)',   // April
                    'rgba(153, 102, 255, 1)',  // May
                    'rgba(255, 159, 64, 1)',   // June
                    'rgba(255, 99, 132, 1)',   // July
                    'rgba(54, 162, 235, 1)',   // August
                    'rgba(255, 206, 86, 1)',   // September
                    'rgba(75, 192, 192, 1)',   // October
                    'rgba(153, 102, 255, 1)',  // November
                    'rgba(255, 159, 64, 1)'    // December
                ],
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true
    }
});

  });
</script>

  
@endsection
