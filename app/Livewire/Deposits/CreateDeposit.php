<?php

namespace App\Livewire\Deposits;

use App\Livewire\Forms\StoreDepositForm;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class CreateDeposit extends Component
{
    public StoreDepositForm $form;

    public Collection $students;

    /**
     * Initialize the component's state.
     */
    public function mount(): void
    {
        $this->fill([
            'form.date_paid' => now()->toDateString(),
            'form.amount' => 0,
        ]);

        $this->students = Student::all(); // Jika $students dipakai di tampilan
    }



    /**
     * Render the view.
     */
    public function render(): View
    {
        return view('livewire.deposits.create-deposit', [
            'students' => $this->students,
        ]);
    }

    /**
     * Save the form data and handle the related events.
     */
    public function save(): void
    {
        $this->form->store();

        $this->dispatch('close-modal');
        $this->dispatch('success', message: 'Data berhasil ditambahkan!');
        $this->dispatch('deposit-created')->to(DepositCurrentWeekTable::class);
    }
}
