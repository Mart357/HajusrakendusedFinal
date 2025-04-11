<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    
     public function __invoke(Request $request)
    {
        $city = 'Kuressaare';
        
        return Inertia::render('Dashboard', [
            'weather' => Cache::remember('weather-'.$city, now()->addHour(), fn () => $this->getWeatherData($city)),
            'markers' => Marker::all(),
        ]);
    
    }

 private function getWeatherData(string $city)
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $city,
            'units' => 'metric',
            'appid' => config('services.open_weather_map.key'),
        ]);

        return $response->json();
    }
}
