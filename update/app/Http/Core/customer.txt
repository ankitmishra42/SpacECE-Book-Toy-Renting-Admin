<?php

use App\Http\Controllers\API\Additional\AdditionalServiceController;
use App\Http\Controllers\API\Address\AddressController;
use App\Http\Controllers\API\AreaController;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\ForgotPasswordController;
use App\Http\Controllers\API\Banner\BannerController;
use App\Http\Controllers\API\Contacts\ContactController;
use App\Http\Controllers\API\Coupon\CouponController;
use App\Http\Controllers\API\Customers\CardController;
use App\Http\Controllers\API\Customers\CustomerController;
use App\Http\Controllers\API\Master\MasterController;
use App\Http\Controllers\API\Notifications\NotificationsController;
use App\Http\Controllers\API\Order\OrderController;
use App\Http\Controllers\API\Payment\PaymentController as PaymentControllerApi;
use App\Http\Controllers\API\PostCode\PostCodeController;
use App\Http\Controllers\API\Product\ProductController;
use App\Http\Controllers\API\Promotion\PromotionController;
use App\Http\Controllers\API\Rating\RatingController;
use App\Http\Controllers\API\Service\ServiceController;
use App\Http\Controllers\API\Setting\SettingController;
use App\Http\Controllers\API\Social\SocialLinkController;
use App\Http\Controllers\API\Store\StoreController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\Variant\VariantController;
use App\Http\Controllers\Web\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/privacy-policy', function () {
    return view('settings.privacy-policy');
});

// Route::middleware('guest:api')->group(function () {
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('/forgot-password/otp/verify', [ForgotPasswordController::class, 'verifyOtp']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
Route::post('/resend/otp', [ForgotPasswordController::class, 'resendOTP']);
// });
Route::get('/social-link', [SocialLinkController::class, 'index']);

Route::controller(AreaController::class)->group(function () {
    Route::get('/areas', 'index');
});

Route::get('/services', [ServiceController::class, 'index']);

Route::get('/shops', [StoreController::class, 'index']);
Route::get('/shops/{store}', [StoreController::class, 'show']);
Route::get('/shop/ratings/{store}', [StoreController::class, 'ratings']);
Route::get('/order-conditions/{store}', [StoreController::class, 'orderCondition']);
Route::get('/variants', [VariantController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/additional-services', [AdditionalServiceController::class, 'index']);

Route::get('/pick-schedules/{store}/{date}', [OrderController::class, 'pickSchedule']);
Route::get('/delivery-schedules/{store}/{date}', [OrderController::class, 'deliverySchedule']);

Route::get('/payments/{orderId}/{cardId}', [PaymentController::class, 'index']);

Route::middleware(['auth:api', 'role:customer'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/coupons/{coupon:code}/apply', [CouponController::class, 'apply']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}/details', [OrderController::class, 'show']);
    Route::post('/orders/{order}/cancle', [OrderController::class, 'cancle']);
    Route::post('/orders/reorder', [OrderController::class, 'reorder']);

    Route::get('/users/profile', [UserController::class, 'show']);
    Route::post('/users/update', [UserController::class, 'update']);
    Route::post('/users/profile-photo/update', [UserController::class, 'updateProfilePhoto']);
    Route::post('/change/password', [UserController::class, 'updatePassword']);

    Route::get('/customers', [CustomerController::class, 'show']);

    Route::get('/card-list', [CardController::class, 'index']);
    Route::post('/cards', [CardController::class, 'store']);

    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::post('/addresses/{address}', [AddressController::class, 'update']);
    Route::delete('/addresses/{address}', [AddressController::class, 'delete']);
    Route::post('/addresses/{address}/default', [AddressController::class, 'setDefault']);

    Route::get('/ratings', [RatingController::class, 'index']);
    Route::post('/ratings', [RatingController::class, 'store']);

    Route::post('/contact/verify', [AuthController::class, 'mobileVerify']);

    Route::post('/payments', [PaymentControllerApi::class, 'store']);

    Route::get('/notifications', [NotificationsController::class, 'index']);
    Route::post('/notifications', [NotificationsController::class, 'store']);
    Route::post('/notifications/{notification}', [NotificationsController::class, 'update']);
    Route::delete('/notifications/{notification}', [NotificationsController::class, 'delete']);
});

// -------------- Settings API's
Route::get('/legal-pages/{page:slug}', [SettingController::class, 'show']);
Route::get('/about-us', [SettingController::class, 'about']);
Route::get('/banners', [BannerController::class, 'index']);
Route::get('/promotions', [PromotionController::class, 'index']);
Route::post('/contacts', [ContactController::class, 'store']);
Route::get('/master', [MasterController::class, 'index']);
Route::get('/configuration', [MasterController::class, 'configuration']);

Route::get('/post-code', [PostCodeController::class, 'index']);
