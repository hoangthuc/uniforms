@extends('admin.menu.layout_menu')
@section('content')
<?php
    $menu =  App\Menu::get_menu($id);
    $Location =  App\Menu::$data_location;
    $categories = App\Product::get_product_categories();
    $menu_url = App\Menu::get_data_post_type();
    ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 d-flex">
                <a href="javascript:;" data-id="{{ $id }}" data-menu="{{ ($menu->data_menu)?$menu->data_menu:'[]' }}"
                    class="btn btn-outline-info btn-save">Save <span class="d-none">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span></a>
                <a href="javascript:;" data-return ="{{ route('admin.menu') }}" data-id="{{ $id }}" class="btn btn-outline-info btn-delete ml-2">Delete</a>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.menu') }}">Manage Menu</a></li>
                    <li class="breadcrumb-item active">{{ $menu->name }}</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card border-primary mb-3">
                    <div class="card-header bg-primary text-white">Menu Item</div>
                    <div class="card-body">
                        <form id="frmEdit" class="form-horizontal">
                            <div class="form-group">
                                <label for="text">Title</label>
                                <div class="input-group list-pages">
                                    <input type="text" class="form-control item-menu" name="text" id="text"
                                        placeholder="Text">
                                    <div class="input-group-append">
                                        <button type="button" id="myEditor_icon"
                                            class="btn btn-outline-secondary"></button>
                                    </div>

                                    <ul id="page_menu" name="pages" class="d-none">
                                        @foreach ($menu_url as $item)
                                            <li data-url="{{ $item['slug'] }}" data-text="{{ $item['name'] }}" onclick="insert_url(this)">{{ $item['description'] }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <input type="hidden" name="icon" class="item-menu">
                            </div>
                            <div class="form-group">
                                <label for="href">URL</label>
                                <input type="text" class="form-control item-menu" id="href" name="href"
                                    placeholder="URL">
                            </div>
                            <div class="form-group">
                                <label for="target">Target</label>
                                <select name="target" id="target" class="form-control item-menu">
                                    <option value="_self">Self</option>
                                    <option value="_blank">Blank</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Class</label>
                                <input type="text" name="title" class="form-control item-menu" id="title"
                                    placeholder="Class name">
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="button" id="btnUpdate" class="btn btn-primary" disabled><i
                                class="fas fa-sync-alt"></i> Update</button>
                        <button type="button" id="btnAdd" class="btn btn-success"><i class="fas fa-plus"></i>
                            Add</button>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card border-primary mb-3">
                    <div class="card-header bg-primary">
                        <input type="text" placeholder="Name menu" name="name_menu" value="{{ $menu->name }}" class="form-control">
                    </div>
                    <div class="card-body">
                        <ul id="myEditor" class="sortableLists list-group">
                        </ul>
                    </div>
                    <div class="card-footer bg-primary">
                        <div class="form-inline">
                            <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Location: </label>    
                            <select class="form-control d-inline-block" name="location_menu">
                                @foreach($Location as $key => $value)
                                <option value="{{ $key }}" {{ $key == $menu->location ? 'selected':'' }}>{{ $value }}</option>
                                @endforeach
                                </select>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection