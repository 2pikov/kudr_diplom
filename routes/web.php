<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WhereController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\QuickOrderController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ComparisonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/catalog', [CatalogController::class, 'getProducts'])->name('catalog');
Route::get('/product/{id}', [ProductController::class, 'index'])->name('product');
Route::get('/where', [WhereController::class, 'index'])->name('where');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::middleware('auth')->group(function () {
    // Профиль пользователя
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/user', [ProfileController::class, 'index'])->name('user');

    // Отзывы
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Заказы пользователя
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{number}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/my-orders', [OrderController::class, 'createOrder'])->name('orders.create');
    Route::delete('/my-orders/{number}', [OrderController::class, 'deleteOrder'])->name('order-delete');

    // Корзина
    Route::get('/add-to-cart/{id}', [CartController::class, 'addToCart']);
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::get('/changeqty/{param}/{id}', [CartController::class, 'changeQty']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart']);

    // Оформление заказа
    Route::get('/quick-order', [QuickOrderController::class, 'show'])->name('quick-order');
    Route::post('/quick-order', [QuickOrderController::class, 'store'])->name('quick-order.store');
    Route::get('/quick-order/confirm', [QuickOrderController::class, 'confirm'])->name('quick-order.confirm');
    Route::post('/quick-order/verify', [QuickOrderController::class, 'verify'])->name('quick-order.verify');

    // Избранное и сравнение
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/comparisons', [ComparisonController::class, 'index'])->name('comparisons.index');
    Route::post('/comparisons/toggle', [ComparisonController::class, 'toggle'])->name('comparisons.toggle');
    Route::delete('/comparisons/toggle', [ComparisonController::class, 'toggle']);

    // Калькулятор
    Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator');
    Route::post('/calculator/calculate', [CalculatorController::class, 'calculate']);
});

// Админ маршруты
Route::middleware(['auth', 'is-admin'])->group(function () {
    // Управление заказами для админа
    Route::get('/admin/orders', [OrderController::class, 'getOrders'])->name('admin.orders');
    Route::patch('/admin/orders/{number}/{action}', [OrderController::class, 'editOrderStatus'])->name('admin.orders.status');

    // Остальные админ маршруты...
    Route::get('/product-create', [ProductController::class, 'createProductView']);
    Route::post('/product-create', [ProductController::class, 'createProduct']);
    Route::get('/products', [ProductController::class, 'getProducts'])->name('admin.products');
    Route::get('/product-edit/{id}', [ProductController::class, 'getProductById']);
    Route::patch('/product-update/{id}', [ProductController::class, 'editProduct']);
    Route::delete('/product-delete/{id}', [ProductController::class, 'deleteProduct']);

    Route::get('/category-create', [CategoriesController::class, 'createCategoryView']);
    Route::post('/category-create', [CategoriesController::class, 'createCategory']);
    Route::get('/categories', [CategoriesController::class, 'getCategories'])->name('admin.categories');
    Route::get('/category-edit/{id}', [CategoriesController::class, 'getCategoryById']);
    Route::patch('/category-update/{id}', [CategoriesController::class, 'editCategory']);
    Route::delete('/category-delete/{id}', [CategoriesController::class, 'deleteCategory']);
});


require __DIR__ . '/auth.php';
