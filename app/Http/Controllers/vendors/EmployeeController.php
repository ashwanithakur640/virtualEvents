<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Requests\CreateBusinessCardRequest;
use App\Http\Requests\UpdateBusinessCardRequest;
use App\Repositories\BusinessCardRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\User;


class EmployeeController extends AppBaseController
{
    /** @var  BusinessCardRepository */
    private $businessCardRepository;

    public function __construct(BusinessCardRepository $businessCardRepo)
    {
        $this->businessCardRepository = $businessCardRepo;
    }

    /**
     * Display a listing of the BusinessCard.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //$businessCards = $this->businessCardRepository->all();
        $businessCards = BusinessCard::where('vendor_id',\Auth::id())->paginate(5);

        return view('vendor.business_cards.index')
            ->with('businessCards', $businessCards);
    }

    /**
     * Show the form for creating a new BusinessCard.
     *
     * @return Response
     */
    public function create()
    {
        //$vendors = User::where('role',2)->pluck('name','id');
        $phoneCode = config('teliphone.code');

        return view('vendor.business_cards.create')->with('phoneCode', $phoneCode);
    }

    /**
     * Store a newly created BusinessCard in storage.
     *
     * @param CreateBusinessCardRequest $request
     *
     * @return Response
     */
    public function store(CreateBusinessCardRequest $request)
    {
        $this->validate($request,
            ['name' => 'required|min:3',
            'email' => 'unique:users,email|required',
            'image' => 'required',
            'phone' => 'required',
            'title' => 'required',
            ]);

        $input = $request->all();
        
        //Add phone code in phone no
        $input = $request->all();
        if($input['phone']){
            $input['phone'] = '+'.$input['phone_code'].'-'.$input['phone'];
        }        

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                //
                $validated = $request->validate([
                    //'name' => 'string|max:40',
                    'image' => 'mimes:jpeg,png|max:1014',
                ]);
                $filename = \Carbon\Carbon::now()->timestamp;
                $extension = $request->image->extension();
                $destinationPath = public_path('/business_card');
                $request->image->move($destinationPath, $filename.'.'.$extension);
                $input['image'] = $filename.'.'.$extension; 
            }
        }
        $input['vendor_id'] = \Auth::id();
        $businessCard = $this->businessCardRepository->create($input);

        Flash::success('Business Card saved successfully.');

        return redirect(route('business-cards.index'));
    }

    /**
     * Display the specified BusinessCard.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $businessCard = $this->businessCardRepository->find($id);

        if (empty($businessCard)) {
            Flash::error('Business Card not found');

            return redirect(route('business-cards.index'));
        }

        return view('vendor.business_cards.show')->with('businessCard', $businessCard);
    }

    /**
     * Show the form for editing the specified BusinessCard.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $businessCard = $this->businessCardRepository->find($id);

        if (empty($businessCard)) {
            Flash::error('Business Card not found');

            return redirect(route('business-cards.index'));
        }

        //split phone code
        $phoneData = explode("-", $businessCard->phone);
        $businessCard['phone'] = $phoneData[1];
        $phone_code = explode("+", $phoneData[0]);
        $businessCard['phone_code'] = $phone_code[1];

        $phoneCode = config('teliphone.code');
        //$vendors = User::where('role',2)->pluck('name','id');

        return view('vendor.business_cards.edit')->with('businessCard', $businessCard)->with('phoneCode', $phoneCode);
    }

    /**
     * Update the specified BusinessCard in storage.
     *
     * @param int $id
     * @param UpdateBusinessCardRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBusinessCardRequest $request)
    {
        $this->validate($request,
            ['name' => 'required|min:3',
            'email' => 'required',
            //'image' => 'required',
            'phone' => 'required',
            'title' => 'required',
            ]);


        $businessCard = $this->businessCardRepository->find($id);

        if (empty($businessCard)) {
            Flash::error('Business Card not found');

            return redirect(route('business-cards.index'));
        }

        $input = $request->all();
        
        //Add phone code in phone no
        if($input['phone']){
            $input['phone'] = '+'.$input['phone_code'].'-'.$input['phone'];
        }


        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                //
                $validated = $request->validate([
                    //'name' => 'string|max:40',
                    'image' => 'mimes:jpeg,png|max:1014',
                ]);
                $filename = \Carbon\Carbon::now()->timestamp;
                $extension = $request->image->extension();
                $destinationPath = public_path('/business_card');
                $request->image->move($destinationPath, $filename.'.'.$extension);
                $input['image'] = $filename.'.'.$extension; 
            }
        }        

        $businessCard = $this->businessCardRepository->update($input, $id);

        Flash::success('Business Card updated successfully.');

        return redirect(route('business-cards.index'));
    }

    /**
     * Remove the specified BusinessCard from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $businessCard = $this->businessCardRepository->find($id);

        if (empty($businessCard)) {
            Flash::error('Business Card not found');

            return redirect(route('business-cards.index'));
        }

        $this->businessCardRepository->delete($id);

        Flash::success('Business Card deleted successfully.');

        return redirect(route('business-cards.index'));
    }
}
