<?php

namespace App\Livewire;

use App\Models\Deposit;
use App\Models\SchoolClass;
use App\Models\SchoolMajor;
use App\Models\Student;
use App\Models\User;
use App\Repositories\DepositRepository;
use App\Repositories\StudentRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;
use Carbon\Carbon;

#[Title('Dashboard')]
class Dashboard extends Component
{
    protected DepositRepository $depositRepository;

    protected StudentRepository $studentRepository;

    public string $year;

    private $months = ['jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'agu', 'sep', 'okt', 'nov', 'des'];

    /**
     * Boot the component.
     */
    public function boot(
        DepositRepository $depositRepository,
        StudentRepository $studentRepository,
    ): void {
        $this->depositRepository = $depositRepository;
        $this->studentRepository = $studentRepository;
    }

    /**
     * Initialize the component's state.
     */
    public function mount(): void
    {
        $this->year = now()->year;

        $depositAmount = $this->depositRepository->getMonthlyAmounts($this->year);
        $depositCount = $this->depositRepository->getMonthlyCounts($this->year);

        $this->dispatch(
            'dashboard-chart-loaded',
            amount: $this->fillMissingMonthsCounts($depositAmount->pluck('amount', 'month')),
            count: $this->fillMissingMonthsCounts($depositCount->pluck('count', 'month'))
        );
    }

    /**
     * Update the dashboard chart with cash transaction data for the specified year.
     */
    public function updateChart(): void
    {
        $depositAmount = $this->depositRepository->getMonthlyAmounts($this->year);
        $depositCount = $this->depositRepository->getMonthlyCounts($this->year);

        $this->dispatch(
            'dashboard-chart-updated',
            amount: $this->fillMissingMonthsCounts($depositAmount->pluck('amount', 'month')),
            count: $this->fillMissingMonthsCounts($depositCount->pluck('count', 'month'))
        );
    }

    /**
     * Render the view.
     */
    public function render(): View
    {
        $studentWithMajors = SchoolMajor::select('name', 'abbreviation')->withCount('students')->get();
        $studentGenders = $this->studentRepository->countStudentGender();

        $depositAmountPerYear = Deposit::selectRaw('EXTRACT(YEAR FROM date_paid) AS year, SUM(amount) AS amount')
            ->groupBy('year')
            ->orderBy('year')
            ->get();


        $depositCountPerYear = Deposit::selectRaw('EXTRACT(YEAR FROM date_paid) AS year, COUNT(*) AS count')
            ->groupBy('year')
            ->get();

        $depositCountByGender = Deposit::leftJoin('students', 'deposits.student_id', '=', 'students.id')
            ->selectRaw('students.gender AS gender, COUNT(*) AS total_paid')
            ->groupBy('gender')
            ->get();

        $charts = [
            'counter' => [
                'student' => Student::count(),
                'schoolClass' => SchoolClass::count(),
                'schoolMajor' => SchoolMajor::count(),
                'administrator' => User::count(),
            ],
            'pieChart' => [
                'studentGender' => [
                    'series' => [
                        $studentGenders['male'],
                        $studentGenders['female'],
                    ],
                    'labels' => ['Laki-laki', 'Perempuan'],
                ],
                'studentMajor' => [
                    'series' => $studentWithMajors->pluck('students_count'),
                    'labels' => $studentWithMajors->map(function ($studentMajor) {
                        return "$studentMajor->name ($studentMajor->abbreviation)";
                    }),
                ],
                'depositCountByGender' => [
                    'series' => $depositCountByGender->pluck('total_paid'),
                    'labels' => ['Laki-laki', 'Perempuan'],
                ],
            ],
            'lineChart' => [
                'depositAmountPerYear' => [
                    'series' => $depositAmountPerYear->pluck('amount'),
                    'categories' => $depositAmountPerYear->pluck('year'),
                ],
                'depositCountPerYear' => [
                    'series' => $depositCountPerYear->pluck('count'),
                    'categories' => $depositCountPerYear->pluck('year'),
                ],
            ],
        ];

        return view('livewire.dashboard', [
            'charts' => $charts,
        ]);
    }

    /**
     * Fill in missing counts for each month in the provided collection.
     */
    private function fillMissingMonthsCounts(Collection $collection): array
    {
        $statistics = [];

        for ($i = 1; $i <= 12; $i++) {
            // if key exists so there is a borrowing count on that month
            // if key does not exists there is no borrowing on that month so the count
            // should be 0
            $statistics[$this->months[$i - 1]] = isset($collection[$i]) ? $collection[$i] : 0;
        }

        return $statistics;
    }
}
