<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use DataTables;
use App\Faq;
use App\User;
use App\ContactUs;

class ReportController extends Controller
{
    /**
     * @method:      index
     * @params:      Request $request
     * @createdDate: 13-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     View list of FAQ Report
     */
    public function index(Request $request){
    	if ($request->ajax()) {
            $data = Faq::with('user')->get();
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {    
                        	$additional = '';
                            if(isset($modal->answer)){
                               $additional = '<a href="'.url("admin/faq-report/view/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp';
                            } else{
                            	$additional = '<a href="'.url("admin/faq-report/reply-answer/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Reply"><i class="fa fa-share"></i></a>&nbsp';
                            }            
                            $action = $additional.'<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="'.url('admin/faq-report/delete/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                             return $action;
                        })
                        ->editColumn('question', function($modal) {
                                return $modal->question ;
                            })
                        ->rawColumns(['action', 'question'])
                        ->make(true);
        }
    	return view('admin.reports.index');
    }

    /**
     * @method:      replyAnswer
     * @params:      $encryptId
     * @createdDate: 13-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     FAQ answer
     */
    public function replyAnswer($encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = Faq::findOrFail($id);
        try{
            return view('admin.reports.replyAnswer', compact('data'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 13-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validator(Request $request, $id = null){
        $rules = [
                    'answer' => 'required',
                ];
        $messages = [
                        'answer.required' => 'Please enter your answer.',
                    ];
        $request->validate($rules,$messages);
    }

    /**
     * @method:      updateAnswer
     * @params:      Request $request
     * @createdDate: 13-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To update FAQ answer
     */
    public function updateAnswer(Request $request, $encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $this->validator($request, $id);
        try{
            $reqData = $request->except(['_token']);
            Faq::where('id', $id)->update($reqData);
            /* Start sent FAQ reply Report Answer email to user */
            $faq = Faq::where('id', $id)->first();
            $user = User::with('vendor_details')->where('id', $faq->user_id)->first();
            if($user->role_id == 1){
                $url = url($user['vendor_details']->company_business_domain.'/dashboard');
            } else{
                $url = url('/welcome');
            }
            $subject = 'Report';            
            $templateName = 'emails.replyReport';
            $mailData = [    
                            'name' => $user->first_name .' '. $user->middle_name .' '. $user->last_name,
                            'title' => 'FAQ',
                            'query' => $faq->question,
                            'answer' => $reqData['answer'],
                            'url' => $url,
                        ];
            $toEmail = $user->email;
            Helper::sendMail($subject, $templateName, $mailData, $toEmail);
            /* End sent FAQ reply Report Answer email to user */
            return redirect('admin/faq-report/')->withSuccess('FAQ answer has been sent successfully');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      show
     * @params:      $encryptId
     * @createdDate: 13-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     View FAQ detail
     */
    public function show($encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = Faq::findOrFail($id);
        try{
            return view('admin.reports.view', compact('data'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      destroy
     * @params:      $encryptId
     * @createdDate: 13-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To soft delete FAQ Report
     */
    public function destroy($encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = Faq::findOrFail($id);
        try{
            Faq::where('id', $data->id)->delete();
            return back()->withSuccess('FAQ Report has been deleted successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      indexContactUs
     * @params:      Request $request
     * @createdDate: 14-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     View list of Contact Us Report 
     */
    public function indexContactUs(Request $request){
        if ($request->ajax()) {
            $data = ContactUs::with('user')->get();
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {    
                            $additional = '';
                            if(isset($modal->answer)){
                               $additional = '<a href="'.url("admin/contact-us-report/view/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp';
                            } else{
                                $additional = '<a href="'.url("admin/contact-us-report/reply-answer/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Reply"><i class="fa fa-share"></i></a>&nbsp';
                            }            
                            $action = $additional.'<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="'.url('admin/contact-us-report/delete/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                             return $action;
                        })
                        ->editColumn('description', function($modal) {
                                return $modal->description ;
                            })
                        ->rawColumns(['action', 'description'])
                        ->make(true);
        }
        return view('admin.reports.contactUs.index');
    }

    /**
     * @method:      replyContactUsAnswer
     * @params:      $encryptId
     * @createdDate: 14-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Contact Us answer
     */
    public function replyContactUsAnswer($encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = ContactUs::findOrFail($id);
        try{
            return view('admin.reports.contactUs.replyAnswer', compact('data'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 14-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validatorRule(Request $request, $id = null){
        $rules = [
                    'answer' => 'required',
                ];
        $messages = [
                        'answer.required' => 'Please enter your answer.',
                    ];
        $request->validate($rules,$messages);
    }

    /**
     * @method:      updateContactUsAnswer
     * @params:      Request $request
     * @createdDate: 14-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To update Contact Us answer
     */
    public function updateContactUsAnswer(Request $request, $encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $this->validatorRule($request, $id);
        try{
            $reqData = $request->except(['_token']);
            ContactUs::where('id', $id)->update($reqData);
            /* Start sent Contact Us reply Report Answer email to user */
            $user = ContactUs::where('id', $id)->first();
            $userDetail = User::with('vendor_details')->where('id', $user['user_id'])->first();
            if($userDetail->role_id == 1){
                $url = url($userDetail['vendor_details']->company_business_domain.'/dashboard');
            } else{
                $url = url('/welcome');
            }
            $subject = 'Report';            
            $templateName = 'emails.replyReport';
            $mailData = [    
                            'name' => $user->first_name .' '. $user->middle_name .' '. $user->last_name,
                            'title' => 'Contact Us',
                            'query' => $user->description,
                            'answer' => $reqData['answer'],
                            'url' => $url,
                        ];
            $toEmail = $user->email;
            Helper::sendMail($subject, $templateName, $mailData, $toEmail);
            /* End sent Contact Us reply Report Answer email to user */
            return redirect('admin/contact-us-report/')->withSuccess('Contact Us answer has been sent successfully');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      showContactUs
     * @params:      $encryptId
     * @createdDate: 14-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     View Contact Us detail
     */
    public function showContactUs($encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = ContactUs::findOrFail($id);
        try{
            return view('admin.reports.contactUs.view', compact('data'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      destroyContactUs
     * @params:      $encryptId
     * @createdDate: 14-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To soft delete Contact Us Report
     */
    public function destroyContactUs($encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = ContactUs::findOrFail($id);
        try{
            ContactUs::where('id', $data->id)->delete();
            return back()->withSuccess('Contact Us Report has been deleted successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

}
