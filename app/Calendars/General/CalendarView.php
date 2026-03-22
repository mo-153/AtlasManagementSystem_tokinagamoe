<?php
namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView{

  private $carbon;
  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  function render(){
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th>土</th>';
    $html[] = '<th>日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();
    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';
      $days = $week->getDays();
      foreach($days as $day){
        $startDay = $this->carbon->copy()->format("Y-m-01");
        $toDay = $this->carbon->copy()->format("Y-m-d");
        $past_day = $day->everyDay()<Carbon::now()->format("Y-m-d");

        // ↓ここで過去日か確認している
        if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){

          // ↓過去日をグレーアウトにする
            $html[] = '<td class="past-day border">';
            }else{
              $html[] = '<td class="border '.$day->getClassName().'">';
            }
              $html[] = $day->render();



              // ↓予約があるかどうか
              // 過去日、未来日に予約がされている場合、リモ1/2/3を表示させる
              if(in_array($day->everyDay(), $day->authReserveDay())){
                $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
                if($reservePart == 1){
                  $reservePart = "リモ1部";
                  }else if($reservePart == 2){
                    $reservePart = "リモ2部";
                    }else if($reservePart == 3){
                      $reservePart = "リモ3部";
                      }

                      // ↓予約がされているかと過去日か確認している
                      if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
                        $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">'. $reservePart .'</p>';
                        // ↑'.変数名.'で記述するルールらしい
                        }else{
                      $html[] = '<button type="button" class="btn btn-danger p-0 w-75 cancel-modal-open" style="font-size:12px" ' .
                      'reserve_date="'. $day->everyDay() . '" ' .
                      'reserve_time="'. $reservePart . '" ' .
                      'reserve_id="' . $day->authReserveDate($day->everyDay())->first()->pivot->id . '"> ' .
                      $reservePart .'</button>';
                        }
                      $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
                      }else{
                        // ↓過去日に予約がないとき
                        if($startDay <= $day->everyDay() && $toDay >= $day ->everyDay()){
                          $html[] = '<p class = "m-auto p- w-75" style="font-size:12px"> 受付終了 </p>';
                      }else{
                        $html[] = $day->selectPart($day->everyDay());
                        }
                      }
                        $html[] = $day->getDate();
                        $html[] = '</td>';
                        }
                        $html[] = '</tr>';
                        }
                        $html[] = '</tbody>';
                        $html[] = '</table>';
                        $html[] = '</div>';
                        $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">'.csrf_field().'</form>';
                        $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'.csrf_field().'</form>';
    return implode('', $html);
  }

  protected function getWeeks(){
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while($tmpDay->lte($lastDay)){
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
