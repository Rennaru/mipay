<?php

namespace App\Livewire\Deposits;

use App\Models\Deposit;
use App\Models\Student;
use App\Models\User;
use App\Repositories\DepositRepository;
use App\Repositories\StudentRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Halaman Daftar Pembayaran Minggu Ini')]
class DepositCurrentWeekTable extends Component
{
    use WithPagination;

    protected StudentRepository $studentRepository;

    protected DepositRepository $depositRepository;

    public Collection $students;

    public Collection $users;

    public ?string $query = '';

    public int $limit = 5;

    public string $orderByColumn = 'date_paid';

    public string $orderBy = 'desc';

    public ?array $statistics = [];

    public ?array $currentWeek = [];

    public array $filters = [
        'user_id' => '',
    ];

    /**
     * Boot the component.
     */
    public function boot(
        StudentRepository $studentRepository,
        DepositRepository $depositRepository
    ): void {
        $this->studentRepository = $studentRepository;
        $this->depositRepository = $depositRepository;
    }

    /**
     * Initialize the component's state.
     */
    public function mount(): void
    {
        $this->currentWeek['startOfWeek'] = now()->startOfWeek()->format('d-m-Y');
        $this->currentWeek['endOfWeek'] = now()->endOfWeek()->format('d-m-Y');

        $this->users = User::orderBy('name')->get();
        $this->students = Student::all();
    }

    /**
     * This method is automatically triggered whenever a property of the component is updated.
     */
    public function updated(): void
    {
        $this->resetPage();
    }

    /**
     * Render the view.
     */
    #[On('deposit-created')]
    #[On('deposit-updated')]
    #[On('deposit-deleted')]
    public function render(): View
    {
        $deposits = Deposit::query()
            ->with('student', 'createdBy')
            ->whereBetween('date_paid', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])
            ->when($this->filters['user_id'], function (Builder $query) {
                return $query->where('created_by', '=', $this->filters['user_id']);
            })
            ->search($this->query)
            ->orderBy($this->orderByColumn, $this->orderBy)
            ->paginate($this->limit);

        $depositSummaries = $this->depositRepository->calculateTransactionSums();
        $this->statistics['totalCurrentMonth'] = local_amount_format($depositSummaries['month']);
        $this->statistics['totalCurrentYear'] = local_amount_format($depositSummaries['year']);

        $studentPaidStatus = $this->studentRepository->getStudentPaymentStatus(
            startDate: now()->createFromDate($this->currentWeek['startOfWeek'])->format('Y-m-d'),
            endDate: now()->createFromDate($this->currentWeek['endOfWeek'])->format('Y-m-d')
        );
        $this->statistics['studentsNotPaidThisWeekLimit'] = $studentPaidStatus['studentsNotPaid']->take(6);
        $this->statistics['studentsNotPaidThisWeek'] = $studentPaidStatus['studentsNotPaid'];
        $this->statistics['studentsPaidThisWeekCount'] = $studentPaidStatus['studentsPaid']->count();
        $this->statistics['studentsNotPaidThisWeekCount'] = $studentPaidStatus['studentsNotPaid']->count();

        return view('livewire.deposits.deposit-current-week-table', [
            'deposits' => $deposits,
        ]);
    }

    /**
     * Reset the filter criteria to default values.
     */
    public function resetFilter(): void
    {
        $this->reset([
            'query',
            'limit',
            'orderByColumn',
            'orderBy',
            'filters',
        ]);
    }
}
