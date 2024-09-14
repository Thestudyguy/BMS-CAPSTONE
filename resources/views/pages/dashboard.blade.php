@extends('layout')
@section('content')
<div class="chart">
    <canvas id="stackedBarChart"></canvas>
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
