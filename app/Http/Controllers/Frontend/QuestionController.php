<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Api\Question;
use App\Models\Api\Student;
use App\Repositories\Frontend\Api\QuestionRepository;
use App\Models\Auth\User;
use Illuminate\Http\Request;

class QuestionController extends Controller
{

    public $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Question::with('category')->orderBy('id', 'desc')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $question = Question::create($request->all());
        return Question::with('category')->find($question->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::where('id', $id)->with('user')->with('category')->with('comments')->first();
        $user = User::where('id', auth()->user()->id)->with('roles')->first();
        return view('frontend.questions.single')->with(compact('question', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Add like for question
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function like($id) {
        $question = Question::find($id);
        $likes = $question->likes;
        $likes++;
        $question->likes = $likes;

        $question->user()->increment('reputation');
        $question->save();

        return response()->json([
            'status' => 200
        ]);
    }

    /**
     * Add dislike for question
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dislike($id) {
        $question = Question::find($id);
        $dislikes = $question->dislikes;
        $dislikes++;
        $question->dislikes = $dislikes;

        $question->user()->decrement('reputation');
        $question->save();

        return response()->json([
            'status' => 200
        ]);
    }

    /**
     * Add views for question
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id) {
        $question = Question::find($id);
        $views = $question->views;
        $views++;
        $question->views = $views;


        $question->save();

        return response()->json([
            'status' => 200
        ]);

    }

    /**
     * Filter questions by criteria
     * @param Request $request
     * @return $this
     */
    public function filter(Request $request) {
        $sort = $request->get('sort');
        $disciplines = $request->get('disciplines');
        $schools = $request->get('schools');

        $user = $this->getUser();
        $usersIds = [];

        if($schools){
            $usersIds = $this->getUsersBySchoolsId($schools);
        }

        $this->questionRepository->filter($sort, $usersIds, $disciplines, $user->id);
//        Question::filter($sort, $disciplines, $usersIds, $user->id);
        die();
        return Question::orderBy('updated_at', 'desc')->get()->toJson();
    }

    /**
     * Return all users id based on schools ids
     * @param $schools
     * @return array
     */
    private function getUsersBySchoolsId($schools)
    {
        $userIds = Student::whereIn('school_id', $schools)->select('user_id')->get()->toArray();
        return array_map(function($x) { return $x['user_id']; }, $userIds);
    }
}
