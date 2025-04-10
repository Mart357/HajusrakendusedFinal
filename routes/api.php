use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarkerController;

Route::get('/markers', [MarkerController::class, 'index']);
Route::post('/markers', [MarkerController::class, 'store']);
Route::put('/markers/{id}', [MarkerController::class, 'update']);
Route::delete('/markers/{id}', [MarkerController::class, 'destroy']);
