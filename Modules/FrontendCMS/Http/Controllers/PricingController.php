<?php

namespace Modules\FrontendCMS\Http\Controllers;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use \Modules\FrontendCMS\Services\PricingService;
use Exception;
use Modules\FrontendCMS\Http\Requests\PricingRequest;
use Modules\GST\Entities\GstTax;
use Modules\UserActivityLog\Traits\LogActivity;
use Modules\Shipping\Repositories\PickupLocationRepository;
use Modules\Setup\Repositories\StateRepository; 
use Modules\Setup\Repositories\CityRepository; 
use Modules\Setup\Entities\Country;
use Modules\Setup\Entities\State;
use Modules\Setup\Entities\City;
class PricingController extends Controller
{
    protected $pricingService,$pickupLocationRepo,$stateRepository,$cityRepository;
    public function __construct(PricingService $pricingService ,PickupLocationRepository $pickupLocationRepo ,StateRepository $stateRepository ,CityRepository $cityRepository)
    {
        $this->middleware('maintenance_mode');
        $this->middleware('prohibited_demo_mode')->only('store');
        $this->pricingService = $pricingService;
        $this->pickupLocationRepo = $pickupLocationRepo;
        $this->stateRepository = $stateRepository;
        $this->cityRepository = $cityRepository;
    }
    public function index()
    {
        try {
            $gst_taxes = GstTax::where('is_active',1)->get();
            $PricingList = $this->pricingService->getAll();
            return view('frontendcms::pricing.index', compact('PricingList','gst_taxes'));
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));
            return back();
        }
    }
    public function get_pricing()
    {
        try {
            $pricingList = $this->pricingService->getAll();
            $option = '';
            foreach ($pricingList as $key => $pricing) {
                $option .= '<option value="'.$pricing->id .'">'. $pricing->name .'</option>';
            }
            $output = '';
            $output .= '<div class="primary_input mb-25">
                            <label class="primary_input_label" for="">'. __('seller.subscription_type') .' <span class="text-danger">*</span></label>
                            <select class="primary_select pricing_id" name="pricing_id" id="pricing_id">
                                '.$option.'
                            </select>
                        </div>';
            return response()->json($output);
        } catch (\Exception $e) {

        }
    }
    public function details_for_warehouse($id)
    {
        try {
            $warehouseAll = $this->pickupLocationRepo->all();
            $countries = Country::all();
            $states = State::with('country')->get();
            $cities = City::with('state.country')->get();
            $warehouse_data = '';
            $address='';
            $phone='';
            $post_code='';
            $countryOutput = '';
            $stateOutput = '';
            $cityOutput = '';
            foreach ($warehouseAll as $warehouse) {
                if ($warehouse->id == $id) {
                    $warehouse_data.=$warehouse;
                    $address .= '<div class="primary_input mb-25">
                                    <label class="primary_input_label" for="warehouse_address">' . __('common.address') . '</label>
                                    <input name="warehouse_address" class="primary_input_field" placeholder="-" type="text"
                                           value="' . $warehouse->address . '" disabled>       
                                 </div>';
                    $phone .= '<div class="primary_input mb-25">
                                    <label class="primary_input_label" for="warehouse_phone">' . __('common.phone_number') . '</label>
                                    <input name="warehouse_phone" class="primary_input_field" placeholder="-" type="text"
                                           value="' . $warehouse->phone . '" disabled>      
                               </div>';
                    $post_code .= '<label class="primary_input_label" for="warehouse_postcode">' . __('common.postcode') . '</label> 
                                    <input name="warehouse_postcode" class="primary_input_field" placeholder="-" type="text"
                                           value="' . $warehouse->pin_code . '" disabled>';
    
                    $countryOption = '';
                    foreach ($countries as $country) {
                        $selected = $country->id == $warehouse->country_id ? 'selected' : '';
                        $countryOption .= '<option value="' . $country->id . '" ' . $selected . '>' . $country->name . '</option>';
                    }
    
                    $countryOutput .= '<label class="primary_input_label" for="country">' . __('seller.country_region') . '</label>
                                        <select name="country" id="warehouse_country" class="primary_select mb-25" disabled>
                                            ' . $countryOption . '
                                        </select>';
    
                    $stateOption = '';
                    foreach ($states as $state) {
                        $selected = $state->id == $warehouse->state_id ? 'selected' : '';
                        $stateOption .= '<option value="' . $state->id . '" ' . $selected . '>' . $state->name . '</option>';
                    }
    
                    $stateOutput .= '<label class="primary_input_label" for="country">' . __('common.state') . '</label>
                                     <select name="state" id="warehouse_state" class="primary_select mb-25" disabled>
                                         ' . $stateOption . '
                                     </select>';
    
                    $cityOption = '';
                    foreach ($cities as $city) {
                        $selected = $city->id == $warehouse->city_id ? 'selected' : '';
                        $cityOption .= '<option value="' . $city->id . '" ' . $selected . '>' . $city->name . '</option>';
                    }
    
                    $cityOutput .= '<label class="primary_input_label" for="country">' . __('common.city') . '</label>
                                    <select name="city" id="warehouse_city" class="primary_select mb-25" disabled>
                                        ' . $cityOption . '
                                    </select>';
                }
            }
            return response()->json(['address' =>$address,'phone' =>$phone,'post_code' =>$post_code,'cityOutput' => $cityOutput,'stateOutput'=> $stateOutput,'countryOutput'=>$countryOutput,'warehouse'=>$warehouse_data]);
        } catch (\Exception $e) {
            
        }
    }

    public function create()
    {
        try {
            return response()->json([
                'editHtml' => (string)view('frontendcms::pricing.components.create')
            ]);
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
    }

    public function store(PricingRequest $request)
    {
        try {

            $this->pricingService->save($request->except("_token"));
            LogActivity::successLog('Pricing Status Added');
            return true;
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        try {
            $gst_taxes = GstTax::where('is_active',1)->get();
            $pricing = $this->pricingService->editById($id);
            return response()->json([
                'editHtml' => (string)view('frontendcms::pricing.components.edit',compact('gst_taxes')),
                'data' => $pricing,
            ]);
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
    }

    public function update(PricingRequest $request)
    {
        try {
            $this->pricingService->update($request->except("_token"));
            LogActivity::successLog('Pricing updated.');
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
        return  $this->loadTableData();
    }

    public function destroy(Request $request)
    {
        try {
            $this->pricingService->deleteById($request->id);
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'status'    =>  false,
                'message'   =>  $e->getMessage()
            ]);
        }
        return $this->loadTableData();
    }

    public function status(Request $request)
    {
        try {
            $status = filter_var($request->status, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $newStatus = !$status;
            $data = [
                'status' => $newStatus
            ];
            $this->pricingService->statusUpdate($data, $request->id);
            LogActivity::successLog('Pricing Status Update.');
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
        return $this->loadTableData();
    }

    private function loadTableData()
    {
        try {
            $PricingList = $this->pricingService->getAll();
            return response()->json([
                'TableData' =>  (string)view('frontendcms::pricing.components.list', compact('PricingList'))
            ]);
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));
            return back();
        }
    }
}
