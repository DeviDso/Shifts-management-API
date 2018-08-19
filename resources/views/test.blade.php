
@php
  setlocale(LC_TIME, 'lt_LT.UTF-8');
@endphp
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>{{ date('Y-M-D') }}</title>
    <style media="screen">
      *{ font-family: DejaVu Sans !important;}
      h2{
        text-align: right;
        margin-right: 75px;
        margin-top: 25px;
      }
      h1{
        font-size: 15px;
      }
      h2{
        font-size: 12px;
      }
      h3{
        font-size: 10px;
      }
      h1, h3{
        text-align: center
      }
      table{
        width: 100%;
        border-collapse: collapse;
      }
      table th{
        font-size: 6px;
      }
      table,th, td{
        border: solid 1px #333;
      }
      table td{
        font-size: 5px;
        text-align: center;
        padding: 0;
      }
      label{
        width: 100%;
        display: inline-block;
      }
      .work{
        background: #fff;
        color: #333;
      }
      .break{
        background: #AAAAAA;
        color: #333;
      }
      .vacation{
        background: #FF851B;
        color: #fff;
      }
      .employee{
        font-size: 6px;
      }
      tr.heading{
        background: #DDDDDD;
      }
    </style>
    <style>

</style>

  </head>
  @php
    $timezone = 'America/Los_Angeles';
    $current = \Carbon\Carbon::parse('2018-08-01', $timezone);
    $daysInMonth = $current->daysInMonth;


    $monthStart = \Carbon\Carbon::create($current->startOfMonth()->year, $current->startOfMonth()->month, $current->startOfMonth()->day, $current->startOfMonth()->hour);
    $monthEnd = \Carbon\Carbon::create($current->endOfMonth()->year, $current->endOfMonth()->month, $current->endOfMonth()->day, $current->endOfMonth()->hour);

    $days = [];

    foreach($schedule->data as $data){
      $start = \Carbon\Carbon::parse($data->start, $timezone);

      if(\Carbon\Carbon::create($start->year, $start->month, $start->day, $start->hour)->between($monthStart, $monthEnd)){
        array_push($days, $data);
      }
    }
  @endphp
  <body>
    <h2>Patvirtinta</h2>
    <h1>Senolių namai</h1>
    <h3>Darbo laiko grafikas</h3>
    <table>
      <tr class="heading">
        <th>Darbuotojas(-a)</th>
        @for($i = 1; $i <= $daysInMonth; $i++)
          <th>{{ $i }}</th>
        @endfor
        {{-- <th>Paprastos</th>
        <th>Naktinės</th> --}}
      </tr>
      <tr>
        <td><label class="employee">Petras</label></td>
        @php
          for($i = 1; $i <= $daysInMonth; $i++){
            echo '<td>';
            foreach($days as $day){
              $startFormated = \Carbon\Carbon::parse($day->start, $timezone);
              $endFormated = \Carbon\Carbon::parse($day->end, $timezone);

              // echo $day->type;

              if($i == $startFormated->day && $i == $endFormated->day){
                if($day->type == 'work'){
                  echo '<label class="work">' . $startFormated->hour . ':' . $startFormated->minute . '-' . $endFormated->hour . ':' . $endFormated->minute . '0</label><br>';
                } else if ($day->type == 'break'){
                  echo '<label class="break">' . $startFormated->hour . ':' . $startFormated->minute . '-' . $endFormated->hour . ':' . $endFormated->minute . '0</label><br>';
                }
              } else if($i == $startFormated->day &&  $i != $endFormated->day){
                if($day->type == 'vacation'){
                  echo '<label class="vacation">A</label><br>';
                }
              }
            }
            echo '</td>';
          }
        @endphp
        {{-- <td></td>
        <td></td> --}}
      </tr>
    </table>
  </body>
</html>
