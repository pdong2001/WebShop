@extends('home/layout/layout')
@section('main-content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="/assets/home/img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>GUCCI SHOP</h2>
                        <div class="breadcrumb__option">
                            <a href="/">Home</a>
                            <span>Shop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Product Section Begin -->
    <section class="product spad" ng-app="myApp" ng-controller="myController">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">
                        <div class="sidebar__item">
                            <h4>Loại sản phẩm</h4>
                            <ul>
                                @foreach ($categories as $item)
                                    <li><a href="/shop">{{ $item->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="sidebar__item">
                            <div class="latest-product__text">
                                <h4>Sản phẩm mới</h4>
                                <div class="latest-product__slider owl-carousel">
                                    <div class="latest-prdouct__slider__item">
                                        @for ($i = 0; $i < $lastest_products->count() - 3; $i++)
                                            @php
                                                $product = $lastest_products[$i];
                                            @endphp
                                            <a href="#" class="latest-product__item">
                                                <div class="latest-product__item__pic">
                                                    <img src="/api/files/{{ $product->image->file_path }}" alt="">
                                                </div>
                                                <div class="latest-product__item__text">
                                                    <h6> {{ $product->name }} </h6>
                                                    <span> {{ $product->details[0]->out_price }} </span>
                                                </div>
                                            </a>
                                        @endfor
                                    </div>
                                    <div class="latest-prdouct__slider__item">
                                        @for ($i = 3; $i < $lastest_products->count(); $i++)
                                            @php
                                                $product = $lastest_products[$i];
                                            @endphp
                                            <a href="#" class="latest-product__item">
                                                <div class="latest-product__item__pic">
                                                    <img src="/api/files/{{ $product->image->file_path }}" alt="">
                                                </div>
                                                <div class="latest-product__item__text">
                                                    <h6> {{ $product->name }} </h6>
                                                    <span> {{ $product->details[0]->out_price }} </span>
                                                </div>
                                            </a>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-7">
                    <div class="filter__item">
                        <div class="row">
                            <div class="col-lg-4 col-md-5">
                                <div class="filter__sort">
                                    <span>Sắp xếp theo</span>
                                    <select>
                                        <option ng-click="order('', '')" value="0">Mặc định</option>
                                        <option ng-click="order('out_price','ASC')" value="0">Giá tăng dần</option>
                                        <option ng-click="order('out_price','ASC')" value="0">Giá giảm dần</option>
                                        <option ng-click="order('created_at','DESC')" value="0">Giá ngày ra mắt</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="filter__found">
                                    <h6>Đã tìm thấy <span>@{{totalRecords}}</span>sản phẩm</h6>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-3">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div ng-repeat="prod in data" class="col-lg-4 col-md-6 col-sm-6">
                            <div class="product__item">
                                <div class="product__item__pic" style="background-position: center;background-image: url('/api/files/@{{prod.default_image.file_path}}')">
                                    <ul class="product__item__pic__hover">
                                        <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6><a href="#">Crab Pool Security</a></h6>
                                    <h5> @{{prod.details[0].out_price | currency : "đ"}} </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product__pagination">
                        <a ng-class="page > 1? 'page-item': 'page-item disabled'" class="page-link"
                                ng-click="loadPage(page - 1)" style="cursor: pointer;"><
                        </a>
                        <a ng-class="i == page ? 'page-item active' : 'page-item'"
                            ng-repeat="i in [] | page: page : totalRecords: limit" class="page-link"
                                style="cursor: pointer;" ng-click="loadPage(i)">@{{ i }}</a>
                        <a ng-class="page < totalRecords / limit? 'page-item': 'page-item disabled'"
                                class="page-link" style="cursor: pointer;" ng-click="loadPage(page + 1)">>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->
@endsection

@section('scripts')
    <script src="/assets/home/js/appController.js"></script>
    <script src="/assets/home/js/productExtend.js"></script>
@endsection
