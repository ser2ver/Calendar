<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @return \Illuminate\Http\Response
     */
    public function index(int $year, int $month, int $day)
    {
        $user = Auth::user();
        $date = [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'form' => sprintf('%04d-%02d-%02d', $year, $month, $day)
        ];

        $today = Calendar::parseDate(date('Y-m-d'), true);
        $today['form'] = date('Y-m-d');

        $notes = $user->notes()->where('date', $date['form'])->orderBy('created_at')->get();

        return view('notes', ['today' => $today, 'date' => $date, 'notes' => $notes]);
    }

    /**
     * @param Request $request
     * @param int $year
     * @param int $month
     * @param int $day
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $year, int $month, int $day)
    {
        $user = Auth::user();
        $date = [
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'form' => sprintf('%04d-%02d-%02d', $year, $month, $day)
        ];

        try {
            $note = new Note();
            $note->user()->associate($user);
            $note->date = $date['form'];
            $note->year = $date['year'];
            $note->month = $date['month'];
            $note->day = $date['day'];
            $note->title = $request->title;
            $note->descr = $request->descr;
            $note->done = intval($request->done);

            if ($note->save()) {
                $request->session()->flash('status', 'Заметка сохранена');
            } else {
                $request->session()->flash('status', 'Заметка не сохранена');
            }
        } catch (\Exception $e) {
            $request->session()->flash('status', 'Что-то не сработало');
        }

        return redirect(route('notes', ['year' => $date['year'], 'month' => $date['month'], 'day' => $date['day']]));
    }

    /**
     * @param Request $request
     * @param int $year
     * @param int $month
     * @param int $day
     * @param Note $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $year, int $month, int $day, Note $note)
    {
        $user = Auth::user();
        if ($note->user_id == $user->id) {
            $note->title = $request->title;
            $note->descr = $request->descr;
            $note->done = intval($request->done);

            try {
                if ($note->save()) {
                    $request->session()->flash('status', 'Заметка изменена');
                } else {
                    $request->session()->flash('status', 'Заметка не изменена');
                }
            } catch (\Exception $e) {
                $request->session()->flash('status', 'Что-то не сработало');
            }
        }
        return redirect(route('notes', ['year' => $year, 'month' => $month, 'day' => $day]));
    }

    /**
     * @param Request $request
     * @param int $year
     * @param int $month
     * @param int $day
     * @param Note $note
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, int $year, int $month, int $day, Note $note)
    {
        $user = Auth::user();
        if ($note->user_id == $user->id) {
            try {
                if ($note->delete()) {
                    $request->session()->flash('status', 'Заметка удалена');
                } else {
                    $request->session()->flash('status', 'Заметка не удалена');
                }
            } catch (\Exception $e) {
                $request->session()->flash('status', 'Что-то не сработало');
            }
        }
        return redirect(route('notes', ['year' => $year, 'month' => $month, 'day' => $day]));
    }
}