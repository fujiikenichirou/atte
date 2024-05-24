@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')

<div class="attendance-calendar">
  <a class="calender-button" href="{{ route('attendance.changeDate', ['date' => $previousDate]) }}"><</a>
  <span>{{ $selectedDate }}</span>
  <a class="calender-button" href="{{ route('attendance.changeDate', ['date' => $nextDate]) }}">></a>
</div>

  <div class="attendance-table">
    <table class="attendance-table__inner">
      <tr class="attendance-table__row">
        <th class="attendance-table__header">名前</th>
        <th class="attendance-table__header">勤務開始</th>
        <th class="attendance-table__header">勤務終了</th>
        <th class="attendance-table__header">休憩時間</th>
        <th class="attendance-table__header">勤務時間</th>
      </tr>


      @foreach ($attendances as $attendance)
    <tr class="attendance-table__row">
      <td class="attendance-table__item">{{ $attendance->user->name }}</td>
      <td class="attendance-table__item">{{ $attendance->start_time ? $attendance->start_time->format('H:i:s') : '-' }}</td>
      <td class="attendance-table__item">{{ $attendance->end_time ? $attendance->end_time->format('H:i:s') : '-' }}</td>
      <td class="attendance-table__item">{{ $attendance->total_break_time ? $attendance->total_break_time->format('H:i:s') : '-' }}</td>
      <td class="attendance-table__item">{{ $attendance->actual_work_time ? $attendance->actual_work_time->format('H:i:s') : '-' }}</td>
    </tr>
    @endforeach

    </table>
  </div>

  <!-- ページネーション -->
<div class="pagination-wrapper">
  {{ $attendances->appends(request()->query())->links() }}
</div>

@endsection