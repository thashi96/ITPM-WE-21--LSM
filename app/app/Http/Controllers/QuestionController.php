<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Student;
use App\category;
use App\Course;
use App\CourseResource;
use App\Resource;
use App\Question;
use App\Comment;
use App\Answer;

//use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required',
            'body' => 'required',

        ]);

        $question =new Question();
        $question->title = $request->get('title');
        $question->body = $request->get('body');
        $question->category= $request->get('category');
        // right code to get tags input @yashith
       // $question->tags=implode(',',$request->input('tags'));
        $question->sid = $request->get('sid');
        $question->save();
        
        return redirect()->back()->with('success', 'Your Question Added successfully');
        
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    }

    public function post_question_page(){   
        $c_cat = category::get(); 
        $student_id = auth()->user()->id;     
        return view('students.post_question',['c_cat'=>$c_cat,'student_id'=>$student_id]);        
    }
//direct to main page
    public function top_question_page(){         
        $q_details = Question::orderBy('created_at','desc')->get(); 
        //$ans_count = Answer::orderBy('created_at','desc')
        return view('students.top_question',['q_details'=>$q_details]);        
    }

// direct to question page
    public function view_question_page($id,$sid){ 
        $get_q=Question::where('qid',$id)->get(); 
        //$sid=Question::where('qid',$id)->pluck('sid');
        $stu_details = Student::where('students.s_id',$sid)->get();

        $commy=Comment::where('comments.qid',$id)->join('students','comments.sid','students.s_id')->get();

        $ansy=Answer::where('answers.qid',$id)->join('students','answers.sid','students.s_id')->get();
  
        return view('students.question',['get_q'=>$get_q,'stu_details'=>$stu_details,'commy'=>$commy,'ansy'=>$ansy]);        
    }
//save comments
    public function save_comments(Request $request){

        $comm =new Comment();
        $comm->qid = $request->get('qid');
        $comm->sid = auth()->user()->id;
        $comm->comment= $request->get('comment');

        $comm->save();
        
        return redirect()->back()->with('success', 'Your comment Added successfully');

    }
//save answers
    public function save_answers(Request $request){

        $ans =new Answer();
        $ans->qid = $request->get('qid');
        $ans->sid = auth()->user()->id;
        $ans->answer= $request->get('answer');

        $ans->save();
        
        return redirect()->back()->with('success', 'Your answer Added successfully');

    }
//direct to my q page
    public function my_question_page(){         
        $qmy_details = Question::where('questions.sid',auth()->user()->id)-> orderBy('created_at','desc')->get();

        return view('students.my_question',['qmy_details'=>$qmy_details]);        
    }

//search question 
public function search_question(Request $request){
    $input = $request->get('search');
    $search_result = Question::where('title','like','%'.$input.'%')->orWhere('body','like','%'.$input.'%')->get();

    return view('students.search_question',['q_des'=>$search_result]);
}
}
