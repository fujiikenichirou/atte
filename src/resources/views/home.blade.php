@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
  @if(session('success'))
    <div class="attendance__alert--success">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="attendance__alert--danger">
      {{ session('error') }}
    </div>
  @endif
</div>

<div class="attendance__comment">
  {{ auth()->user()->name }}さんお疲れ様です！
</div>

<div class="attendance__content">
  <form class="attendance__button" action="{{ route('work.store') }}" method="post">
    @csrf
    <div class="attendance__panel__top">
      <button class="attendance__button-submit" type="submit" {{ $canStartShift ? '' : 'disabled' }} name="action" value="start">勤務開始</button>
      <button class="attendance__button-submit" type="submit" {{ $canEndShift ? '' : 'disabled' }} name="action" value="end">勤務終了</button>
    </div>
    <div class="attendance__panel__bottom">
      <button class="attendance__button-submit" type="submit" {{ $canStartBreak ? '' : 'disabled' }} name="action" value="breaking_start">休憩開始</button>
      <button class="attendance__button-submit" type="submit" {{ $canEndBreak ? '' : 'disabled' }} name="action" value="breaking_end">休憩終了</button>
    </div>
  </form>
</div>
@endsection