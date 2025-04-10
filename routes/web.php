<?php

use App\Livewire\Administrators\AdministratorTable;
use App\Livewire\Authentication\Login;
use App\Livewire\Authentication\Logout;
use App\Livewire\Deposits\DepositCurrentWeekTable;
use App\Livewire\Deposits\FilterDeposit;
use App\Livewire\Dashboard;
use App\Livewire\SchoolClasses\SchoolClassTable;
use App\Livewire\SchoolMajors\SchoolMajorTable;
use App\Livewire\Students\StudentTable;
use App\Livewire\UpdateProfile;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', Login::class);

    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', Logout::class)->name('logout');

    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/kelas', SchoolClassTable::class)->name('school-classes.index');
    Route::get('/jurusan', SchoolMajorTable::class)->name('school-majors.index');
    Route::get('/pengguna', AdministratorTable::class)->name('administrators.index');
    Route::get('/profil', UpdateProfile::class)->name('update-profiles.index');
    Route::get('/pelajar', StudentTable::class)->name('students.index');

    Route::get('/deposits', DepositCurrentWeekTable::class)->name('deposits.index');
    Route::get('/deposits/filter', FilterDeposit::class)->name('deposits.filter');

});
