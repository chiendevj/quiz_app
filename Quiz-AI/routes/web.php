<?php

use App\Http\Controllers\Api\RoomApiController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use App\Livewire\ListQuestions;
use App\Models\Question;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleAndPermissionController;
use App\Http\Controllers\VerificationController;
use App\Livewire\FormCreateQuizImage;

//Home
Route::get('/', function () {
    return view('home');
});
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/quiz', [HomeController::class, 'quiz'])->name('quiz');

// Auth
Route::get('/auth/login', [AuthController::class, "showLogin"])->name("login");
Route::post('/auth/login', [AuthController::class, "login"])->name("handle_login");
Route::get('/auth/register', [AuthController::class, "showRegister"])->name("register");
Route::post('/auth/register', [AuthController::class, "register"])->name("handle_register");
Route::post('/auth/logout', [AuthController::class, "logout"])->name("handle_logout");
Route::post('/auth/update', [AuthController::class, "update"])->name("update_profile");

// Require Auth
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('user_dashboard');

    // Quizz Room Multiple
    Route::get('/quiz-multiple/create', [RoomController::class, 'create'])->name('quiz.multiple.create');
    Route::post('/quiz-multiple/create', [RoomController::class, 'createRoom'])->name('quiz.multiple.handle_create_room');
    Route::get('/quiz-multiple/{id}/join', [RoomController::class, 'wating'])->name('quiz.multiple.join');
    Route::get('/quiz-multiple/{id}', [RoomController::class, 'show']);
    Route::get('/quiz-multiple/{id}/left', [RoomController::class, 'left'])->name('quiz.multiple.left');

    // Quizz Room Single
    Route::get('/quizz-mode-single/start/{id}', [QuizController::class, 'startQuiz'])->name('quiz.start');
    Route::post('/submit-answer/{quizId}/{questionId}', [QuizController::class, 'submitAnswer'])->name('checkAnswer');
    Route::get('/quiz/{id}/question/{questionIndex}', [QuizController::class, 'showQuestion'])->name('quiz.question.show');
    Route::get('/quiz/{id}/result', [QuizController::class, 'showResult'])->name('quiz.result');
});

Route::get('/quizz-mode-single/{id}', [QuizController::class, 'getQuiz'])->name('quiz.play');
Route::get('/search', [QuizController::class, 'search'])->name('quiz.search');

Route::prefix('admin')->middleware(['role_or_permission:super-admin|admin', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');
    Route::get('/quizzes', [QuizController::class, 'indexAdmin'])->name('quizzes.indexAdmin');

    Route::group(['middleware' => ['role:super-admin']], function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/roles', [RoleAndPermissionController::class, 'index'])->name('roles.index');
    });
});

// profile
Route::prefix('profile')->middleware(['auth'])->group(function () {
    Route::get('/my-quiz', [UserController::class, 'quizzes'])->name('profile.quizzes');
});

// User
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');


// Verify
Route::get('email/verify/{id}', [VerificationController::class, 'show'])->name('verification.notice');
Route::post('email/verify/{id}', [VerificationController::class, 'reverify'])->name('handle_reverify');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

// Quizz
Route::get('/quizzes/create/{id?}', [QuizController::class, 'create'])->name('quizzes.create');
Route::get('/quizzes/edit/{id?}', [QuizController::class, 'create'])->name('quizzes.edit');
Route::post('/quizzes/create-with-ai', [ListQuestions::class, 'storeQuizWithAI'])->name('quizzes.storeWithAI');
Route::post('/quizzes/update', [QuizController::class, 'update'])->name('quizzes.update');
Route::delete('/quizzes/question/destroy', [QuestionController::class, 'destroy'])->name('quizzes.question.destroy');
Route::put('/quizzes/question/update', [QuestionController::class, 'update'])->name('quizzes.question.update');
// Route::post('/quizzes/question/store', [QuestionController::class, 'store'])->name('quizzes.question.store');
Route::post('/quizzes/question/store', [ListQuestions::class, 'store'])->name('quizzes.question.store');
Route::post('/quizzes/published', [QuizController::class, 'published'])->name('quizzes.published');
Route::post('/quizzes/setting', [QuizController::class, 'setting'])->name('quizzes.setting');
Route::post('/quizzes/details', [QuizController::class, 'getDetailsQuiz'])->name('quizzes.details');
Route::post('/quizzes/accept', [QuizController::class, 'appectQuiz'])->name('quizzes.accept');
Route::post('/quizzes/destroy', [QuizController::class, 'destroy'])->name('quizzes.destroy');
Route::post('/quizzes/reject', [QuizController::class, 'rejectQuiz'])->name('quizzes.reject');

// Api
Route::get('rooms/{id}', [RoomApiController::class, "show"])->name("get_room_info");
Route::get('rooms/{id}/quizz', [RoomApiController::class, "getQuestion"])->name("get_room_quizz");
Route::post('rooms/{id}/init-point', [RoomApiController::class, "initRoomPoint"])->name("init_point");
Route::get('rooms/{id}/start', [RoomController::class, "show"])->name("quiz.multiple.play");
Route::get('rooms/{id}/users', [RoomApiController::class, "getAllUserInRoom"])->name("quiz.multiple.users");
Route::post('rooms/{id}/user/point', [RoomApiController::class, "updateUserPoint"])->name("quiz.multiple.update.user.point");
Route::post('rooms/{id}/close', [RoomApiController::class, "closeRoom"])->name("quiz.multiple.close.room");
Route::post('rooms/{id}/restart', [RoomApiController::class, "restartRoom"])->name("quiz.multiple.restart.room");

