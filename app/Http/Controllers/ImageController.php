<?php

namespace App\Http\Controllers;

use App\classes\LRUCache;
use App\Models\ConfigrationModel;
use App\Models\Image;
use App\Models\ReadStatistic;
use App\Models\StatisticsModel;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    protected $cache;
    public function __construct()
    {
        $this->cache =  new LRUCache();
        session()->put('cache', $this->cache);
    }
    public function storeImage(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'image' => 'required'
        ]);
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('/', [
                'disk' => 'uploads'
            ]);
        }
        $data = array_merge($request->except('image'), ['image' => $image_path]);

        $image = Image::where('key', $request->key)->first();

        $ch = session()->get('cache');
        $ch->put($request->key, $image_path);
        session()->put('cache', $ch);

        if ($image) {
            Storage::disk('uploads')->delete($image->image);
            $image->update($data);
            return redirect()->back()->with('success', 'Image updated');
        } else {
            Image::create($data);
            return redirect()->back()->with('success', 'Uploaded successfuly');
        }
    }
    public function getImage(Request $request)
    {
        $cache = session()->get('cache');
        $image = $cache->get($request->key);
        if ($image) {
            session()->put('hitCount', session()->get('hitCount') + 1);
            session()->put('requestsCount', session()->get('requestsCount') + 1);
            return (['image' => $image, 'imageSource' => 'cache', 'cache' => session()->get('cache')->cacheContent(), 'totalCacheSize' => session()->get('totalCacheSize'), 'cacheCapacity' => $cache->getCapacity()]);
        }
        $image = Image::where('key', $request->key)->first();
        if ($image) {
            $ch = session()->get('cache');
            $ch->put($request->key, $image->image);
            session()->put('cache', $ch);

            session()->put('missCount', session()->get('missCount') + 1);
            session()->put('requestsCount', session()->get('requestsCount') + 1);
            return (['image' => $image->image, 'imageSource' => 'database', 'cache' => session()->get('cache')->cacheContent(), 'totalCacheSize' => session()->get('totalCacheSize'), 'cacheCapacity' => $cache->getCapacity()]);
        }
        return 'not found';
    }

    protected function encodeImage($image)
    {
        $path = public_path('uploads/' . $image);
        $encodeIimage = base64_encode(file_get_contents($path));
        return $encodeIimage;
    }

    public function allKeys()
    {
        return view('allKeys', ['images' => Image::all()]);
    }

    public function configration()
    {
        $policies = DB::table('cache_polices')->get();
        $currentConfigData = ConfigrationModel::first();
        return view('configration', ['policies' => $policies, 'currentConfigData' => $currentConfigData]);
    }

    public function statistics()
    {
        $statistics = StatisticsModel::latest()->take(120)->get();
        // $statistics = ReadStatistic::first();
        // if (!$statistics){
        //     $statistic = [
        //         'number_of_items' => 0,
        //         'hit_rate' => 0,
        //         'miss_rate' => 0,
        //         'total_items_size' => 0,
        //         'policy' => session()->get('lr_policy') == 2 ? 'Least Recently Used Replacment' : 'Random Replacement',
        //     ];
        // }
        return view('statistics', ['statistics' => $statistics]);
    }

    public function storeConfig(Request $request)
    {
        $request->validate([
            'replacment_policy' => 'required',
        ]);
        $configData = ConfigrationModel::first();
        if ($configData)
            $configData->update($request->all());
        else {
            ConfigrationModel::create($request->all());
        }
        session()->put('policy', $request->replacment_policy);
        session()->put('cacheCapacity', $request->capacity);
        $cache = session()->get('cache');
        $cache->adjustCacheSize();
        return redirect()->back()->with('success', 'Configration saved');
    }

    public function storeStatistics()
    {
        $cache = session()->get('cache');
        StatisticsModel::create([
            'number_of_items' => $cache->getSize(),
            'total_items_size' => session()->get('totalCacheSize'),
            'count_requests' => session()->get('requestsCount'),
            'miss_rate' => $this->getMissRate(),
            'hit_rate' => $this->getHitRate(),
            'policy' => session()->get('policy')
        ]);
    }


    public function clearCache()
    {
        session()->put('cache', new LRUCache());
        return true;
    }

    protected function getHitRate(){
        return session()->get('requestsCount') == 0 ? 0 : session()->get('hitCount') * 100 / session()->get('requestsCount');
    }

    protected function getMissRate(){
        return session()->get('requestsCount') == 0 ? 0 : session()->get('missCount') * 100 / session()->get('requestsCount');
    }


    // public function getStatistics()
    // {
    //     $hitRate = session()->get('hitCount') * 100 / session()->get('requestsCount');
    //     $missRate = session()->get('missCount') * 100 / session()->get('requestsCount');
    //     $cache = session()->get('cache');
    //     $number_of_items = $cache->getSize();
    //     $total_items_size = session()->get('totalCacheSize');
    //     $policy = session()->get('policy');


    //     session()->put('lr_number_of_items', $number_of_items);
    //     session()->put('lr_total_items_size', $total_items_size);
    //     session()->put('lr_policy', $policy);
    //     session()->put('lr_hit_rate', $hitRate);
    //     session()->put('lr_miss_rate', $missRate);

    //     return ['statistics' => [
    //         'number_of_items' => $number_of_items,
    //         'total_items_size' => $total_items_size,
    //         'miss_rate' => $missRate,
    //         'hit_rate' => $hitRate,
    //     ], 'policy' => $policy];
    // }
}
