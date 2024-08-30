@extends('frontend.amazy.layouts.app')
@section('title')
    {{__('common.category') }}
@endsection
@push('styles')
<style>
/* Category Card */
.category-card {
    width: 100%;
    border: 1px solid #e0e0e0;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 24px;
    background: #ffffff;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* .category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
} */

/* Parent Category */
.parent-category {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}

.category-image {
    height: 50px;
    width: 50px;
    border: none;
    border-radius: 50%;
    margin-right: 12px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.parent-name {
    font-size: 18px;
    color: #333333;
    font-weight: 700;
}

/* Child Categories */
.child-categories {
    padding: 10px 12px;
    background: #f9f9f9;
    border-radius: 6px;
}

.child-category {
    color: #555555;
    font-size: 15px;
    font-weight: 600;
    display: block;
    margin-bottom: 8px;
    text-decoration: none;
    transition: color 0.3s ease;
}

.child-category:hover {
    color: #ff4b5a;
}

/* Third Level Categories */
.third-level {
    padding: 8px 0px 8px 16px;
    width: 100%;
}

.third-level li {
    list-style-type: none;
    margin-bottom: 6px;
}

.third-level li a {
    color: #444444;
    font-size: 14px;
    text-decoration: none;
    transition: color 0.3s ease;
}

.third-level li a:hover {
    color: #ff4b5a;
}

/* Load More Button */
.load-more {
    color: #ff4b5a !important;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
}

.load-more i {
    margin-left: 6px;
    transition: transform 0.3s ease;
}

/* .load-more:hover i {
    transform: rotate(180deg);
} */

</style>
@endpush
@section('content')
<!-- brand_banner::start  -->
<div class="brand_banner d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="branding_text">{{ __('common.category') }}</h3>
            </div>
        </div>
    </div>
</div>
<!-- brand_banner::end  -->
<!-- prodcuts_area ::start  -->
<!-- Category Section -->
<div class="prodcuts_area">
    <div class="container">
        <div class="row">
            <!-- <div class="col-lg-12"> -->
                @foreach($categories as $category)
                    <div class="category-card">
                        <div class="parent-category">
                            <a href="{{ count($category->subCategories) > 0 ? 'javascript:void(0)' : route('frontend.category-product',['slug' => $category->slug, 'item' =>'category']) }}">
                                <div class="parent-img">
                                    <img src="{{ showImage($category->categoryImage->image) }}" class="category-image">
                                </div>
                                <div class="parent-name">{{ $category->name }}</div>
                            </a>
                        </div>
                        @if(!empty($category->subCategories))
                            @foreach($category->subCategories as $subCategory)
                                <div class="child-categories">
                                    <a class="child-category" href="{{ count($subCategory->subCategories) > 0 ? 'javascript:void(0)' : route('frontend.category-product',['slug' => $subCategory->slug, 'item' =>'category']) }}">
                                        {{ $subCategory->name }}
                                    </a>
                                    @if(count($subCategory->subCategories) > 0)
                                        <ul class="third-level">
                                            @foreach($subCategory->subCategories as $key => $thirdCategory)
                                                <li class="{{ $key > 4 ? 'd-none '.Str::slug($subCategory->name):'' }}">
                                                    <a href="{{ route('frontend.category-product',['slug' => $thirdCategory->slug, 'item' =>'category']) }}">
                                                        {{ $thirdCategory->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                            @if(count($subCategory->subCategories) > 5)
                                                <li>
                                                    <a class="load-more" data-class=".{{ Str::slug($subCategory->name) }}" href="javascript:void(0)">
                                                        more <i class='fas fa-angle-down'></i>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center">
                @if ($categories->lastPage() > 1)
                    <x-pagination-component :items="$categories" type="" />
                @endif
            </div>
        </div>
    </div>
    <div class="add-product-to-cart-using-modal"></div>
</div>

@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        $(document).on('click','.load-more',function(){
            let class_name = $(this).attr('data-class');
            $(class_name).toggleClass('d-none');
            if($(this).html() == 'more <i class="fas fa-angle-down"></i>'){
                $(this).html('less <i class="fas fa-angle-up"></i>');
            }else{
                $(this).html('more <i class="fas fa-angle-down"></i>');
            }

        });

    });
</script>
@endpush
@include(theme('partials.add_to_cart_script'))
@include(theme('partials.add_to_compare_script'))
