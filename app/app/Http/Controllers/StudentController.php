<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Student;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $this->validate($request, [
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email' => 'required|string|email|min:6|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = new User();
        $user->email = $request->get('email');
        $user->role_id = 1;
        $user->password = bcrypt($request->get('password'));
        $user->save();

        $student = new Student();
        $student->s_id = $user->id;
        $student->f_name = $request->get('f_name');
        $student->l_name = $request->get('l_name');
        $student->save();

        return redirect()->back()->with('success', 'You have been successfully registered');
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
        $this->validate($request, [
        'password' => 'required|string|min:6|confirmed'
        ]);

        $users = User::find($id);
        $users->email = $request->get('email');
        $users->role_id = $request->get('role_id');
        $users->password = bcrypt($request['password']);
        $users->save();

        $user_details = Student::where('s_id', $id)->first();
        $user_details->f_name = $request->get('f_name');
        $user_details->l_name = $request->get('l_name');
        $user_details->save();


        return redirect()->back()->with('message', 'Successfully Updates');
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
    public function destroy1(Request $request) {
        $users = User::find($request->id);
        $users->delete();
        $student = Student::where('s_id', $request->id);
        $student->delete();
    }
    //admin
    public function edit_profile_view($id){
        $stu_details = User::where('users.id',$id)->join('students','users.id','students.s_id')->get();
        return view('admins.student_profiles.edit_profile',['stu_details'=>$stu_details]);
    }
    //student
    public function edit_profile_view_student(){
        $user = auth()->user();
        $stu_details = User::where('users.id',$user->id)->join('students','users.id','students.s_id')->get();
        return view('students.edit_profile',['stu_details'=>$stu_details]);
    }
    public function view_profiles_page(){
        $student_details = User::join('students','users.id','students.s_id')->get();
        return view('admins.student_profiles.view_profiles',['student_details'=>$student_details]);
    }
    public function new_student_add_page(){
        return view('admins.student_profiles.add_new_student');
    }
    //student-post_question
    
}
