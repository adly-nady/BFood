@extends('layouts.admin.app')

@section('title', translate('Add new addon'))

@section('content')
<style>
    .image-upload-container {
        position: relative;
        width: 200px;
        height: 200px;
        border: 2px dashed #ccc;
        border-radius: 10px;
        cursor: pointer;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .upload-icon {
        font-size: 50px;
        color: #ccc;
    }

    .upload-text {
        color: #666;
        margin-top: 10px;
    }

    #image-preview {
        position: absolute;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }

    #file-input {
        display: none;
    }
</style>
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <img width="20" class="avatar-img" src="{{asset('public/assets/admin/img/icons/attribute.png')}}" alt="">
                <span class="page-header-title">
                    {{translate('Add_New_Addon')}} 
                </span>
            </h2>
        </div>

        <div class="row g-3">
            <div class="col-12">
                <div class="mt-3">
                    <div class="card">
                        <div class="card-top px-card pt-4">
                            <div class="d-flex flex-column flex-md-row flex-wrap gap-3 justify-content-md-between align-items-md-center">
                                <h5 class="d-flex align-items-center gap-2">
                                    {{translate('Addon_Table')}}
                                    <span class="badge badge-soft-dark rounded-50 fz-12">{{ $addons->total() }}</span>
                                </h5>

                                <div class="d-flex flex-wrap justify-content-md-end gap-3">
                                    <form action="{{url()->current()}}" method="GET">
                                        <div class="input-group">
                                            <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{translate('Search by Addon name')}}" aria-label="Search" value="{{$search}}" required="" autocomplete="off">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary"> {{translate('Search')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#adAddondModal">
                                        <i class="tio-add"></i>
                                        {{translate('Add_Addon')}}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="py-4">
                            <div class="table-responsive datatable-custom">
                                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{translate('SL')}}</th>
                                            <th>{{translate('name')}}</th>
                                            <th>{{translate('image')}}</th>
                                            <th>{{translate('price')}}</th>
                                            <th class="text-center">{{translate('tax')}} (%)</th>
                                            <th class="text-center">{{translate('action')}}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($addons as $key=>$addon)
                                        <tr>
                                            <td>{{$addons->firstitem()+$key}}</td>
                                            <td>
                                                <div>
                                                    {{$addon['name']}}
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <img src="{{ asset('public/storage/' . $addon->image) }}" style="width: 200px" alt="صورة الإضافة">
                                                </div>
                                            </td>
                                            {{--  --}}
                                            <td>{{ Helpers::set_symbol($addon['price']) }}</td>
                                            <td class="text-center">{{ $addon['tax'] }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a class="btn btn-outline-info btn-sm edit square-btn"
                                                        href="{{route('admin.addon.edit',[$addon['id']])}}"><i class="tio-edit"></i></a>
                                                    <button class="btn btn-outline-danger btn-sm delete square-btn" type="button"
                                                        onclick="form_alert('addon-{{$addon['id']}}','{{translate('Want to delete this addon')}} ?')"><i class="tio-delete"></i></button>
                                                </div>
                                                <form action="{{route('admin.addon.delete',[$addon['id']])}}"
                                                        method="post" id="addon-{{$addon['id']}}">
                                                    @csrf @method('delete')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive mt-4 px-3">
                                <div class="d-flex justify-content-lg-end">
                                    {!! $addons->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="adAddondModal" tabindex="-1" role="dialog" aria-labelledby="adAddondModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <form action="{{route('admin.addon.store')}}"  method="post" enctype="multipart/form-data">
                            @csrf

                            @php($data = Helpers::get_business_settings('language'))
                            @php($defaultLang = Helpers::get_default_language())

                            {{-- <h1>adly</h1> --}}

                            @if ($data && array_key_exists('code', $data[0]))
                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach ($data as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link {{ $lang['default'] == true ? 'active' : '' }}" href="#"
                                        id="{{ $lang['code'] }}-link">{{ Helpers::get_language_name($lang['code']) . '(' . strtoupper($lang['code']) . ')' }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            
                            <div class="row">
                                
                                <div class="col-sm-12">
                                    
                                    @foreach ($data as $lang)
                                        <div class="form-group {{ $lang['default'] == false ? 'd-none' : '' }} lang_form" id="{{ $lang['code'] }}-form">
                                            <label class="input-label" for="exampleFormControlInput1">{{ translate('name') }} ({{ strtoupper($lang['code']) }})</label>
                                            <input type="text" name="name[]" class="form-control" placeholder="{{translate('New addon')}}"
                                                {{$lang['status'] == true ? 'required':''}} maxlength="255"
                                                @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    @endforeach
                            @else
                                    <div class="row">
                                        <div class="col-sm-12 mb-4">
                                            <div class="form-group lang_form" id="{{ $defaultLang }}-form">
                                                <label class="input-label" for="exampleFormControlInput1">{{ translate('name') }} ({{ strtoupper($defaultLang) }})</label>
                                                <input type="text" name="name[]" class="form-control" maxlength="255" placeholder="{{ translate('New addon') }}" required>
                                            </div>
                                            <input type="hidden" name="lang[]" value="{{ $defaultLang }}">
                            @endif
                            {{-- <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group lang_form" id="file">
                                        <label class="input-label" for="exampleFormControlInput1">{{ translate('name') }} ({{ strtoupper($defaultLang) }})</label>
                                        <input type="file" class="form-control" name="images" id="file">
                                    </div>
                                </div>
                            </div> --}}
                            <div class="form-group lang_form">
                                <label class="input-label">{{ translate('image') }}</label>
                                <div class="image-upload-container" onclick="document.getElementById('file-input').click()">
                                    <svg class="upload-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/>
                                    </svg>
                                    <span class="upload-text">{{ translate('click_to_upload') }}</span>
                                    <img id="image-preview" alt="Preview">
                                </div>
                                <input type="file" name="images" id="file-input" accept="image/*">
                            </div>


                                            <input name="position" class="position-area" value="0">
                                        </div>
                                        <div class="col-sm-6 from_part_2 mb-4">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('price')}}</label>
                                            <input type="number" min="0" name="price" step="any" class="form-control"
                                                placeholder="100" required
                                                oninvalid="document.getElementById('en-link').click()">
                                        </div>
                                        <div class="col-sm-6 from_part_2 mb-4">
                                            <label class="input-label" for="exampleFormControlInput1">{{translate('tax')}} (%)</label>
                                            <input type="number" min="0" name="tax" step="any" class="form-control"
                                                   placeholder="5" required
                                                   oninvalid="document.getElementById('en-link').click()">
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end gap-3">
                                                <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                                                <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

@endsection

@push('script_2')
    <script>
        "use strict";

        // $(".lang_link").click(function(e){
        //     e.preventDefault();
        //     $(".lang_link").removeClass('active');
        //     $(".lang_form").addClass('d-none');
        //     $(this).addClass('active');

        //     let form_id = this.id;
        //     let lang = form_id.split("-")[0];

        //     $("#"+lang+"-form").removeClass('d-none');
        //     if(lang == '{{$defaultLang}}')
        //     {
        //         $(".from_part_2").removeClass('d-none');
        //     }
        //     else
        //     {
        //         $(".from_part_2").addClass('d-none');
        //     }
        // });
        
    document.getElementById('file-input').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('image-preview');
        const container = document.querySelector('.image-upload-container');
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                container.querySelector('.upload-icon').style.display = 'none';
                container.querySelector('.upload-text').style.display = 'none';
            }
            
            reader.readAsDataURL(file);
        }
    });
    </script>

@endpush
