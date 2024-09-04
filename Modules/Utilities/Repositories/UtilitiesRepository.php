<?php

namespace Modules\Utilities\Repositories;

use App\Models\User;
use App\Traits\UploadTheme;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Modules\FrontendCMS\Entities\DynamicPage;
use Modules\Marketing\Entities\FlashDeal;
use Modules\Marketing\Entities\NewUserZone;
use Modules\Product\Entities\Brand;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\ProductTag;
use Modules\Seller\Entities\SellerProduct;
use Illuminate\Support\Facades\Hash;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Modules\Blog\Entities\BlogPost;
use Modules\GeneralSetting\Entities\GeneralSetting;
use Modules\ModuleManager\Entities\InfixModuleManager;
use Modules\ModuleManager\Entities\Module;
use Modules\PaymentGateway\Entities\PaymentMethod;
use Modules\Utilities\Entities\XmlSitemap;
use Modules\Visitor\Entities\VisitorHistory;
use ZipArchive;
use Illuminate\Support\Facades\log;
class UtilitiesRepository
{
    use UploadTheme;
    public function updateUtility($utilities)
    {
        if ($utilities == 'optimize_clear') {
            Artisan::call('optimize:clear');

            $dirname = base_path('/bootstrap/cache/');

            if (is_dir($dirname)) {
                $dir_handle = opendir($dirname);
            } else {
                $dir_handle = false;
            }
            if (!$dir_handle)
                return false;
            while ($file = readdir($dir_handle)) {
                if ($file != "." && $file != "..") {
                    if (!is_dir($dirname . "/" . $file))
                        unlink($dirname . "/" . $file);
                    else
                        File::deleteDirectory($dirname . '/' . $file);
                }
            }
            closedir($dir_handle);
        } elseif ($utilities == "clear_log") {
            array_map('unlink', array_filter((array)glob(storage_path('logs/*.log'))));
            array_map('unlink', array_filter((array)glob(storage_path('debugbar/*.json'))));
        } elseif ($utilities == "change_debug") {
            envu([
                'APP_DEBUG' => env('APP_DEBUG') ? "false" : "true"
            ]);
        } elseif ($utilities == "force_https") {
            envu([
                'FORCE_HTTPS' => env('FORCE_HTTPS') ? "false" : "true"
            ]);
        } elseif ($utilities == "xml_sitemap") {
        } else {
            return 'not_done';
        }
        return 'done';
    }

    public function get_xml_data($request)
    {
        $sitemaps = XmlSitemap::all();

        if(in_array('all', $request->sitemap)){
            foreach($sitemaps as $map){
                $map->update([
                    'status' => 1
                ]);
            }
        }else{
            foreach($sitemaps as $map){
                $map->update([
                    'status' => 0
                ]);
            }

            if (in_array('pages', $request->sitemap)) {
                $sitemaps->where('type', 'pages')->first()->update([
                    'status' => 1
                ]);
            }
            if (in_array('products', $request->sitemap)) {
                $sitemaps->where('type', 'products')->first()->update([
                    'status' => 1
                ]);
            }

            if (in_array('categories', $request->sitemap)) {
                $sitemaps->where('type', 'categories')->first()->update([
                    'status' => 1
                ]);
            }

            if (in_array('brands', $request->sitemap)) {
                $sitemaps->where('type', 'brands')->first()->update([
                    'status' => 1
                ]);
            }

            if (in_array('blogs', $request->sitemap)) {
                $sitemaps->where('type', 'blogs')->first()->update([
                    'status' => 1
                ]);
            }

            if (in_array('tags', $request->sitemap)) {
                $sitemaps->where('type', 'tags')->first()->update([
                    'status' => 1
                ]);
            }
            if (in_array('flash_deal', $request->sitemap)) {
                $sitemaps->where('type', 'flash_deal')->first()->update([
                    'status' => 1
                ]);
            }
            if (in_array('new_user_zone', $request->sitemap)) {
                $sitemaps->where('type', 'new_user_zone')->first()->update([
                    'status' => 1
                ]);
            }
        }

        return true;
    }

    public function xml_sitemap_public(){
        $sitemaps = XmlSitemap::where('status', 1)->where('type', '!=', 'all')->get();
        $data = [];
        foreach($sitemaps as $map){
            if($map->type == 'pages'){
                $data['pages'] = DynamicPage::all();
            }
            if($map->type == 'products'){
                $data['products'] = SellerProduct::where('status', 1)->activeSeller()->get();
            }
            if($map->type == 'categories'){
                $data['categories'] = Category::where('status', 1)->get();
            }
            if($map->type == 'brands'){
                $data['brands'] = Brand::where('status', 1)->get();
            }
            if($map->type == 'blogs'){
                $data['blogs'] = BlogPost::where('status', 1)->get();
            }
            if($map->type == 'tags'){
                $data['tags'] = ProductTag::distinct()->with('tag')->get(['tag_id']);
            }
            if($map->type == 'flash_deal'){
                $data['flash_deal'] = FlashDeal::where('status', 1)->first();
            }
            if($map->type == 'new_user_zone'){
                $data['new_user_zone'] = NewUserZone::where('status', 1)->first();
            }
        }
        return $data;
    }

    public function getSitemapConfig(){
        return XmlSitemap::all();
    }

    public function reset_database($request)
    {
        // Log::info('Testing verified');
        $user = DB::table('users')->where('id', 1)->first();
        log::info(json_encode($user));
        $data = (array) $user;
        $data['lang_code'] = 'en';
        $data['currency_id'] = 2;
        $data['currency_code'] = "USD";
        $data['is_verified'] = 1;
        // Log::info('Testing verified setp1');
        $infix_modules = InfixModuleManager::all();
        log::info($infix_modules);
        $setting = [
            'system_domain' => app('general_setting')->system_domain,
            'copyright_text' => app('general_setting')->copyright_text,
            'software_version' => app('general_setting')->software_version,
            'system_version' => app('general_setting')->system_version
        ];
        // Log::info('Testing verified setp2');
        $payment_methods = PaymentMethod::all();
        log::info($payment_methods);
        $modules = Module::all();
        Artisan::call('rate:fresh',array('--force' => true));
        User::where('id', 1)->update($data);
        log::info('User');
        InfixModuleManager::query()->truncate();
        log::info('InfixModuleManager');
        Module::query()->truncate();
        Artisan::call('rate:fresh',array('--force' => true));
        Log::info('Testing verified setp4');
        foreach($infix_modules as $module){
            $module = $module->toArray();
            InfixModuleManager::create($module);
            if($module['purchase_code'] != null){
                Log::info('log1');
                if(!Schema::hasColumn('general_settings', 'general_settings')) {
                    Log::info('log2');
                    $name = $module['name'];
                    Schema::table('general_settings', function ($table) use ($name) {
                        $table->integer($name)->default(1)->nullable();
                        Log::info('log3');
                    });
                    Log::info('Testing verified ');
                }
                Log::info('Testing verified ');
            }
            Log::info('Testing verified ');
        }
        Log::info('Testing verified setp5');
        foreach($modules as $module){
            $module = $module->toArray();
            Module::create($module);
        }
        Log::info('Testing verified setp6');
        foreach($payment_methods as $payment_method){
            PaymentMethod::where('id', $payment_method->id)->update([
                'active_status' => 1
            ]);
        }
        Log::info('Testing verified setp7');
        GeneralSetting::first()->update($setting);
        if(file_exists(asset_path('uploads'))){
            $this->delete_directory(asset_path('uploads'));
        }
        $zip = new ZipArchive;
        Log::info('Testing verified zip');
        $res = $zip->open(asset_path('demo_db/reset_uploads.zip'));
        if ($res === true) {
            $zip->extractTo(storage_path('app/tempResetFile'));
            $zip->close();
        } else {
            abort(500, 'Error! Could not open File');
        }


        $src = storage_path('app/tempResetFile');
        $dst = asset_path('uploads');

        $this->recurse_copy($src, $dst);

        if(file_exists(storage_path('app/tempResetFile'))){
            $this->delete_directory(storage_path('app/tempResetFile'));
        }

        Artisan::call('optimize:clear');
        return true;

    }

    public function import_demo_database($request){
        DB::beginTransaction(); // Start the transaction
    
        try {
            $user = DB::table('users')->where('id', 1)->first();
            $data = (array) $user;
            $data['lang_code'] = 'en';
            $data['currency_id'] = 2;
            $data['currency_code'] = "USD";
            $data['is_verified'] = 1;
            $setting = [
                'system_domain' => app('general_setting')->system_domain,
                'copyright_text' => app('general_setting')->copyright_text,
                'software_version' => app('general_setting')->software_version,
                'system_version' => app('general_setting')->system_version
            ];
            $modules = Module::all();
            $infix_modules = InfixModuleManager::all();
            $payment_methods = PaymentMethod::all();
    
            if(file_exists(asset_path('uploads'))){
                $this->delete_directory(asset_path('uploads'));
            }
    
            $zip = new ZipArchive;
            $res = $zip->open(asset_path('demo_db/demo_uploads.zip'));
            if ($res === true) {
                $zip->extractTo(storage_path('app/tempDemoFile'));
                $zip->close();
            } else {
                abort(500, 'Error! Could not open File');
            }
            $src = storage_path('app/tempDemoFile');
            $dst = asset_path('uploads');
            $this->recurse_copy($src, $dst);
    
            if(file_exists(storage_path('app/tempDemoFile'))){
                $this->delete_directory(storage_path('app/tempDemoFile'));
            }
    
            set_time_limit(2700);
    
            Log::info('Disabling foreign key checks.');
                                                         
            DB::statement('SET CONSTRAINTS ALL DEFERRED');
            Log::info('Checked.');
    
            Artisan::call('db:wipe', ['--force' => true]);
            Log::info('err.');
            if(app('theme')->folder_path == 'amazy'){
                Log::info('code.');
                $sql = asset_path('demo_db/amazy_demo.sql');
                Log::info('code1.');
            }else{
                Log::info('coder.');
                $sql = asset_path('demo_db/amazcart_demo.sql');
                Log::info('coder1.');
            }
            if (file_exists($sql)) {
                $sqlContent = file_get_contents($sql);
                Log::info('SQL file read successfully. Length: ' . strlen($sqlContent));
                $sqlrec = str_replace('`', '"', $sql);
                Log::info('SQL Content: ' . substr($sql, 0, 200));
                // Log::info('Unprepare: ' . DB::unprepared($sqlrec));
                DB::unprepared($sqlrec);
            } else {
                Log::error('SQL file not found: ' . $sql);
                throw new Exception('SQL file not found.');
            }
            // DB::unprepared(file_get_contents($sql));
            Log::info('pre.');
            DB::statement("SET CONSTRAINTS ALL IMMEDIATE");
            Log::info('chk1.');
            DB::statement("SET AUTOCOMMIT=1");
            Artisan::call('rate', ['--force' => true]);
            Log::info('err232.');
    
    
            User::where('id', 1)->update($data);
            InfixModuleManager::query()->truncate();
            Module::query()->truncate();
            foreach($infix_modules as $module){
                InfixModuleManager::create([
                    'name' => $module->name,
                    'email' => $module->email
                ]);
            }
    
            foreach($modules as $module){
                $module = $module->toArray();
                Module::create($module);
            }
            foreach($payment_methods as $payment_method){
                PaymentMethod::where('id', $payment_method->id)->update([
                    'active_status' => 1
                ]);
            }
            GeneralSetting::first()->update($setting);
            Artisan::call('optimize:clear');
            Log::info('done.');

            DB::commit();

            return true;  
    
        } catch (Exception $e) {
            DB::rollBack(); // Rollback the transaction on error
            Log::error('Error in import_demo_database: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function remove_Visitor(){
        VisitorHistory::truncate();
        return true;
    }
}
