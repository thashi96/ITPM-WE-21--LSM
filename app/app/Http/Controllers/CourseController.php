<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\category;
use App\Course;
use App\CourseResource;
use App\Resource;

class CourseController extends Controller
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
            'file' => 'required|image|mimes:jpeg,png,jpg|max:50000',
        ]);
        $imageName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path('images/courses') , $imageName);
      
        $course              = new Course();
        $course->c_category      = $request->get('courses_category');
        $course->c_name      = $request->get('course_name');
        $course->code          = $request->get('course_code');
        $course->c_discription = $request->get('discription');
        $course->image       = $imageName;
        $course->save();

        
        $c_cat = category::get();
        $courses = Course::get();

        return back()->with(['success','Succesfully added'],['c_cat'=>$c_cat,'courses'=>$courses]);
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
        //
    }
    public function details(){
        $details = Student::get();
        return view('students.add_courses',['deatils'=>$details]);
    }
    public function create_courses_view(){
        $c_cat = category::get();
        $courses = Course::get();
        return view('admins.courses.create_course',['c_cat'=>$c_cat,'courses'=>$courses]);
    }
    public function course_resource($id){
        $get_modules = CourseResource::where('course_id',$id)->get();
        return view('admins.courses.courseResourece',['get_modules'=>$get_modules,'course_id'=>$id]);
    }

    public function course_materials(Request $request){
        $module = new CourseResource();
        $module->course_id = $request->get('couurseID');
        $module->module_name = $request->get('module_name');
        $module->save();
        $get_modules = CourseResource::where('course_id',$request->get('couurseID'))->get();
        return redirect()->back()->with(['get_modules'=>$get_modules]);
        
    }
    public function course_materials_save(Request $request,$type){
        $resourses = new Resource();
        $resourses->course_id = $request->get('couurseID');
        $resourses->module_id = $request->get('module_id');
        $resourses->meterial_name = $request->get('meterial_name');
        $resourses->type = $type;

        if($type=="pdf"){

            $pdf_file = time() . '.' . $request->upload_file->getClientOriginalExtension();
            $request->upload_file->move(public_path('file/pdf') , $pdf_file);

            $resourses->file = $pdf_file;
            $resourses->save();

            return redirect()->back()->with('success', 'File uploaded successfully.');
        }
        else if($type=="word"){

            $pdf_file = time() . '.' . $request->upload_file->getClientOriginalExtension();
            $request->upload_file->move(public_path('files/word') , $pdf_file);

            $resourses->file = $pdf_file;
            $resourses->save();

            return redirect()->back()->with('success', 'File uploaded successfully.');
        }else if($type=="powerpoint"){

            $pdf_file = time() . '.' . $request->upload_file->getClientOriginalExtension();
            $request->upload_file->move(public_path('files/powerpoint') , $pdf_file);

            $resourses->file = $pdf_file;
            $resourses->save();

            return redirect()->back()->with('success', 'File uploaded successfully.');
        }
    }
    public function destroy1(Request $request){
        $users = Course::find($request->id);
        $users->delete();
        $resource = Resource::where('course_id', $request->id);
        $resource->delete();
        $resource = CourseResource::where('course_id', $request->id);
        $resource->delete();

    }
}
