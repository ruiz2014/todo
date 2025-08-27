@extends('layouts.app')

@section('template_title')
    Panel
@endsection

@push('styles')
    <!-- <link href=" https://cdn.jsdelivr.net/npm/ionicons@7.4.0/dist/collection/components/icon/icon.min.css " rel="stylesheet">  -->
    <!-- <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script> -->
    <script src="https://www.amcharts.com/lib/4/core.js"></script>
    <script src="https://www.amcharts.com/lib/4/charts.js"></script>

@endpush 

@section('content')
    <style>
    #chartdiv {
    width: 100%;
    height: 250px;
    }
    </style>
<section class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if (session('danger'))
        <div class="alert alert-danger">
            {{ session('danger') }}
        </div>
        @endif

        @if (session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
        @endif



  <div class="row">

    <div class="col-md-6">
      @if(Auth::user()->rol == 1)
        <h6>Elija Compania</h6>
        <form method="post" action="{{ route('admin.chc') }}" class="d-flex mb-3"> 
          @csrf
            <select name="company" class="form-select" aria-label="Default select example">
                <option value="">Seleccione Compania</option>
              @foreach($companys as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
              @endforeach
            </select>  
            <button type="submit" class="btn btn-outline-info ms-3">Confirmar</button>
        </form>
      @endif

      @if(Auth::user()->rol <= 2)
       <h6>{{ isset($local->local_name) ? $local->local_name : 'Elija el Local a Observar' }}</h6>
      <form method="post" action="{{ route('admin.chl') }}" class="d-flex"> 
        @csrf
          <select name="local" class="form-select" aria-label="Default select example">
              <option value="">Seleccione el local</option>
            @foreach($locals as $key => $value)
              <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
          </select>  
          <button type="submit" class="btn btn-outline-info ms-3">Confirmar</button>
      </form>
    @else
      <h3 class="mb-3">{{ \App\Helpers\CompanyHelper::getSector()['name'] .':'. $local->local_name }} </h3>
    @endif
    </div>
    
  </div>
 

    <div class="row mt-4 mb-5">

        
        <div class="col-sm-6 col-lg-3">
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row g-0">
                    
                    <div class="col-md-9">
                        <div class="card-body">
                            <h5 class="card-title mb-0 fw-normal">{{ Auth::user()->name }}</h5>
                            <p class="card-text"><small class="text-body-secondary">{{ $rol->name }}</small></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <!-- <div id="chartdiv"></div> -->
              <!-- <div id="chart"></div> -->
               <canvas id="myChart2"></canvas>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="card pt-5 pb-4 px-2">
            <div class="card mb-3" style="background:black;color:white;">
                <div class="row g-0">
                    <div class="col-md-3 d-flex justify-content-center align-items-center">
                        <ion-icon name="ticket-outline" style="background:white;font-size:25px;color:black;border: 1px solid white;padding: 5px;border-radius: 50%;"></ion-icon>
                    </div>
                    <div class="col-md-9">
                        <div class="card-body p-1">
                            <h6 class="card-title mb-0 fw-normal">Atenciones</h6>
                            <p class="card-text fs-6 mb-0">{{ $attentionDay->attentions }}</p>
                            <p class="card-text"><small class="" style="color: #939393;">Total del dia</small></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3" style="background:black;color:white;">
                <div class="row g-0">
                    <div class="col-md-3 d-flex justify-content-center align-items-center">
                        <ion-icon name="wallet-outline" style="background:white;font-size:25px;color:black;border: 1px solid white;padding: 5px;border-radius: 50%;"></ion-icon>
                    </div>
                    <div class="col-md-9">
                        <div class="card-body p-1">
                            <h6 class="card-title mb-0 fw-normal">Dinero</h6>
                            <p class="card-text fs-6 mb-0"> S/. {{ $attentionDay->total }}</p>
                            <p class="card-text"><small class="" style="color: #939393;">Total del dia</small></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3" style="background:black;color:white;">
                <div class="row g-0">
                    <div class="col-md-3 d-flex justify-content-center align-items-center">
                        <ion-icon name="today-outline" style="background:white;font-size:25px;color:black;border: 1px solid white;padding: 5px;border-radius: 50%;"></ion-icon>
                    </div>
                    <div class="col-md-9">
                        <div class="card-body p-1">
                            <h6 class="card-title mb-0 fw-normal">Semana</h6>
                            <p class="card-text fs-6 mb-0"> S/. {{ $attentionWeek->total ?? '0' }}</p>
                            <p class="card-text"><small class="" style="color: #939393;">Total de la Semana</small></p>
                        </div>
                    </div>
                </div>
            </div> 
          </div>      
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="card px-3 py-5">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-lg-5">
          <div class="card">
            <canvas id="myChart5"></canvas>
          </div>
        </div>

        <div class="col-sm-12 col-lg-7">
          <div class="row">
            <div class="col-lg-7">
                  <div class="card py-4 px-3">
                    <h5 class="card-title mb-3 fw-normal">Pagos Realizados en este Mes</h5>
                    <ul class="p-0">
                    @foreach($pays as $pay)
                      <li class="d-flex justify-content-between align-items-start my-3">
                        <div class="ms-2 me-auto d-flex align-items-center">
                            <ion-icon name="{{ $pay->image }}" class="me-2 fs-4" style="font-size:20px;"></ion-icon>
                            <div class="fw-nomal">Pagos por {{ $pay->name }}</div>
                        </div>
                        <span class="badge text-bg-primary rounded-pill">S/. {{ $pay->total}}</span>
                      </li>
                    @endforeach
                    </ul>
                  </div>
                </div>
            
            <div class="col-lg-5">
                <div class="card py-3 px-2">
                  <h6 class="card-title mb-3 fw-normal">Enlace directos</h6>
                  <ul class="p-0">
                      <li class="d-flex justify-content-between align-items-start my-3">
                          <div class="ms-2 me-auto d-flex align-items-center">
                            <ion-icon name="fast-food-outline" class="me-2 fs-4" style="font-size:20px;"></ion-icon>
                            <div class="fw-nomal">Productos</div>
                          </div>
                          <a href="{{ route('products.index') }}">
                            <ion-icon name="enter-outline" class="me-2 fs-4" style="font-size:20px;"></ion-icon> 
                          </a>
                      </li>
                      <li class="d-flex justify-content-between align-items-start my-3">
                          <div class="ms-2 me-auto d-flex align-items-center">
                            <ion-icon name="restaurant-outline" class="me-2 fs-4" style="font-size:20px;"></ion-icon>  
                            <div class="fw-nomal">Clientes</div>
                          </div>
                          <a href="{{ route('customers.index') }}">
                            <ion-icon name="enter-outline" class="me-2 fs-4" style="font-size:20px;"></ion-icon> 
                          </a>
                      </li>
                      <li class="d-flex justify-content-between align-items-start my-3">
                          <div class="ms-2 me-auto d-flex align-items-center">
                            <ion-icon name="people-outline" class="me-2 fs-4" style="font-size:20px;"></ion-icon>
                            <div class="fw-nomal">Usuarios</div>
                          </div>
                          <a href="{{ route('users.index') }}">
                            <ion-icon name="enter-outline" class="me-2 fs-4" style="font-size:20px;"></ion-icon> 
                          </a>
                      </li>
                  </ul>
                </div>
            </div>
          </div>
          
        </div> 
    </div>

    <div class="row">
        <!-- <div class="col-3">
            <div>
                <canvas id="myChart7"></canvas>
            </div>
        </div> -->
        <div class="col-8">
            <!-- <div>
                <canvas id="myChart2"></canvas>
            </div> -->
            <!-- <div id="chart"></div> -->
        </div>
        <div class="col-4">
          <!-- <div id="chartdiv"></div> -->
        </div>
    </div>


</section>                
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script> -->

<script>
    const ctx = document.getElementById('myChart');
    const br = document.getElementById('myChart2');
    const ln = document.getElementById('myChart3');
    
    const br2 = document.getElementById('myChart4');
    var xValues = ['Ticket', 'Factura', 'Boleta'];//["Ticket", "Factura", "Boleta"];
    var yValues = {{ $receipts }};
    var barColors = [
        "rgba(226, 152, 15, 0.6)",
        "rgba(124, 73, 6, 0.4)",
        "rgba(80, 66, 7, 0.2)",
    ];

    new Chart(ctx, {
        type: 'bar',
        data: {
        // labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
          labels: {!! $months !!},
          datasets: [{
              label: 'Ingresos Por Mes',
              // data: [12, 19, 3, 5, 2, 3],
              data: {{ $monthlyCare }},
              borderWidth: 1,
              backgroundColor: ['#ff6c2f']
          }]
        },
        options: {
        scales: {
            y: {
            beginAtZero: true
            }
        }
        }
    });

  new Chart(br, {
    type: 'doughnut',
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
    plugins: {

      // datalabels: {
      //   // display: true,
      //   formatter: (value) => {
      //     return value + '%';
      //   },
      // },
      datalabels: {
        display: true,
        align: 'bottom',
        backgroundColor: '#ccc',
        borderRadius: 3,
        font: {
          size: 18,
        },
      },
      title: {
        display: true,
        text: "Comprobantes Generados"
      },
    },
  }
});

const xValues2 = [50,60,70,80,90,100,110,120,130,140,150];
const yValues2 = [7,8,8,9,9,9,10,11,14,14,15];

// new Chart(ln, {
//   type: "line",
//   data: {
//     labels: xValues2,
//     datasets: [{
//       backgroundColor:"rgba(0,0,255,1.0)",
//       borderColor: "rgba(0,0,255,0.1)",
//       data: yValues2
//     }]
//   },
//   options:{ 
//     legend: {display: false},
//     scales: {
//       yAxes: [{ticks: {min: 6, max:16}}],
//     }
//     }
// });
</script>



<script>
// Create chart instance
var chart = am4core.create("chartdiv", am4charts.PieChart);

// Add data
// chart.data = [{
//     "country": "Ticket",
//     "value": 2
//   },{
//     "country": "Boleta",
//     "value": 1
//   }, {
//     "country": "Factura",
//     "value": 0
//   }  
// ];

// Add and configure Series
  var pieSeries = chart.series.push(new am4charts.PieSeries());
  pieSeries.dataFields.value = "value";
  pieSeries.dataFields.category = "country";
  pieSeries.labels.template.disabled = true;
  pieSeries.ticks.template.disabled = true;

  chart.legend = new am4charts.Legend();
  chart.legend.labels.template.text = "[font-size: 13px] {name}[/]";
  chart.legend.position = "bottom";
  var markerTemplate = chart.legend.markers.template;
  markerTemplate.width = 10;
  markerTemplate.height = 10;

  chart.innerRadius = am4core.percent(50);

  var label = pieSeries.createChild(am4core.Label);
  label.text = "S/. {values.value.sum}";
  label.horizontalCenter = "middle";
  label.verticalCenter = "middle";
  label.fontSize = 20;


  new Chart(document.getElementById("myChart5"), {
    type: 'bar',
    data: {
      // labels: ["Africa", "Asia", "Europe", "Latin America", "North America", "juan bolas", "lucho en gran bolas", "Odin dispara todas", "El pichiche lewa", "balon de playa vinni"],
      labels: {!! $bestSeller !!},
      datasets: [
        {
          label: "Mas Vendido (Cantidad)",
          //backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
          data: {{ $bestSellerQty }},
          backgroundColor: ['#ff6c2f']
        }
      ]
    },
    options: {
      indexAxis: 'y',
      legend: { display: false },
      title: {
        display: true,
        text: 'Predicted world population (millions) in 2050'
      }
    }
});

// 

</script>
@endsection