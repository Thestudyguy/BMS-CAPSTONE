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
          <span class="info-box-number">142</span>
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
        <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>
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
          <span class="info-box-number">93,139</span>
        </div>
      </div>
    </div>
  </div>
  {{-- end of summary cards --}}
  {{-- chart --}}
  <div class="row">
    <div class="col-sm-8">
      <div class="card">
        <h4 class="h6 fw-bold m-3">Clients Chart</h4>
        <div class="card-body">
          <div class="chart">
            <canvas id="stackedBarChart" style="height: 50px;"></canvas>
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
        <h4 class="h6 fw-bold m-3">Transaction History</h4>
        </div>
        <div class="card-body">
          <div class="chart">
            <canvas id="stackedBarChart" style="height: 50px;"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header text-sm fw-bold">Payment Status</div>
        <div class="card-body">
        </div>
      </div>
    </div>
   </div>
    {{--end of transaction history --}}
</div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var ctx = document.getElementById('stackedBarChart').getContext('2d');
      var stackedBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['January', 'February', 'March', 'April', 'May', 'June'],
          datasets: [
            {
              label: 'Dataset 1',
              backgroundColor: 'rgba(60,141,188,0.9)',
              borderColor: 'rgba(60,141,188,0.8)',
              data: [65, 59, 80, 81, 56, 55]
            },
            {
              label: 'Dataset 2',
              backgroundColor: 'rgba(210, 214, 222, 1)',
              borderColor: 'rgba(210, 214, 222, 1)',
              data: [28, 48, 40, 19, 86, 27]
            }
          ]
        },
        options: {
          responsive: true,
          animation: {
            duration: 1000, // Set the duration of the animation
            easing: 'easeOutQuart' // You can choose different easing options
          },
          scales: {
            x: {
              stacked: true
            },
            y: {
              stacked: true
            }
          }
        }
      });
    });
  </script>
  
@endsection
