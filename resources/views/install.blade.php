@extends('layouts.blank')

@section('content')
    <div class="text-center text-white mb-4">
        <h2>{{ translate('eFood Software Installation') }}</h2>
        <h6 class="fw-normal">{{ translate('Please proceed step by step with proper data according to instructions') }}</h6>
    </div>

    <div class="pb-2 px-2 px-sm-5 mx-xl-4">
        <div class="progress cursor-pointer" role="progressbar" aria-label="eFood Software Installation"
             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" data-bs-toggle="tooltip"
             data-bs-placement="top" data-bs-custom-class="custom-progress-tooltip" data-bs-title="Intro Step!"
             data-bs-delay='{"hide":1000}'>
            <div class="progress-bar width-0-percent"></div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="p-4 my-md-6 mx-xl-4 px-md-5">
          
            <div class="bg-light p-4 rounded mb-4">

                <div class="px-md-4 pb-sm-3">
                    <form action="{{ route('install') }}" method="POST" class="container mt-5 p-4 border rounded bg-light shadow-sm">
                        @csrf
                    
                        <h4 class="mb-3 text-primary">بيانات الموقع</h4>
                        <div class="mb-3">
                            <input type="text" name="web_name" class="form-control" placeholder="اسم الموقع" required>
                        </div>
                    
                        <h4 class="mb-3 text-primary">بيانات الأدمن</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" name="admin_f_name" class="form-control" placeholder="الاسم الأول" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" name="admin_l_name" class="form-control" placeholder="الاسم الأخير">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="email" name="admin_email" class="form-control" placeholder="الإيميل" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="password" name="admin_password" class="form-control" placeholder="كلمة المرور" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="password" name="admin_password_confirmation" class="form-control" placeholder="تأكيد كلمة المرور" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input type="text" name="phone_code" class="form-control" placeholder="كود الهاتف (مثال: +20)">
                            </div>
                            <div class="col-md-9 mb-3">
                                <input type="text" name="admin_phone" class="form-control" placeholder="رقم الهاتف">
                            </div>
                        </div>
                    
                        <h4 class="mb-3 text-primary">بيانات قاعدة البيانات</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" name="DB_HOST" class="form-control" placeholder="DB Host" value="127.0.0.1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" name="DB_DATABASE" class="form-control" placeholder="DB Name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" name="DB_USERNAME" class="form-control" placeholder="DB Username" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="password" name="DB_PASSWORD" class="form-control" placeholder="DB Password">
                            </div>
                        </div>
                    
                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-5">تثبيت</button>
                        </div>
                    </form>
                    
                </div>
            </div>

        </div>
    </div>
@endsection
