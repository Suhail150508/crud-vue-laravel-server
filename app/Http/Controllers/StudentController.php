<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Stringable;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Component\HttpKernel\Event\ResponseEvent;


class StudentController extends Controller
{
    // public function store(Request $request ){

    //    $students =  new Student;
    //    $students->name = $request->name;
    //    $students->class = $request->class;


    // if($request->hasFile('image'))
    // {
    //     $file = $request->file('image');
    //     $extention = $file->getClientOriginalExtension();
    //     $fileName ='http:localhost://127.0.0.1:8000/images'.time().'.'.$extention;
    //     $file->move('images',$fileName);
    //     $students->image=$fileName;
    // }

    //    $students->save();
    // 	return response()->json(['success'=>'You have successfully upload image.']);

    // }
    // public function index(){
    //    $students =  Student::all();
    //    return $students;
    // }



    public function index()
    {
        $students = Student::all();
        return response()->json($students);
    }

    public function indexid($id)
    {
        $students = Student::find($id);

        return response()->json($students);
    }

       public function store(Request $request)
        {
            // dd($request->all());
            // dd(URL());
            // $request->validate([
            //     'name' => 'Required|max:50',
            //     'class' => 'Required|max:50',
            //     'image' => 'Required',
            // ]);

            // dd('dsfsdfgdsf');



            $store = new Student;
            $store->name = $request->input('name');
            $store->class = $request->input('class');
            //  $imageName = time().'.'.$request->image->getClientOriginalExtension();
            //  $request->image->move(public_path('images'), $imageName);

            // if ($request->file('image')) {
            //     $file = $request->file('image');
            //     $extention = $file->getClientOriginalExtension();
            //     $fileName = 'http://localhost:8000/imagesss/' . time() . '.' . $extention;
            //     $file->move('imagesss', $fileName);
            //     $store->image = $fileName;
            // }
            if ($request->image) {
                $file = $request->image;
                // dd($file);
                $directory = 'imagesss';
                $filename = Str::random(10).'_'.uniqid().'.' . explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
                $path = $directory.'/'.$filename;

                // dd($filename, $path);

                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0775, true, true);
                }

                $image_parts = explode(";base64,", $file);
                // dd($image_parts);
                $image_base64 = base64_decode($image_parts[1]);
                // dd($image_base64);
                file_put_contents($path, $image_base64);
                $store->image = 'http://localhost:8000/'.$path;

                // $img = Image::make($image);
                // $img->resize($width, $height, function ($constraint) {
                // })->save($path);

                // return $path;
            }
            $store->save();

            if (($store->save())) {

                return response()->json([
                    'status' => 200,
                    'message' => 'added successfully'
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'not found path'
                ]);
            }
        }

    public function edit($id)
    {
        $edit = Student::find($id);
        if ($edit) {

            return response()->json([
                'status' => 202,
                'message' => 'update successfully'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'not found student'
            ]);
        }
    }
    public function update(Request $request, $id)
    {

        // dd($request->all(),$id);
        $update = Student::find($id);

        if ($update) {
            $update->name = $request->input('name');
            $update->class = $request->input('class');
            // if($request->hasFile('image'))
            // {
            //  $destination = 'imagesss/'.$update->image;

            //  if(File::exists($destination))
            //  {
            //     File::delete($destination);
            //  }
            //    $file = $request->file('image');
            // $extention = $file->getClientOriginalExtension();
            // $fileName = 'http://localhost:8000/imagesss/'.time().'.'.$extention;
            // $file->move('imagesss', $fileName);
            // $update->image=$fileName;
            // }

            if ($request->image) {
                $file = $request->image;
                // dd($file);
                $directory = 'imagesss';
                $filename = Str::random(10).'_'.uniqid().'.' . explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
                $path = $directory.'/'.$filename;

                // dd($filename, $path);

                if (!File::exists($directory)) {
                    File::delete($directory);
                }

                $image_parts = explode(";base64,", $file);
                // dd($image_parts);
                $image_base64 = base64_decode($image_parts[1]);
                // dd($image_base64);
                file_put_contents($path, $image_base64);
                $update->image = 'http://localhost:8000/'.$path;

                // $img = Image::make($image);
                // $img->resize($width, $height, function ($constraint) {
                // })->save($path);

                // return $path;
            }

            $update->update();
            return response()->json([
                'status' => 200,
                'message' => 'update successfully'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'not found student'
            ]);
        }
    }

    public function delete($id){
     $student = Student::find($id);
       $delete = $student->delete();
     if( $delete){
        return response()->json([
            'status' => 200,
            'message' => 'deleted successfully'
        ]);
     }else{
        return response()->json([
            'status' => 404,
            'message' => 'not found student'
        ]);
     }

}

}
