<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class InstallCustom extends Controller
{   
    public function index()
    {
        if($this->checkDatabaseExists("'".env('DB_DATABASE')."'"))
        {
            return redirect()->route('admin.auth.login');
        }else
        {
            return view('install');
        }
    }
    public function install(Request $request)
    {
        if (!$this->check_database_connection($request->DB_HOST, $request->DB_DATABASE, $request->DB_USERNAME, $request->DB_PASSWORD)) {
            return back()->with('error', 'خطأ في الاتصال بقاعدة البيانات.');
        }
    
        $this->writeEnvFile($request);
    
        try {
            Artisan::call('db:wipe'); 
            $sqlPath = base_path('installation/backup/database.sql');
            DB::unprepared(file_get_contents($sqlPath));
        } catch (\Exception $e) {
            return back()->with('error', 'فشل استيراد قاعدة البيانات. تأكد من التصاريح.');
        }
    
        DB::table('admins')->insertOrIgnore([
            'f_name' => $request['admin_f_name'] ?? "Admin",
            'l_name' => $request['admin_l_name'] ?? "User",
            'email' => $request['admin_email'],
            'password' => bcrypt($request['admin_password']),
            'phone' => $request['phone_code'] . $request['admin_phone'],
            'admin_role_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    
        DB::table('business_settings')->updateOrInsert(
            ['key' => 'restaurant_name'],
            ['value' => $request['web_name'] ?? 'My Business']
        );
    
        return redirect()->route('admin.dashboard')->with('success', 'تم التثبيت بنجاح!');
    }
    
    private function writeEnvFile($request)
    {
        $key = base64_encode(random_bytes(32));
        $content = "
            APP_NAME=efood
            APP_ENV=live
            APP_KEY=base64:$key
            APP_DEBUG=false
            APP_INSTALL=true
            APP_URL=" . URL::to('/') . "
            
            DB_CONNECTION=mysql
            DB_HOST={$request->DB_HOST}
            DB_PORT=3306
            DB_DATABASE={$request->DB_DATABASE}
            DB_USERNAME={$request->DB_USERNAME}
            DB_PASSWORD={$request->DB_PASSWORD}
            
            CACHE_DRIVER=file
            SESSION_DRIVER=file
            QUEUE_DRIVER=sync";
    
        file_put_contents(base_path('.env'), $content);
    }
    private function check_database_connection($host, $database, $username, $password)
    {
        try {
            $connection = @mysqli_connect($host, $username, $password, $database);
            if ($connection) {
                mysqli_close($connection);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
    function checkDatabaseExists($databaseName)
    {
        try {
            $result = DB::selectOne("
                SELECT COUNT(*) AS count 
                FROM information_schema.schemata 
                WHERE schema_name = ?
            ", [$databaseName]);
    
            return $result->count > 0 ? 1 : 0;
        } catch (\Exception $ex) {
            return 0;
        }
    }
    
}
