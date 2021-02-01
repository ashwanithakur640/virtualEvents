<?php

namespace App\Http\Controllers\vendors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;
use DataTables;
use App\Category;
use App\Event;
use App\Session;

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
        
    	$data = Category::get();
    	return view('vendors.categories.index', compact('data'));
        
    }

    /**
     * @method:      create
     * @params:      
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Create category form
     */
    public function create(){
    	return view('vendors.categories.create');
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
    public function store($prefix , Request $request){
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

             $reqData['organisation_id'] = Auth::id() ;

        	Category::create($reqData);
        	return redirect($this->Prefix. '/categories/')->withSuccess('Category has been added successfully.');
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
    public function edit($prefix , $encryptId){
        $id = Helper::decrypt($encryptId);
        $data = Category::findOrFail($id);
        try{
            return view('vendors.categories.edit', compact('data'));
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
    public function update($prefix , Request $request, $encryptId){
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
            return redirect($this->Prefix. '/categories/')->withSuccess('Category has been update successfully');
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
    public function destroy($prefix , $encryptId){
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
