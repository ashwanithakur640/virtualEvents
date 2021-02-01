<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Page;
use App\PageContent;

class FrontendContentController extends Controller
{
    /**
     * @method:      index
     * @params:      
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Frontend content
     */
    public function index(){
        $data = PageContent::with('page')->get();
        return view('admin.frontendContent.index', compact('data'));
    }

    /**
     * @method:      create
     * @params:      
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Create frontend content form
     */
    public function create(){
    	$data = Page::get();
    	return view('admin.frontendContent.create', compact('data'));
    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validator(Request $request, $id = null){
        $rules = [
                    'page_id' => 'required|unique:page_contents,page_id',
                    'title' => 'required',
                    'description' => 'required',
                ];
        if($id) {
            $rules = array_merge($rules, [
                'page_id' => 'required|unique:page_contents,page_id,'.$id,
            ]);
        }
        $messages = [
                        'page_id.required' => 'Please select your page.',
                        'page_id.unique' => 'This page already has been taken.',
                        'title.required' => 'Please enter page title.',
                        'description.required' => 'Please enter page description.',
                    ];
        $request->validate($rules,$messages);
    }

    /**
     * @method:      store
     * @params:      Request $request
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Add frontend content
     */
    public function store(Request $request){
        $this->validator($request);
        try{
        	$reqData = $request->all();
        	PageContent::create($reqData);
        	return redirect('/admin/frontend-contents/')->withSuccess('Page has been created successfully.');
    	} catch(\Exception $e) {
        	return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      edit
     * @params:      $encryptId
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To edit frontend content
     */
    public function edit($encryptId){
        $id = Helper::decrypt($encryptId);
        $page = PageContent::with('page')->findOrFail($id);
        try{
        	$data = Page::get();
            return view('admin.frontendContent.edit', compact('data','page'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      update
     * @params:      Request $request, $encryptId
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To frontend content
     */
    public function update(Request $request, $encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $this->validator($request, $id);
        try{
            $reqData = $request->except(['_token', '_method']);
            PageContent::where('id', $id)->update($reqData);
            return redirect('admin/frontend-contents/')->withSuccess('Page has been updated successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

}
