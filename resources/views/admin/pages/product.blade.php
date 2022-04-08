@extends('admin/layout/admin-layout')
@section('title')
    WebShop - Sản phẩm
@endsection

@section('page-title')
    Sản phẩm
@endsection

@section('content')
<form action="" method="POST">
<table class="table table-striped">
    <tr>
      <th>
        <div class="custom-checkbox custom-control">
          <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
          <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
        </div>
      </th>
      <th>Code</th>
      <th>Tên sản phẩm</th>
      <th>Số tùy chọn</th>
      <th>Ảnh mặc định</th>
    </tr>
    @if (count($product_list) > 0)
    @foreach ($product_list as $item)
        <tr>
            <td>
                <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" name="id" id="{{$item->id}}">
                <label for="{{$item->id}}" class="custom-control-label">&nbsp;</label>            </td>
            <td>
                {{ $item->code }}
            </td>
            <td>
                {{ $item->name }}
            </td>
            <td>
                {{ $item->option_count }}
            </td>
            <td>
                @if (isset($item->default_image))
                    <img src="blob/{{$item->default_image}}" alt="Image">
                @endif
            </td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="10">Không có sản phẩm</td>
        </tr>
    @endif
      
  </table>
</form>
@endsection