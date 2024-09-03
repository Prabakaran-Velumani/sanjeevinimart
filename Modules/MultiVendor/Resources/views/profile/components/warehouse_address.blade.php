<div class="row">
    <div class="col-lg-12">
        <div class="main-title">
            <h3 class="mb-30">
                {{__('common.warehouse_address')}} </h3>
        </div>

        <form method="POST" action="{{route('seller.profile.warehouse-address.update',$seller->id)}}" id="warehouse_address_form" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="white-box">
                <div class="add-visitor">
                    <div class="row">

                        {{-- <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="warehouse_name">{{__('common.full_name')}} <span class="text-danger">*</span></label>
                                <input name="warehouse_name" class="primary_input_field" placeholder="-" type="text"
                                       value="{{ old('warehouse_name')? old('warehouse_name'):$seller->SellerWarehouseAddress->warehouse_name }}">
                                       @error('warehouse_name')
                                       <span class="text-danger">{{$message}}</span>
                                       @enderror       
                            </div>
                            
                        </div> --}}
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label" for="">{{ __('common.warehouse') }} <span class="text-danger">*</span></label>
                                <select name="warehouse_id" id="warehouse_id" class="primary_select mb-15">
                                    <option disabled selected>{{'Select Warehouse'}}</option>
                                   
                                    @foreach($warehouse as $key => $item)
                                        <option value="{{$item->id}}" @if($seller->sellerAccount->warehouse_id == $item->id) selected @endif >{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{$errors->first('warehouse_id')}}</span>
                            </div>
                        </div>
                        <div class="col-xl-6" id='warehouse_address_div'>
                        </div>
                        <div class="col-xl-6" id='phone_div'>
                        </div>
                        <div class="col-xl-6" id=warehouse_country>
                        </div>
                        <div class="col-xl-6" id='warehouse_state_div'>
                        </div>
                        <div class="col-xl-6" id='warehouse_city_div'>
                        </div>
                        <div class="col-xl-6" id='warehouse_postcode_div'>
                        </div>
                        
                        <input type="hidden" name="warehouse_address" id="warehouse_address" value="">
                <input type="hidden" name="phone" id="phone" value="">
                <input type="hidden" name="country" id="country" value="">
                <input type="hidden" name="state" id="state" value="">
                <input type="hidden" name="city" id="city" value="">
                <input type="hidden" name="post_code" id="post_code" value="">
                    </div>

                    <div class="row mt-40">
                        <div class="col-lg-12 text-center tooltip-wrapper" data-title=""
                             data-original-title="" title="">
                            <button class="primary-btn fix-gr-bg tooltip-wrapper " id="copyrightBtn">
                                <span class="ti-check"></span>
                                {{__('common.update')}} </button>
                        </div>


                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
(function($){
    "use strict";
    $(document).ready(function(){
        // Function to load pricing based on the selected subscription type
        function loadWarehouse(warehouse_id) {
            console.log('warehouse_id---',warehouse_id)
            if (warehouse_id) {
                $('#pre-loader').removeClass('d-none');
                let get_warehouse_url = "{{ route('admin.warehouse.get_warehouseDetails', ':id') }}";
                get_warehouse_url = get_warehouse_url.replace(':id', warehouse_id);
                console.log('get_warehouse_url',get_warehouse_url)
                $.ajax({
                    url: get_warehouse_url,
                    type: "GET",
                    dataType: "JSON",
                    success: function (response) {
                       console.log('response',response)

                       $("#warehouse_address_div").html(response.address); // Replace .append with .html to prevent repeated appending
                       $("#phone_div").html(response.phone); // Replace .append with .html to prevent repeated appending
                       $("#warehouse_country").html(response.countryOutput); // Replace .append with .html to prevent repeated appending
                       $("#warehouse_state_div").html(response.stateOutput); // Replace .append with .html to prevent repeated appending
                       $("#warehouse_city_div").html(response.cityOutput); // Replace .append with .html to prevent repeated appending
                       $("#warehouse_postcode_div").html(response.post_code); // Replace .append with .html to prevent repeated appending

                       let warehouseString = response.warehouse; // This is the string
                    let warehouse = JSON.parse(warehouseString); // Parse the string into an object

                    // if (warehouse && warehouse.city_id) {   
                    //     let cityId = warehouse.city_id;
                    //     console.log(cityId); // This should log the city ID, e.g., 1750
                    // } else {
                    //     console.log("City ID is undefined");
                    // }


                       console.log('warehouse',warehouse)
                       console.log('warehouse.address',warehouse.address)
                       console.log('warehouse.phone',warehouse.phone)
                       console.log('warehouse.country_id',warehouse.country_id)
                       console.log('warehouse.state_id',warehouse.state_id)
                       console.log('warehouse.city_id',warehouse.city_id)
                       console.log('warehouse.pin_code',warehouse.pin_code)
                       $("#warehouse_address").val(warehouse.address);
                $("#phone").val(warehouse.phone);
                $("#country").val(warehouse.country_id); // If you need country_id, use warehouse.country.id
                $("#state").val(warehouse.state_id); // If you need state_id, use warehouse.state.id
                $("#city").val(warehouse.city_id); // If you need city_id, use warehouse.city.id
                $("#post_code").val(warehouse.pin_code);
                       $('#pre-loader').addClass('d-none');
                        
                    },
                    error: function (error) {
                        $('#pre-loader').addClass('d-none');
                        if (error.responseJSON && error.responseJSON.error) {
                            toastr.error(error.responseJSON.error, "{{__('common.error')}}");
                            return false;
                        }
                    }
                });
            } 

        }

        // Handle subscription type change
        $(document).on('change', '#warehouse_id', function(){
        var warehouse_id = this.value;
        console.log('warehouse_idio', warehouse_id);
        loadWarehouse(warehouse_id);
    });

        // Check for old commission ID and load pricing if necessary
        function warehouseSelected(){
            let warehouse_id = "{{ $seller->sellerAccount->warehouse_id }}";
            console.log('warehouse_id',warehouse_id)
            $('#warehouse_id').val(warehouse_id).niceSelect('update');
                loadWarehouse(warehouse_id);
        }

        warehouseSelected(); // Check and load pricing on page load if old value exists
    });
})(jQuery);
</script>