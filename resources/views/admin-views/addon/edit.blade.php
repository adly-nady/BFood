@extends('layouts.admin.app')

@section('title', translate('Update Addon'))

@section('content')
<style>
    .image-upload-container {
        position: relative;
        width: 500px;
        height: 250px;
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
                    {{translate('Addon_Update')}}
                </span>
            </h2>
        </div>

        <div class="row g-3">
            <div class="col-12">
                <div class="card card-body">
                    <form action="{{route('admin.addon.update',[$addon['id']])}}" method="post" enctype="multipart/form-data">
                        @csrf

                        @php($data = Helpers::get_business_settings('language'))
                        @php($defaultLang = Helpers::get_default_language())

                        @if($data && array_key_exists('code', $data[0]))
                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach($data as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link {{$lang['default'] == true ? 'active':''}}" href="#" id="{{$lang['code']}}-link">{{ Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="row">
                                <div class="col-sm-12">
                                    @foreach($data as $lang)
                                        <?php
                                        if(count($addon['translations'])){
                                            $translate = [];
                                            foreach($addon['translations'] as $t)
                                            {
                                                if($t->locale == $lang['code'] && $t->key=="name"){
                                                    $translate[$lang['code']]['name'] = $t->value;
                                                }
                                            }
                                        }
                                        ?>
                                            <div class="form-group {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                                <label class="input-label" for="exampleFormControlInput1">{{translate('name')}} ({{strtoupper($lang['code'])}})</label>
                                                <input type="text" name="name[]"
                                                    class="form-control"
                                                    placeholder="{{ translate('New Addon') }}"
                                                    value="{{$lang['code'] == 'en' ? $addon['name']:($translate[$lang['code']]['name']??'')}}"
                                                    {{$lang['status'] == true ? 'required':''}} maxlength="255"
                                                    @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                            </div>
                                        <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                    @endforeach
                                    @else
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group lang_form" id="{{$defaultLang}}-form">
                                                    <label class="input-label" for="exampleFormControlInput1">{{translate('name')}} ({{strtoupper($defaultLang)}})</label>
                                                    <input type="text" name="name[]" value="{{$addon['name']}}" class="form-control" placeholder="{{translate('New Addon')}}" required maxlength="255">
                                                </div>
                                                <input type="hidden" name="lang[]" value="{{$defaultLang}}">
                                                @endif
                                                <div class="form-group lang_form">
                                                    <label class="input-label">{{ translate('image') }}</label>
                                                    <div class="image-upload-container" onclick="document.getElementById('file-input').click()">
                                                        <svg class="upload-icon" viewBox="0 0 24 24" fill="currentColor" style="{{ $addon->image ? 'display:none;' : '' }}">
                                                            <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/>
                                                        </svg>
                                                        <span class="upload-text" style="{{ $addon->image ? 'display:none;' : '' }}">{{ translate('click_to_upload') }}</span>
                                                        <img id="image-preview" 
                                                             src="{{ $addon->image ? asset('public/storage/' . $addon->image) : '' }}" 
                                                             alt="Preview"
                                                             style="{{ $addon->image ? 'display:block;' : 'display:none;' }}">
                                                    </div>
                                                    <input type="file" name="images" id="file-input" accept="image/*" style="display: none">
                                                    
                                                    
                                                </div>
                                                <input name="position" class="position-area" value="0">
                                            </div>
                                            <div class="col-sm-6 from_part_2">
                                                <div class="form-group">
                                                    <label class="input-label" for="exampleFormControlInput1">{{translate('price')}}</label>
                                                    <input type="number" min="0" step="any" name="price"
                                                        value="{{$addon['price']}}" class="form-control"
                                                        placeholder="200" required
                                                        oninvalid="document.getElementById('en-link').click()">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 from_part_2">
                                                <div class="form-group">
                                                    <label class="input-label" for="exampleFormControlInput1">{{translate('tax')}} (%)</label>
                                                    <input type="number" min="0" step="any" name="tax"
                                                           value="{{$addon['tax']}}" class="form-control"
                                                           placeholder="5" required
                                                           oninvalid="document.getElementById('en-link').click()">
                                                </div>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <div class="d-flex justify-content-end gap-3">
                                                    <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                                                    <button type="submit" class="btn btn-primary">{{translate('update')}}</button>
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

        $(".lang_link").click(function(e){
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            $("#"+lang+"-form").removeClass('d-none');

            if(lang == '{{$defaultLang}}')
            {
                $(".from_part_2").removeClass('d-none');
            }
            else
            {
                $(".from_part_2").addClass('d-none');
            }
        });
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
