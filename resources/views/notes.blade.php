@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @if ($date['form'] > $today['form'])
                            Что планируете на <b>{{ $date['form'] }}</b>:
                        @elseif ($date['form'] < $today['form'])
                            Что было <b>{{ $date['form'] }}</b>:
                        @else
                            На <b>сегодня</b> у вас:
                        @endif
                    </div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table-bordered col-md-12">
                            @foreach ($notes as $note)
                            <tr>
                                <td class="day">
                                    <div>
                                        <form action="{{ route('notes.update', ['year' => $date['year'], 'month' => $date['month'], 'day' => $date['day'], 'note' => $note]) }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="PUT">

                                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                                <label for="title" class="col-md-4 control-label">Заголовок</label>
                                                <div class="col-md-6">
                                                    <input id="title" type="text" class="form-control" name="title" value="{{ $note->title }}" required>
                                                    @if ($errors->has('title'))
                                                        <span class="help-block">
                                                    <strong>{{ $errors->first('title') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('descr') ? ' has-error' : '' }}">
                                                <label for="descr" class="col-md-4 control-label">Описание</label>
                                                <div class="col-md-6">
                                                    <textarea id="descr" class="form-control" name="descr">{{ $note->descr }}</textarea>
                                                    @if ($errors->has('descr'))
                                                        <span class="help-block">
                                                    <strong>{{ $errors->first('descr') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('done') ? ' has-error' : '' }}">
                                                <label for="done" class="col-md-4 control-label">Выполнено</label>
                                                <div class="col-md-6">
                                                    <input id="done" type="checkbox" class="form-control" name="done" value="1"{{ $note->done ? ' checked="checked"' : '' }}>
                                                    @if ($errors->has('done'))
                                                        <span class="help-block">
                                                    <strong>{{ $errors->first('done') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-6 col-md-offset-4">
                                                    <button type="submit" class="btn btn-primary">
                                                        Сохранить
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <form action="{{ route('notes.destroy', ['year' => $date['year'], 'month' => $date['month'], 'day' => $date['day'], 'note' => $note]) }}" method="POST" onsubmit="return confirm('Точно удалить заметку?');">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <div class="form-group">
                                                <div class="col-md-6 col-md-offset-4">
                                                    <button type="submit" class="btn btn-primary">
                                                        Удалить
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="day">
                                    <div>
                                        Новая запись:
                                        <form action="{{ route('notes.store', ['year' => $date['year'], 'month' => $date['month'], 'day' => $date['day']]) }}" method="POST">
                                            {{ csrf_field() }}

                                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                                <label for="title" class="col-md-4 control-label">Заголовок</label>
                                                <div class="col-md-6">
                                                    <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                                                    @if ($errors->has('title'))
                                                        <span class="help-block">
                                                    <strong>{{ $errors->first('title') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('descr') ? ' has-error' : '' }}">
                                                <label for="descr" class="col-md-4 control-label">Описание</label>
                                                <div class="col-md-6">
                                                    <textarea id="descr" class="form-control" name="descr">{{ old('descr') }}</textarea>
                                                    @if ($errors->has('descr'))
                                                        <span class="help-block">
                                                    <strong>{{ $errors->first('descr') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('done') ? ' has-error' : '' }}">
                                                <label for="done" class="col-md-4 control-label">Выполнено</label>
                                                <div class="col-md-6">
                                                    <input id="done" type="checkbox" class="form-control" name="done" value="1">
                                                    @if ($errors->has('done'))
                                                        <span class="help-block">
                                                    <strong>{{ $errors->first('done') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-6 col-md-offset-4">
                                                    <button type="submit" class="btn btn-primary">
                                                        Добавить
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
