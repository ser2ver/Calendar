@extends('layouts.app')

@section('content')
<script type="text/javascript">
function setMonth(year, month) {
    var addr = '{{ route('home') }}';
    year = (year > 0) ? year : year.value;
    month = (month > 0) ? month : month.value;
    addr += '/' + year + '/' + month;
    console.log(addr);
    document.location = addr;
    return false;
}
</script>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @guest
                        Зарегистрируйтесь и начните планировать!
                    @else
                        Всё время - ваше!
                    @endguest
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table-bordered col-md-12">
                        <tr>
                            <td></td>
                            <td align="center" class="day day-curr">
                                @if ($calendar['year'] - 1 >= $calendar['today']['year'] - 10)
                                    <a href="{{ route('home', ['year' => ($calendar['year'] - 1), 'month' => $calendar['month']]) }}">&lt;&lt;</a>
                                @endif
                            </td>
                            <td align="center" colspan="3" class="day day-curr">
                                <select name="year" onchange="return setMonth(this, '{{ $calendar['month'] }}');">
                                    @for ($i = $calendar['today']['year'] - 10; $i <= $calendar['today']['year'] + 10; ++$i)
                                        <option value="{{ $i }}"{{ ($calendar['year'] == $i) ? ' selected="selected"' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </td>
                            <td align="center" class="day day-curr">
                                @if ($calendar['year'] + 1 <= $calendar['today']['year'] + 10)
                                    <a href="{{ route('home', ['year' => ($calendar['year'] + 1), 'month' => $calendar['month']]) }}">&gt;&gt;</a>
                                @endif
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="center" class="day day-curr">
                                @if (($calendar['year'] - 1 >= $calendar['today']['year'] - 10) || ($calendar['month'] - 1 >= 1))
                                    <a href="{{ route('home', ['year' => (($calendar['month'] - 1 < 1) ? ($calendar['year'] - 1) : $calendar['year']), 'month' => (($calendar['month'] - 1 < 1) ? 12 : ($calendar['month'] - 1))]) }}">&lt;&lt;</a>
                                @endif
                            </td>
                            <td align="center" colspan="3" class="day day-curr">
                                <select name="month" onchange="return setMonth('{{ $calendar['year'] }}', this);">
                                    <option value="1"{{ ($calendar['month'] == 1) ? ' selected="selected"' : '' }}>Январь</option>
                                    <option value="2"{{ ($calendar['month'] == 2) ? ' selected="selected"' : '' }}>Февраль</option>
                                    <option value="3"{{ ($calendar['month'] == 3) ? ' selected="selected"' : '' }}>Март</option>
                                    <option value="4"{{ ($calendar['month'] == 4) ? ' selected="selected"' : '' }}>Апрель</option>
                                    <option value="5"{{ ($calendar['month'] == 5) ? ' selected="selected"' : '' }}>Май</option>
                                    <option value="6"{{ ($calendar['month'] == 6) ? ' selected="selected"' : '' }}>Июнь</option>
                                    <option value="7"{{ ($calendar['month'] == 7) ? ' selected="selected"' : '' }}>Июль</option>
                                    <option value="8"{{ ($calendar['month'] == 8) ? ' selected="selected"' : '' }}>Август</option>
                                    <option value="9"{{ ($calendar['month'] == 9) ? ' selected="selected"' : '' }}>Сентябрь</option>
                                    <option value="10"{{ ($calendar['month'] == 10) ? ' selected="selected"' : '' }}>Октябрь</option>
                                    <option value="11"{{ ($calendar['month'] == 11) ? ' selected="selected"' : '' }}>Ноябрь</option>
                                    <option value="12"{{ ($calendar['month'] == 12) ? ' selected="selected"' : '' }}>Декабрь</option>
                                </select>
                            </td>
                            <td align="center" class="day day-curr">
                                @if (($calendar['year'] + 1 <= $calendar['today']['year'] + 10) || ($calendar['month'] + 1 <= 12))
                                    <a href="{{ route('home', ['year' => (($calendar['month'] + 1 > 12) ? ($calendar['year'] + 1) : $calendar['year']), 'month' => (($calendar['month'] + 1 > 12) ? 1 : ($calendar['month'] + 1))]) }}">&gt;&gt;</a>
                                @endif
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td align="center" class="day day-curr">Пн</td>
                            <td align="center" class="day day-curr">Вт</td>
                            <td align="center" class="day day-curr">Ср</td>
                            <td align="center" class="day day-curr">Чт</td>
                            <td align="center" class="day day-curr">Пт</td>
                            <td align="center" class="day day-curr day-holy">Сб</td>
                            <td align="center" class="day day-curr day-holy">Вс</td>
                        </tr>
                        <tr>
                            @foreach ($calendar['prev'] as $day)
                                @continue (($loop->index < (count($calendar['prev']) - 6)) || ($day['week'] >= $calendar['curr'][1]['week']))
                                <td align="center" class="day day-prev{{ ($day['week'] > 4) ? ' day-holy' : '' }}"><span>{{ $loop->iteration }}</span></td>
                            @endforeach
                            @foreach ($calendar['curr'] as $day)
                                <td align="center" class="day day-curr{{ ($day['week'] > 4) ? ' day-holy' : '' }}{{ (($calendar['today']['year'] == $calendar['year']) && ($calendar['today']['month'] == $calendar['month']) && ($calendar['today']['day'] == $loop->index)) ? ' day-today' : '' }}{{ (isset($calendar['notes'][$loop->index]) && intval($calendar['notes'][$loop->index]) > 0) ? ' day-notes' : '' }}"{{ (isset($calendar['notes'][$loop->index]) && intval($calendar['notes'][$loop->index]) > 0) ? ' title="'.$calendar['notes'][$loop->index].'"' : '' }}>
                                    @if (Auth::check())
                                        <a href="{{ route('notes', ['year' => $calendar['year'], 'month' => $calendar['month'], 'day' => $loop->iteration]) }}"><span>{{ $loop->iteration }}</span></a>
                                    @else
                                        <span>{{ $loop->iteration }}</span>
                                    @endif
                                </td>
                                @if ($day['week'] == 6)
                                    </tr><tr>
                                @endif
                            @endforeach
                            @foreach ($calendar['next'] as $day)
                                @break (($loop->index > 6) || ($day['week'] == 0))
                                <td align="center" class="day day-next{{ ($day['week'] > 4) ? ' day-holy' : '' }}"><span>{{ $loop->iteration }}</span></td>
                            @endforeach
                        </tr>
                        @if (($calendar['year'] != $calendar['today']['year']) || ($calendar['month'] != $calendar['today']['month']))
                            <tr>
                                <td colspan="7" align="center" class="day day-curr">
                                    <a href="{{ route('home', ['year' => $calendar['today']['year'], 'month' => $calendar['today']['month']]) }}">
                                        @guest
                                            Сегодня
                                        @else
                                            Что там на сегодня?
                                        @endguest
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <pre>{{ print_r($calendar) }}</pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
