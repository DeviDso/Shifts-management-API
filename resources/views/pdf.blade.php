
@php
  setlocale(LC_TIME, 'lt_LT.UTF-8');
@endphp
<!DOCTYPE html>
<html lang="lt" dir="ltr">
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
        text-align: center;
      }
      table,th, td{
        border: solid 1px #333;
      }
      table td{
        font-size: 4.5px;
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
        background: #cecece;
        color: #333;
        margin-top: 1.5px;
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
    $current = \Carbon\Carbon::parse($selectedMonth, $timezone);
    $daysInMonth = $current->daysInMonth;

    $nightStarts = $settingNightStarts;
    $nightEnds = $settingNightEnds;

    $monthStart = \Carbon\Carbon::create($current->startOfMonth()->year, $current->startOfMonth()->month, $current->startOfMonth()->day, $current->startOfMonth()->hour);
    $monthEnd = \Carbon\Carbon::create($current->endOfMonth()->year, $current->endOfMonth()->month, $current->endOfMonth()->day, $current->endOfMonth()->hour);

    $extDay = false;
    $extDayData = [];
  @endphp
  <body>
    <h2>Patvirtinta</h2>
    <h1>Senolių namai</h1>
    <h3>Darbo laiko grafikas</h3>
    <h3>{{ $current->format('Y-m') }}</h3>
    <table>
      <tr class="heading">
        <th width="8%">Darbuotojas(-a)</th>
        @for($i = 1; $i <= $daysInMonth; $i++)
          <th>{{ $i }}</th>
        @endfor
        <th width="5%"></th>
        {{-- <th>Paprastos</th>
        <th>Naktinės</th> --}}
        {{-- <th>Šventinės</th> --}}
      </tr>
      @foreach ($employees as $key => $employee)
        @php
        $days = [];
        $nightMinutes = 0;
        $holidayMinutes = 0;
        $workMinutes = 0;
        $totalVacations = 0;
        // print_r($employee->schedule)
        foreach($employee->schedule->data as $data){
          $start = \Carbon\Carbon::parse($data->start, $timezone);

          if(\Carbon\Carbon::create($start->year, $start->month, $start->day, $start->hour)->between($monthStart, $monthEnd)){
            array_push($days, $data);
          }
        }
        @endphp
        <tr>
          <td>
            <label class="employee">{{ $employee->name . ' ' . $employee->surname }}</label>
            ({{ $employee->position->name }})
            {{-- <div style="width: 100%; height: 0.5px; background: #998; display: block"></div> --}}

          </td>
          @php
            for($i = 1; $i <= $daysInMonth; $i++){
              echo '<td>';
              if($extDay){
                echo '<label class="work">00:00-' . substr($endFormated, 11, 5) . '</label><br>';
                $extDay = false;
              }
              foreach($days as $index => $day){
                $startFormated = \Carbon\Carbon::parse($day->start, $timezone);
                $endFormated = \Carbon\Carbon::parse($day->end, $timezone);

                $nightFormatS = $startFormated->year . '-' . $startFormated->month . '-' . $startFormated->day . ' ' . $nightStarts;
                $nightFormatM = $startFormated->year . '-' . $startFormated->month . '-' . $startFormated->day . ' ' . '24:00';
                $nightFormatE = $endFormated->year . '-' . $endFormated->month . '-' . $endFormated->day . ' ' . $nightEnds;

                $nightStartsFormated = \Carbon\Carbon::parse($nightFormatS, $timezone);
                $midnight = \Carbon\Carbon::parse($nightFormatM, $timezone);
                $nightEndsFormated = \Carbon\Carbon::parse($nightFormatE, $timezone);

                if($i == $startFormated->day && $i == $endFormated->day){
                  if($day->type == 'work'){
                    $workMinutes += $startFormated->diffInMinutes($endFormated);

                    //If shift starts at night might cause problem
                    if($nightStartsFormated <= $endFormated){
                      if($nightStartsFormated <= $startFormated){
                        $nightMinutes += $nightStartsFormated->diffInMinutes($endFormated);
                      } else{
                        $nightMinutes += $nightStartsFormated->diffInMinutes($endFormated);
                      }
                    }
                    echo '<label class="work">' . substr($startFormated, 11, 5) . '-' . substr($endFormated, 11, 5) . '</label><br>';

                  } else if ($day->type == 'break'){
                    $workMinutes -= $startFormated->diffInMinutes($endFormated);
                    echo '<label class="break">' . substr($startFormated, 11, 5) . '-' . substr($endFormated, 11, 5) . '</label><br>';
                  }
                } else if($i == $startFormated->day &&  $i != $endFormated->day){
                  if($day->type == 'vacation'){
                    echo '<label class="vacation">A</label><br>';
                    $totalVacations++;
                  }
                  if($day->type == 'work'){
                    $workMinutes += $startFormated->diffInMinutes($endFormated);
                    //Night hours
                    if($nightEndsFormated <= $endFormated){
                      $nightMinutes += $nightStartsFormated->diffInMinutes($nightEndsFormated);
                    } else{
                      $nightMinutes += $nightStartsFormated->diffInMinutes($endFormated);
                    }

                    echo '<label class="work">' . substr($startFormated, 11, 5) . '-' . '00:00</label><br>';
                    array_push($extDayData, $day);
                    $extDay = true;
                  }  else if ($day->type == 'break'){
                    $workMinutes -= $startFormated->diffInMinutes($endFormated);
                    echo '<label class="break">' . substr($startFormated, 11, 5) . '-' . substr($endFormated, 11, 5) . '</label><br>';
                  }
                }
                foreach($holidays as $holiday){
                  $holidayStart = \Carbon\Carbon::parse($holiday . ' 00:00', $timezone);
                  $holidayEnd = \Carbon\Carbon::parse($holiday . ' 24:00', $timezone);

                  $dayStart = \Carbon\Carbon::parse($day->start, $timezone);
                  $dayEnd = \Carbon\Carbon::parse($day->end, $timezone);

                  //Counting 1st day if extended
                  if(\Carbon\Carbon::create($dayStart->year, $dayStart->month, $dayStart->day, $dayStart->hour, $dayStart->minute)->between($holidayStart, $holidayEnd)){
                    if($day->type == 'work'){
                      if($i == $dayStart->day && $i == $dayEnd->day){
                        $holidayMinutes += $dayStart->diffInMinutes($dayEnd);
                      } else if($i == $dayStart->day &&  $i != $dayEnd->day){
                        $holidayMinutes += $dayStart->diffInMinutes($holidayEnd);
                      }
                    }
                  }
                  //Counting 2nd day if extended
                  if(\Carbon\Carbon::create($dayEnd->year, $dayEnd->month, $dayEnd->day, $dayEnd->hour, $dayEnd->minute)->between($holidayStart, $holidayEnd)){
                    if($day->type == 'work'){
                      if($i != $dayStart->day && $i == $dayEnd->day){
                        $holidayMinutes += $dayEnd->diffInMinutes($holidayStart);
                      }
                    }
                  }
                }
              }
              echo '</td>';
            }
          @endphp
          <td style="text-align: left; font-size: 4px">
            Pap: {{ floor(($workMinutes - $nightMinutes) / 60) }}h {{ ($workMinutes - $nightMinutes) % 60}}min
            <br>
            Nakt: {{ floor($nightMinutes / 60) }}h {{ $nightMinutes % 60}}min
            <br>
            Šv: {{ floor($holidayMinutes / 60) }}h {{ $holidayMinutes % 60}}min
            <br>
            Viso: {{ floor(($workMinutes) / 60) }}h {{ ($workMinutes) % 60}}min
          </td>
          {{-- <td>{{ floor(($workMinutes - $nightMinutes) / 60) }}h {{ ($workMinutes - $nightMinutes) % 60}}min</td>
          <td>{{ floor($nightMinutes / 60) }}h {{ $nightMinutes % 60 }}min</td> --}}
          {{-- <td>123h 30min</td> --}}
        </tr>
      @endforeach
    </table>
  </body>
</html>
