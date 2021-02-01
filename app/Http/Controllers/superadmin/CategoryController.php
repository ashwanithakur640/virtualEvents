<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use DataTables;
use App\Category;
use App\Event;
use App\Session;
//use Phpfastcache\Helper\Psr16Adapter;

// use InstagramScraper\Instagram;
// use Phpfastcache\Helper\Psr16Adapter;

class CategoryController extends Controller
{
    /**
     * @method:      index
     * @params:      
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view list of categories
     */
    public function index(Request $request){


//         $instagram  = Instagram::withCredentials('itechnolabsca', 'Desktop@123', new Psr16Adapter('Files'));
// $instagram->login();
// $instagram->saveSession();

// $posts  = $instagram->getFeed();

// foreach ($posts as $post){
//     echo $post->getImageHighResolutionUrl()."\n";
// }

// die();
        
    	$data = Category::latest()->get();
    	return view('superadmin.categories.index', compact('data'));
        
    }

    /**
     * @method:      create
     * @params:      
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Create category form
     */
    public function create(){
    	return view('superadmin.categories.create');
    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validator(Request $request, $id = null){
        $rules = [
                    'name' => 'required|max:30|unique:categories,name,NULL,id,deleted_at,NULL',
                    'image' => 'required|mimes:jpg,jpeg,png',
                    'description' => 'required',
                ];
        if($id) {
            $rules = array_merge($rules, [
                'name' => 'required|max:30|unique:categories,name,'.$id.',id,deleted_at,NULL',
                'image' => 'nullable|mimes:jpg,jpeg,png',

            ]);
        }
        $messages = [
                        'name.required' => 'Please enter category name.',
                        'name.unique' => 'Category name already has been taken.',
                        'image.required' => 'Please select category image.',
                        'description.required' => 'Please enter category description.',
                    ];
        $request->validate($rules,$messages);
    }

    /**
     * @method:      store
     * @params:      Request $request
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Add category
     */
    public function store(Request $request){
        $this->validator($request);
        try{
        	$reqData = $request->all();
        	/* Start upload category image */
            if(isset($reqData['image']) && !empty($reqData['image'])){
                $profilePicName = time() . '_' . $reqData['image']->getClientOriginalName();
                $path = public_path('/assets/images/categories');
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                Helper::uploadImage($reqData['image'], $path, $profilePicName);
                $reqData['image'] = $profilePicName;
            }
            /* End upload category image */
        	Category::create($reqData);
        	return redirect('/superadmin/categories/')->withSuccess('Category has been added successfully.');
    	} catch(\Exception $e) {
        	return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      edit
     * @params:      $encryptId
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To edit category
     */
    public function edit($encryptId){
        $id = Helper::decrypt($encryptId);
        $data = Category::findOrFail($id);
        try{
            return view('superadmin.categories.edit', compact('data'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      update
     * @params:      Request $request, $encryptId
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To update category
     */
    public function update(Request $request, $encryptId){
        $id = Helper::decrypt($encryptId);
        $this->validator($request, $id);
        try{
            $reqData = $request->except(['_token', '_method']);
            /* Start upload category image */
            if(isset($reqData['image']) && !empty($reqData['image'])){
                $profilePicName = time() . '_' . $reqData['image']->getClientOriginalName();
                $path = public_path('/assets/images/categories');
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                Helper::uploadImage($reqData['image'], $path, $profilePicName);
                $reqData['image'] = $profilePicName;
            }
            /* End upload category image */
            Category::where('id', $id)->update($reqData);
            return redirect('superadmin/categories/')->withSuccess('Category has been update successfully');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      destroy
     * @params:      $encryptId
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To soft delete category
     */
    public function destroy($encryptId){
        $id = Helper::decrypt($encryptId);
        $data = Category::findOrFail($id);
        try{
            $eventIds = Event::where('category_id', $data->id)->pluck('id');
            Category::where('id', $data->id)->delete();
            Event::where('category_id', $data->id)->delete();
            Session::whereIn('event_id', $eventIds)->delete();
            return back()->withSuccess('Category has been deleted successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

}
